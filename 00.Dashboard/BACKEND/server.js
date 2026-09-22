'use strict';
const fs = require('fs');
const fsp = require('fs').promises;
const http = require('http');
const path = require('path');
const { WebSocketServer } = require('ws');

// Log File 01. CTF: /var/log/auth.log
const LOG_FILE = '../../01.Vulnerable-SSH-Server/log/auth.log'; // ou /var/log/secure sur RHEL
const PORT = 3000;
const TAIL_LINES = 20; // lignes initiales envoyées à la connexion

const server = http.createServer((req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/html' });
  res.end(fs.readFileSync(path.join(__dirname, 'index.html')));
});

const wss = new WebSocketServer({ server, path: '/ws' });

// --- Lire les N dernières lignes (snapshot initial) ---
async function readLastNLines(filePath, n, chunkSize = 64 * 1024) {
  const handle = await fsp.open(filePath, 'r');
  try {
    const stat = await handle.stat();
    let fileSize = stat.size;
    if (fileSize === 0) return '';
    let position = fileSize;
    let lines = [];
    let leftover = '';
    while (position > 0 && lines.length <= n) {
      const toRead = Math.min(chunkSize, position);
      position -= toRead;
      const buffer = Buffer.allocUnsafe(toRead);
      await handle.read(buffer, 0, toRead, position);
      const chunk = buffer.toString('utf8') + leftover;
      const parts = chunk.split('\n');
      leftover = parts.shift();
      lines = parts.concat(lines);
      if (lines.length > n) lines = lines.slice(lines.length - n);
    }
    if (lines.length < n && leftover) lines.unshift(leftover);
    return lines.slice(-n).join('\n');
  } finally {
    await handle.close();
  }
}

// --- Tailer : détecte les nouvelles lignes en polling ---
class Tailer {
  constructor(filePath, onLines) {
    this.filePath = filePath;
    this.onLines = onLines;
    this.fd = null;
    this.offset = 0n;
    this.timer = null;
    this.intervalMs = 300;
    this.partial = '';
  }

  async start() {
    this.fd = await fsp.open(this.filePath, 'r');
    const stat = await this.fd.stat();
    this.offset = BigInt(stat.size);
    this.timer = setInterval(() => this.tick().catch(() => {}), this.intervalMs);
  }

  async stop() {
    if (this.timer) clearInterval(this.timer);
    if (this.fd) await this.fd.close();
    this.timer = null;
    this.fd = null;
  }

  async tick() {
    if (!this.fd) return;
    const stat = await this.fd.stat();
    const size = BigInt(stat.size);
    if (size <= this.offset) return; // pas de nouvelle donnée
    const toRead = Number(size - this.offset);
    const buffer = Buffer.allocUnsafe(Math.min(toRead, 64 * 1024));
    let remaining = toRead;
    let pos = this.offset;
    let text = '';
    while (remaining > 0) {
      const len = Math.min(buffer.length, remaining);
      const { bytesRead } = await this.fd.read(buffer, 0, len, pos);
      if (bytesRead === 0) break;
      text += buffer.toString('utf8', 0, bytesRead);
      pos += BigInt(bytesRead);
      remaining -= bytesRead;
    }
    this.offset = size;
    text = this.partial + text;
    const parts = text.split('\n');
    this.partial = parts.pop() ?? ''; // garde la ligne incomplète
    if (parts.length > 0) this.onLines(parts);
  }
}

// --- Broadcast à tous les clients ---
function broadcast(json) {
  const msg = JSON.stringify(json);
  for (const client of wss.clients) {
    if (client.readyState === 1) client.send(msg);
  }
}

const tailer = new Tailer(LOG_FILE, (lines) => {
  if (lines.length) broadcast({ type: 'append', lines });
});

let tailerStarted = false;

wss.on('connection', async (ws) => {
  // 1. Envoie un snapshot des dernières lignes
  try {
    const last = await readLastNLines(LOG_FILE, TAIL_LINES);
    ws.send(JSON.stringify({
      type: 'snapshot',
      lines: last ? last.split('\n') : []
    }));
  } catch (e) {
    ws.send(JSON.stringify({ type: 'error', message: e.message }));
  }

  // 2. Démarre le tailer au premier client
  if (!tailerStarted) {
    tailerStarted = true;
    tailer.start().catch(() => {});
  }

  // 3. Stop le tailer si plus aucun client
  ws.on('close', () => {
    if (wss.clients.size === 0) {
      tailer.stop().catch(() => {});
      tailerStarted = false;
    }
  });
});

server.listen(PORT, () => {
  console.log(`HTTP: http://172.16.0.90:${PORT}`);
  console.log(`WS:   ws://172.16.0.90:${PORT}/ws`);
  console.log(`Tailing: ${LOG_FILE}`);
});   