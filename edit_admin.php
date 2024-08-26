<?php
include 'functions.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Vérifier si l'ID de l'administrateur à modifier est spécifié
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer et exécuter la requête pour obtenir les informations actuelles de l'administrateur
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $admin = $stmt->fetch();

    // Vérifier si l'administrateur existe
    if (!$admin) {
        die("Administrateur non trouvé");
    }

    // Traiter le formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        
        // Validation stricte de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
            !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $error = "L'adresse email n'est pas valide.";
        } else {
            // Vérifier si l'email est unique
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM administrateurs WHERE email = :email AND id != :id");
            $stmt->execute(['email' => $email, 'id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                $error = "L'email est déjà utilisé par un autre administrateur.";
            } else {
                // Mettre à jour les informations de l'administrateur
                $stmt = $pdo->prepare("UPDATE administrateurs SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id");
                $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'id' => $id
                ]);

                header('Location: admin_list.php');
                exit();
            }
        }
    }
} else {
    die("ID non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Administrateur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Modifier Administrateur</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form id="edit" action="edit_admin.php?id=<?= htmlspecialchars($id) ?>" method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($admin['nom']) ?>" required>
        
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($admin['prenom']) ?>" required>
        
        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
        
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
