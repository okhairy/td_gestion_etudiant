<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$etudiant_id = $_GET['id'];
$etudiant = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
$etudiant->execute([':id' => $etudiant_id]);
$etudiant = $etudiant->fetch(PDO::FETCH_ASSOC);

$absences = $pdo->prepare("
    SELECT * FROM absences
    WHERE etudiant_id = :etudiant_id
");
$absences->execute([':etudiant_id' => $etudiant_id]);
$absences = $absences->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'absences</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Rapport d'absences pour <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Motif</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($absences as $absence): ?>
                <tr>
                    <td><?= htmlspecialchars($absence['date_absence']) ?></td>
                    <td><?= htmlspecialchars($absence['motif']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='admin_dashboard.php'">Retour au tableau de bord</button>
</body>
</html>
