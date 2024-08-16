<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

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
        <h2>Tableau de bord de l'administrateur</h2>
        <ul>
            <li><a href="list_student.php">Liste des étudiants archivés et non archivés</a></li>
            <li><a href="admin_list.php">Gérer les administrateurs</a></li>
            <li><a href="inscrire_student.php">inscrire un etudiant</a></li>
            <li><a href="add_admin.php">ajouter un administrateur</a></li>
            <li><a href="select_level.php">selectionner un niveau a afficher</a></li>
            <li><a href="manage_notes.php">gerer les notes des etudiants</a></li>
            <li><a href="logout.php" class="logout">Déconnexion</a></li>
        </ul>
        </div>
    
</body>
</html>
