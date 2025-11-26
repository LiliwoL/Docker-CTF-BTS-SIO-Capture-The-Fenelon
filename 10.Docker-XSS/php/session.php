<?php
require 'config.php';

function open_session()
{
    global $pdo;
    if (!session_id()) {
        session_start();
    }

    $sessionId = session_id();
    $userId = $_SESSION['user_id'] ?? null;
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    if ($userId)
    {
        $stmt = $pdo->prepare("SELECT * FROM sessions WHERE session_id = ?");
        $stmt->execute([$sessionId]);

        if (!$stmt->fetch())
        {
            $stmt = $pdo->prepare("INSERT INTO sessions (session_id, user_id, ip_address, expires_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
            $stmt->execute([$sessionId, $userId, $ipAddress]);
        } else {
            $stmt = $pdo->prepare("UPDATE sessions SET expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR), ip_address = ? WHERE session_id = ?");
            $stmt->execute([$ipAddress, $sessionId]);
        }
    }
}

function close_session()
{
    global $pdo;
    $sessionId = session_id();
    $pdo->prepare("DELETE FROM sessions WHERE session_id = ?")
        ->execute([$sessionId]);
    session_destroy();
}
?>
