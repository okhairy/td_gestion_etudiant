<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer le prénom et le nom de l'administrateur depuis la session
$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="dashboard">
        <h2>Bienvenue, <?= htmlspecialchars($prenom) ?> <?= htmlspecialchars($nom) ?> !</h2>
        <h3>Tableau de bord de l'administrateur</h3>
        <ul>
            <li><a href="list_student.php">Liste des étudiants archivés et non archivés</a></li>
            <li><a href="admin_list.php">Gérer les administrateurs</a></li>
            <li><a href="inscrire_student.php">Inscrire un étudiant</a></li>
            <li><a href="add_admin.php">Ajouter un administrateur</a></li>
            <li><a href="list_etudiants.php">Lister par ordre de mérite</a></li>
            <li><a href="logout.php" class="logout">Déconnexion</a></li>
        </ul>
    </div>
    
</body>
</html>
