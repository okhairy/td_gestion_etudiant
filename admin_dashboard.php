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
        <h2 id="welcomeMessage" class="welcome-message">Bienvenue, <?= htmlspecialchars($prenom) ?> <?= htmlspecialchars($nom) ?> !</h2>
        <h3>Tableau de bord de l'administrateur</h3>
        <ul>
            <li><a href="list_students.php">Liste des étudiants </a></li>
            <?php if($_SESSION['role']==1): ?>
            <li><a href="admin_list.php">Gérer les administrateurs</a></li>
            <?php endif; ?>
            <li><a href="list_etudiants_ordre.php">Liste des étudiants par ordre de mérite</a></li>
            <li><a href="logout.php" class="logout">Déconnexion</a></li>
        </ul>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const welcomeMessage = document.getElementById('welcomeMessage');
            
            // Afficher le message
            welcomeMessage.classList.add('show');
            
            // Faire disparaître le message après un délai
            setTimeout(() => {
                welcomeMessage.classList.add('fade-out');
            }, 1000); // Modifier le délai si nécessaire
        });
    </script>
</body>
</html>
