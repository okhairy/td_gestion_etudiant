<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php'); // Redirige vers la page d'authentification si l'utilisateur n'est pas connecté
    exit();
}

// Initialiser les variables pour les champs du formulaire
$nom = $prenom = $email = $role = '';
$error = '';

// Traitement du formulaire d'ajout d'administrateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validation des données
    if (empty($nom) || empty($prenom)) {
        $error = "Le nom et le prénom ne doivent pas être vides ou contenir uniquement des espaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        // Validation stricte de l'email
        $error = "Le format de l'email est invalide.";
    } elseif (empty($password) || empty($confirm_password) || !is_numeric($role)) {
        $error = "Tous les champs doivent être remplis et le rôle doit être un nombre valide.";
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
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="add_admin.php" method="POST">
            <input type="text" name="nom" placeholder="Nom" value="<?= htmlspecialchars($nom) ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?= htmlspecialchars($prenom) ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
            <div class="password-field">
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                <span id="togglePassword" class="toggle-password">👁️‍🗨️</span>
            </div>
            <div class="password-field">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                <span id="toggleConfirmPassword" class="toggle-password">👁️‍🗨️</span>
            </div>
            <input type="number" name="role" placeholder="Rôle (entier)" value="<?= htmlspecialchars($role) ?>" required min="0">
            <button type="submit">Ajouter</button>
        </form>
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>

    <script src="script.js"></script>
</body>
</html>
