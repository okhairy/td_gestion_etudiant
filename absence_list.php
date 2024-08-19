<?php
include 'db.php';
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer les informations sur les absences
$etudiants = $pdo->query("SELECT nom, prenom, date_absence, motif, nombre_absences FROM etudiants")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des absences</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Liste des absences</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Dernière date d'absence</th>
                <th>Dernier motif</th>
                <th>Nombre total d'absences</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiants as $etudiant): ?>
                <tr>
                    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                    <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                    <td><?= htmlspecialchars($etudiant['date_absence']) ?></td>
                    <td><?= htmlspecialchars($etudiant['motif']) ?></td>
                    <td><?= htmlspecialchars($etudiant['nombre_absences']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
</body>
</html>
