<?php
include 'functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();

    if ($admin && verifyPassword($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['prenom'] = $admin['prenom'];
        $_SESSION['nom'] = $admin['nom'];
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Connexion Administrateur</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form id="login" action="index.php" method="POST">
        <label>Email :</label>
        <input type="email" name="email" required>

        <div>
            <label>Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
           
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <script src="script.js"></script>
</body>
</html>
