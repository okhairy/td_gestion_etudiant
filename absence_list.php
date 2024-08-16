<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$absences = $pdo->query("
    SELECT etudiants.prenom, etudiants.nom, absences.date_absence, absences.motif 
    FROM absences
    JOIN etudiants ON absences.etudiant_id = etudiants.id
")->fetchAll(PDO::FETCH_ASSOC);
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
                <th>Prénom</th>
                <th>Nom</th>
                <th>Date</th>
                <th>Motif</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($absences as $absence): ?>
                <tr>
                    <td><?= htmlspecialchars($absence['prenom']) ?></td>
                    <td><?= htmlspecialchars($absence['nom']) ?></td>
                    <td><?= htmlspecialchars($absence['date_absence']) ?></td>
                    <td><?= htmlspecialchars($absence['motif']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='admin_dashboard.php'">Retour au tableau de bord</button>
</body>
</html>
