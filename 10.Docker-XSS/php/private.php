<?php
require 'session.php';
open_session();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Espace Privé</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Espace Privé</h1>

        <p>Bienvenue, utilisateur #<?= htmlspecialchars($_SESSION['user_id']) ?>!</p>

        <p>Votre cookie de session: <code><?= htmlspecialchars(session_id()) ?></code></p>

        <p>Votre cookie de username (si il est encore valide): <code><?= htmlspecialchars($_COOKIE['user_name'])
                ?></code></p>

        <p><a href="connected_users.php">Voir les utilisateurs connectés</a></p>
        <p><a href="vuln.php">Page vulnérable à XSS</a></p>
        <p><a href="logout.php">Se déconnecter</a></p>
    </body>
</html>
