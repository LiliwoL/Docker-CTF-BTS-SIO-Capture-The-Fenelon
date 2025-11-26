<?php
require 'config.php';
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password']))
    {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        open_session();

        // Ajout d'un cookie pour un durée de 60 secondes
        setcookie("user_name", $username, time()+ 60,'/');

        header("Location: private.php");
        exit;
    } else {
        $error = "Identifiants invalides.";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Connexion</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <h1>Connexion</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Utilisateur: <input type="text" name="username" required></label><br>
        <label>Mot de passe: <input type="password" name="password" required></label><br>
        <button type="submit">Se connecter</button>
    </form>
    <p><a href="index.php">Retour à l'accueil</a></p>
    </body>
</html>
