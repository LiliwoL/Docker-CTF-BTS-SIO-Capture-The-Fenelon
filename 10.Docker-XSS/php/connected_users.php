<?php
require 'config.php';
require 'session.php';
open_session();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer les utilisateurs connectés
$stmt = $pdo->query("
    SELECT u.username, s.ip_address, s.expires_at
    FROM sessions s
    JOIN users u ON s.user_id = u.id
    WHERE s.expires_at > NOW()
");
$connectedUsers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Utilisateurs connectés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Utilisateurs actuellement connectés</h1>
<table border="1">
    <tr>
        <th>Utilisateur</th>
        <th>Adresse IP</th>
        <th>Session expire à</th>
    </tr>
    <?php foreach ($connectedUsers as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['ip_address']) ?></td>
            <td><?= htmlspecialchars($user['expires_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<p><a href="private.php">Retour à l'espace privé</a></p>
<p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>
