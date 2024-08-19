<?php
include 'functions.php';
session_start();

// Afficher les erreurs PHP pour déboguer
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Traitement du formulaire d'ajout d'administrateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    }

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirm_password) || !is_numeric($role)) {
        $error = "Tous les champs doivent être remplis et le rôle doit être un nombre valide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Vérifie si l'email a un format valide
        $error = "Le format de l'email est invalide.";
    } elseif ($password !== $confirm_password) {
        // Vérifie si les mots de passe correspondent
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifie si l'email est déjà utilisé
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM administrateurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = "L'email est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, prenom, email, password, role) VALUES (:nom, :prenom, :email, :password, :role)");
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => $hashed_password,
                'role' => $role
            ]);

            // Redirection après l'ajout
            header('Location: admin_list.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un administrateur</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <h2>Ajouter un administrateur</h2>
    <div class="form-container">
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="add_admin.php" method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-field">
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                <span id="togglePassword" class="toggle-password">👁️‍🗨️</span>
            </div>
            <div class="password-field">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                <span id="toggleConfirmPassword" class="toggle-password">👁️‍🗨️</span>
            </div>
            <input type="number" name="role" placeholder="Rôle (entier)" required min="0">
            <button type="submit">Ajouter</button>
        </form>
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>

    <script src="script.js"></script>
        
   
</body>
</html>
