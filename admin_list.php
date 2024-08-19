<?php
include 'functions.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM administrateurs");
$stmt->execute();
$admins = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des administrateurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Liste des administrateurs</h2>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($admins as $admin) : ?>
            <tr>
                <td><?= htmlspecialchars($admin['nom']) ?></td>
                <td><?= htmlspecialchars($admin['prenom']) ?></td>
                <td><?= htmlspecialchars($admin['email']) ?></td>
                <td>
<<<<<<< HEAD
                    <a href="edit_admin.php?id=<?= $admin['id'] ?> " class="btn-modifier">Modifier</a>
                    <a href="delete.php?id=<?= $admin['id'] ?>"class="btn-supprimer">Supprimer</a>
=======
                    <a href="edit_admin.php?id=<?= $admin['id'] ?>" class="btn-modifier">Modifier</a>
                    <a href="delete.php?id=<?= $admin['id'] ?>&type=admin" 
             onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');" class="btn-supprimer">Supprimer</a>

>>>>>>> 70eb2a0a858fa3a31671c74a2f0cda2aaf873238
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <ul>
    <li><a href="add_admin.php">ajouter un administrateur</a></li>
    </ul>
    
    <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
</body>
</html>
