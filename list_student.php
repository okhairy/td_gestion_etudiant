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

    <h2>Liste des étudiants de la licence 1</h2>
    <div class="list-container">
        <table class="L1">
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Module 1</th>
                <th>Module 2</th>
                <th>Module 3</th>
                <th>Module 4</th>
                <th>Moyenne</th>
                <th>Decision du jury</th>
            </tr>
            <?php foreach ($L1_class as $L1_clas): ?>
            <tr>
                <td><?= htmlspecialchars($L1_clas['prenom']) ?></td>
                <td><?= htmlspecialchars($L1_clas['nom']) ?></td>
                <td><?= htmlspecialchars($L1_clas['noteM1']) ?></td>
                <td><?= htmlspecialchars($L1_clas['noteM2']) ?></td>
                <td><?= htmlspecialchars($L1_clas['noteM3']) ?></td>
                <td><?= htmlspecialchars($L1_clas['noteM4']) ?></td>
                <td><?= htmlspecialchars($L1_clas['moyenne']) ?></td>
                <td><?= htmlspecialchars($L1_clas['Admission']) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Liste des étudiants de la licence 2</h2>
    <div class="list-container">
        <table class="L2">
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Module 1</th>
                <th>Module 2</th>
                <th>Module 3</th>
                <th>Module 4</th>
                <th>Moyenne</th>
                <th>Decision du jury</th>
            </tr>
            <?php foreach ($L2_class as $L2_clas): ?>
            <tr>
                <td><?= htmlspecialchars($L2_clas['prenom']) ?></td>
                <td><?= htmlspecialchars($L2_clas['nom']) ?></td>
                <td><?= htmlspecialchars($L2_clas['noteM1']) ?></td>
                <td><?= htmlspecialchars($L2_clas['noteM2']) ?></td>
                <td><?= htmlspecialchars($L2_clas['noteM3']) ?></td>
                <td><?= htmlspecialchars($L2_clas['noteM4']) ?></td>
                <td><?= htmlspecialchars($L2_clas['moyenne']) ?></td>
                <td><?= htmlspecialchars($L2_clas['Admission']) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <h2>Liste des étudiants de la licence 3</h2>
    <div class="list-container">
        <table class="L3">
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Module 1</th>
                <th>Module 2</th>
                <th>Module 3</th>
                <th>Module 4</th>
                <th>Moyenne</th>
                <th>Decision du jury</th>
            </tr>
            <?php foreach ($L3_class as $L3_clas): ?>
            <tr>
                <td><?= htmlspecialchars($L3_clas['prenom']) ?></td>
                <td><?= htmlspecialchars($L3_clas['nom']) ?></td>
                <td><?= htmlspecialchars($L3_clas['noteM1']) ?></td>
                <td><?= htmlspecialchars($L3_clas['noteM2']) ?></td>
                <td><?= htmlspecialchars($L3_clas['noteM3']) ?></td>
                <td><?= htmlspecialchars($L3_clas['noteM4']) ?></td>
                <td><?= htmlspecialchars($L3_clas['moyenne']) ?></td>
                <td><?= htmlspecialchars($L3_clas['Admission']) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Liste des étudiants du Mater 1</h2>
    <div class="list-container">
        <table class="L1">
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Module 1</th>
                <th>Module 2</th>
                <th>Module 3</th>
                <th>Module 4</th>
                <th>Moyenne</th>
                <th>Decision du jury</th>
            </tr>
            <?php foreach ($M1_class as $M1_clas): ?>
            <tr>
                <td><?= htmlspecialchars($M1_clas['prenom']) ?></td>
                <td><?= htmlspecialchars($M1_clas['nom']) ?></td>
                <td><?= htmlspecialchars($M1_clas['noteM1']) ?></td>
                <td><?= htmlspecialchars($M1_clas['noteM2']) ?></td>
                <td><?= htmlspecialchars($M1_clas['noteM3']) ?></td>
                <td><?= htmlspecialchars($M1_clas['noteM4']) ?></td>
                <td><?= htmlspecialchars($M1_clas['moyenne']) ?></td>
                <td><?= htmlspecialchars($M1_clas['Admission']) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Liste des étudiants du Mater 2</h2>
    <div class="list-container">
        <table class="L1">
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Module 1</th>
                <th>Module 2</th>
                <th>Module 3</th>
                <th>Module 4</th>
                <th>Moyenne</th>
                <th>Decision du jury</th>
            </tr>
            <?php foreach ($M1_class as $M2_clas): ?>
            <tr>
                <td><?= htmlspecialchars($M2_clas['prenom']) ?></td>
                <td><?= htmlspecialchars($M2_clas['nom']) ?></td>
                <td><?= htmlspecialchars($M2_clas['noteM1']) ?></td>
                <td><?= htmlspecialchars($M2_clas['noteM2']) ?></td>
                <td><?= htmlspecialchars($M2_clas['noteM3']) ?></td>
                <td><?= htmlspecialchars($M2_clas['noteM4']) ?></td>
                <td><?= htmlspecialchars($M2_clas['moyenne']) ?></td>
                <td><?= htmlspecialchars($M2_clas['Admission']) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>
</body>
</html>
