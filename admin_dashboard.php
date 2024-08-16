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
            <li><a href="add_note.php">ajouter des notes</a></li>
            <li><a href="list_etudiants.php" >lister par ordre de merite</a></li>
            <li><a href="add_absence.php">Marquer une absence</a></li>
            <li><a href="absence_list.php">Voir les absences</a></li>
            <li><a href="logout.php" class="logout">Déconnexion</a></li>
        </ul>
        </div>
    
</body>
</html>
