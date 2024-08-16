<?php
include 'db.php';

// Récupérer le niveau à afficher depuis l'URL
$niveau = isset($_GET['niveau']) ? $_GET['niveau'] : 'L1';

// Sélectionner les étudiants du niveau donné et trier par moyenne
$sql = "SELECT * FROM etudiants WHERE niveau = :niveau ORDER BY moyenne DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':niveau' => $niveau]);
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compter le nombre total d'étudiants, d'admis et de recalés
$admisCount = 0;
$recaleCount = 0;
$totalCount = count($etudiants);

foreach ($etudiants as $etudiant) {
    if ($etudiant['Admission'] === 'admis') {
        $admisCount++;
    } elseif ($etudiant['Admission'] === 'recalé') {
        $recaleCount++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants par ordre de mérite</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Liste des étudiants de <?= htmlspecialchars($niveau) ?> par ordre de mérite</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Moyenne</th>
                <th>Statut d'admission</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiants as $etudiant): ?>
                <tr>
                    <td><?= htmlspecialchars($etudiant['id']) ?></td>
                    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                    <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                    <td><?= htmlspecialchars($etudiant['moyenne']) ?></td>
                    <td><?= htmlspecialchars($etudiant['Admission']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Nombre total d'étudiants : <?= $totalCount ?></p>
    <p>Nombre d'étudiants admis : <?= $admisCount ?></p>
    <p>Nombre d'étudiants recalés : <?= $recaleCount ?></p>

    <button type="button" onclick="window.location.href='admin_dashboard.php'">Retour au tableau de bord</button>
</body>
</html>
