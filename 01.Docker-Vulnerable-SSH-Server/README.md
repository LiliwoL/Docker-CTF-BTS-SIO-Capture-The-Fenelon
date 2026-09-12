---
tags:
  - CTF
  - B3
  - bruteforce
  - ssh
  - docker
version: "0.2"
Niveau: Débutant
Sources:
  - https://github.com/LiliwoL/Capture-the-Fenelon/tree/master/01.Docker-Vulnerable-SSH-Server
---
---
```dataviewjs
// Récupérer les métadonnées du fichier actuel
let frontmatter = dv.current().file.frontmatter;

// Définir les champs à exclure (adapter si nécessaire)
let excludeFields = ['created'];

// Extraire et filtrer les clés et les valeurs en fonction de la liste d'exclusion
let filtered_keys = Object.keys(frontmatter).filter(key => ! excludeFields.includes(key));
let filtered_values = filtered_keys.map(key => frontmatter[key]);

// Définir un tableau pour contenir chaque ligne du tableau
let rows = [];
// Remplir le tableau rows avec les paires clé-valeur
for (let i = 0; i < filtered_keys.length; i++) {
	// Convertir les valeurs de tableau (par exemple, les tags) en une seule chaîne séparée par des virgules
	let value = Array.isArray(filtered_values[i]) ? filtered_values[i].join(', ') : filtered_values[i];
	rows.push([filtered_keys[i], value]);
}

// Générer un tableau avec deux colonnes et le remplir avec les lignes
dv.table(['Propriétés', 'Valeurs'], rows);
```

---


![](readme_docs/5068cf6fd917905d8faac4549f7f3e08.png)

# Objectif

Un jeu **Capture The Fenelon** pour tester ses connaissances en **bruteforcing**.

Tester la vulnérabilité du serveur SSH et proposer des solutions pour renforcer la sécurité du serveur SSH.

---
# Notions à étudier

- Brute Force
- Scanner de port
- Accès SSH

---
# A lire avant de réaliser ce CTF

- [[🏫 Cours - 01. Force Brute]]
- [[🚀 TP - 04. SLAM2 - Brute Force - Brute Force maison]]
- [[🚀 TP - 05. SLAM2 - Brute Force SSH avec Hydra]]
- [[🏫 Cours - 04. Contre-mesures]]
* [[🚀 TP - 03. Python - Scanner de port]]
* [[🚀 TP - 01. PHP - Scanner de port]]
- [[🔧 NMAP - Scanner de ports]]

---
# Règles du jeu (à fournir aux étudiants)

- Un serveur est mal configuré et vulnérable
- L'adresse IP du serveur *(et éventuellement son port)* sont fournis
- Vous devez trouver 3 **fenelon** sur cette machine
    - Un **fenelon** est situé à l'intérieur d'un fichier nommé **fenelon.txt**
- Chacun des **fenelon** devra être validé sur une plateforme spécifique

---
# Technique

## Informations

| IP                           | Port |
| ---------------------------- | ---- |
| selon la machine qui héberge | 2222 |

## Liste des utilisateurs existants

> A modifier dans le fichier **.env**!

|User|Password|
|---|---|
|user1|...|
|admin|...|
|sio|...|
## Dossiers et fichiers

- ssh_public_keys/
    - Placez-y la clé publique autorisée à se connecter au serveur et nommez la **id_rsa_prof.pub**

---
# Scénarios pédagogiques possibles

1. On fournit aux étudiants l'**adresse IP** du serveur sous forme de **jeu de piste**, d'une **solution à une énigme**...
## Scénario 1 (simple)

1. On informe qu'ils doivent scanner les ports à la recherche d'un serveur SSH
2. On indique le nom des utilisateurs existants
3. On les informe qu'ils doivent faire du BruteForce
4. Un dictionnaire de mot de passe est fourni 
	[https://github.com/tarraschk/richelieu/blob/master/french_passwords_top20000.txt](https://github.com/tarraschk/richelieu/blob/master/french_passwords_top20000.txt)

---
# Solutions

- Complexité des mots de passe des utilisateurs trop faible
- Emplacement des **fenelon** dans le répertoire **home** des utilisateurs.
# Propositions d'amélioration pour sécuriser le serveur

- Améliorer la complexité des mots de passe
- Eviter de trop nombreuses tentatives
- Augmenter le délai entre deux tentatives
- Interdire les connexions par mot de passe

# Propositions d'amélioration du brute force

- Tester une ligne sur 2 (ou 3) avec un camarade
- Un camarade lit le dictionnaire en partant de la fin, l'autre du début
- Faire du multithreading [https://c0deman.wordpress.com/2014/05/22/multiple-ssh-login-python-paramiko-with-threading/](https://c0deman.wordpress.com/2014/05/22/multiple-ssh-login-python-paramiko-with-threading/)