<?php
include 'db.php';

// Récupérer le niveau à afficher depuis l'URL, avec une valeur par défaut
$niveaux = ['L1', 'L2', 'L3', 'M1', 'M2'];
$niveau = isset($_GET['niveau']) && in_array($_GET['niveau'], $niveaux) ? $_GET['niveau'] : 'L1';

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
    } elseif ($etudiant['Admission'] === 'recale') {
        $recaleCount++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants par niveau</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Liste des étudiants de <?= htmlspecialchars($niveau) ?></h2>

    <div class="button-group">
        <?php foreach ($niveaux as $niv): ?>
            <a href="?niveau=<?= $niv ?>" class="btn-level <?= $niveau === $niv ? 'active' : '' ?>">Niveau <?= $niv ?></a>
        <?php endforeach; ?>
    </div>

    <div class="list-container">
        <table>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Module 1</th>
                    <th>Module 2</th>
                    <th>Module 3</th>
                    <th>Module 4</th>
                    <th>Moyenne</th>
                    <th>Décision du jury</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant): ?>
                    <tr>
                        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['noteM1']) ?></td>
                        <td><?= htmlspecialchars($etudiant['noteM2']) ?></td>
                        <td><?= htmlspecialchars($etudiant['noteM3']) ?></td>
                        <td><?= htmlspecialchars($etudiant['noteM4']) ?></td>
                        <td><?= htmlspecialchars($etudiant['moyenne']) ?></td>
                        <td><?= htmlspecialchars($etudiant['Admission']) ?></td>
                        <td><a href="add_note.php?id=<?= $etudiant['id']?>">ajouter notes</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p>Nombre total d'étudiants : <?= $totalCount ?></p>
    <p>Nombre d'étudiants admis : <?= $admisCount ?></p>
    <p>Nombre d'étudiants recalés : <?= $recaleCount ?></p>

    <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
</body>
</html>
