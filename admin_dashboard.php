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
    <style>
        /* Style de base */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Conteneur principal */
        .container {
            width: 90%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        /* Titre */
        h2 {
            color: #2980b9;
            margin-bottom: 20px;
        }

        /* Liste des options */
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            margin: 15px 0;
        }

        ul li a {
            display: block;
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        ul li a:hover {
            background-color: #2980b9;
        }

        /* Bouton de déconnexion */
        ul li a.logout {
            background-color: #e74c3c;
        }

        ul li a.logout:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tableau de bord de l'administrateur</h2>
        <ul>
            <li><a href="list_student.php">Liste des étudiants archivés et non archivés</a></li>
            <li><a href="admin_list.php">Gérer les administrateurs</a></li>
            <li><a href="edit_student.php">Modifier un étudiant</a></li>
            <li><a href="logout.php" class="logout">Déconnexion</a></li>
        </ul>
    </div>
</body>
</html>
