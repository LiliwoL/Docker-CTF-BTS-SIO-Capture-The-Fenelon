<?php require 'session.php'; open_session(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Espace Public</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Bienvenue sur l'espace public</h1>
        <p><a href="login.php">Se connecter</a></p>
        <p><a href="vuln.php">Page vulnérable à XSS</a></p>
    </body>
</html>
