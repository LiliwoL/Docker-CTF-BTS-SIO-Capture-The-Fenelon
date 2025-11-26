<?php
require 'session.php';
open_session();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Page Vulnérable</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Page Vulnérable à XSS</h1>

        <?php if (isset($_GET['message'])): ?>
            <!-- Vulnérabilité XSS intentionnelle -->
            <p><?= $_GET['message'] ?></p>

            <?
                // Sécurisation recommandée
                /* <p><?= htmlspecialchars($_GET['message']) ?></p> */
            ?>
        <?php endif; ?>

        <form method="get">
            <label>Entrez un message: <input type="text" name="message"></label>
            <button type="submit">Envoyer</button>
        </form>

        <p><a href="private.php">Retour à l'espace privé</a></p>
        <p><a href="logout.php">Se déconnecter</a></p>
    </body>
</html>
