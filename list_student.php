<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Traitement de l'archivage et du désarchivage
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'archive') {
        $stmt = $pdo->prepare("UPDATE etudiants SET archived = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif ($action == 'unarchive') {
        $stmt = $pdo->prepare("UPDATE etudiants SET archived = 0 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // Redirection après l'action
    header('Location: list_student.php');
    exit();
}

// Récupérer la liste des étudiants non archivés
$students = $pdo->query("SELECT * FROM etudiants WHERE archived = 0")->fetchAll();

// Récupérer la liste des étudiants archivés
$archived_students = $pdo->query("SELECT * FROM etudiants WHERE archived = 1")->fetchAll();

$L1_class = $pdo->query("SELECT *, (COALESCE(noteM1, 0) + COALESCE(noteM2, 0) + COALESCE(noteM3, 0) + COALESCE(noteM4, 0)) / 4 as moyenne FROM etudiants WHERE niveau= 'L1' AND archived = 0 ORDER BY moyenne DESC")->fetchAll();
$L2_class = $pdo->query("SELECT *, (COALESCE(noteM1, 0) + COALESCE(noteM2, 0) + COALESCE(noteM3, 0) + COALESCE(noteM4, 0)) / 4 as moyenne FROM etudiants WHERE niveau= 'L2' AND archived = 0 ORDER BY moyenne DESC")->fetchAll();
$L3_class = $pdo->query("SELECT *, (COALESCE(noteM1, 0) + COALESCE(noteM2, 0) + COALESCE(noteM3, 0) + COALESCE(noteM4, 0)) / 4 as moyenne FROM etudiants WHERE niveau= 'L3' AND archived = 0 ORDER BY moyenne DESC")->fetchAll();
$M1_class = $pdo->query("SELECT *, (COALESCE(noteM1, 0) + COALESCE(noteM2, 0) + COALESCE(noteM3, 0) + COALESCE(noteM4, 0)) / 4 as moyenne FROM etudiants WHERE niveau= 'M1' AND archived = 0 ORDER BY moyenne DESC")->fetchAll();
$M2_class = $pdo->query("SELECT *, (COALESCE(noteM1, 0) + COALESCE(noteM2, 0) + COALESCE(noteM3, 0) + COALESCE(noteM4, 0)) / 4 as moyenne FROM etudiants WHERE niveau= 'M2' AND archived = 0 ORDER BY moyenne DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Liste des étudiants non archivés</h2>
    <div class="list-container">
        <table class="non-archived">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Matricule</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['nom']) ?></td>
                <td><?= htmlspecialchars($student['prenom']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['telephone']) ?></td>
                <td><?= htmlspecialchars($student['matricule']) ?></td>
                <td>
                    <!-- Liens pour modifier et archiver l'étudiant -->
                    <a href="edit_student.php?id=<?= $student['id'] ?>">Modifier</a> |
                    <a href="list_student.php?action=archive&id=<?= $student['id'] ?>">Archiver</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Liste des étudiants archivés</h2>
    <div class="list-container">
        <table class="archived">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Matricule</th>
                <th>Action</th>
            </tr>
            <?php foreach ($archived_students as $archived_student): ?>
            <tr>
                <td><?= htmlspecialchars($archived_student['nom']) ?></td>
                <td><?= htmlspecialchars($archived_student['prenom']) ?></td>
                <td><?= htmlspecialchars($archived_student['email']) ?></td>
                <td><?= htmlspecialchars($archived_student['telephone']) ?></td>
                <td><?= htmlspecialchars($archived_student['matricule']) ?></td>
                <td>
                    <!-- Lien pour désarchiver l'étudiant -->
                    <a class="unarchive" href="list_student.php?action=unarchive&id=<?= $archived_student['id'] ?>">Désarchiver</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
     </div>

    
            
       
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>
</body>
</html>
