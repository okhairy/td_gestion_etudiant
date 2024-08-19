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

// Traitement de la recherche
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Préparer les requêtes avec recherche
$query = "SELECT * FROM etudiants WHERE archived = 0 AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$students = $stmt->fetchAll();
$students_count = count($students); // Nombre d'étudiants non archivés

$query_archived = "SELECT * FROM etudiants WHERE archived = 1 AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
$stmt_archived = $pdo->prepare($query_archived);
$stmt_archived->execute(['search' => "%$search%"]);
$archived_students = $stmt_archived->fetchAll();
$archived_students_count = count($archived_students); // Nombre d'étudiants archivés

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
    <h2>Recherche d'étudiants</h2>
    <form method="get" action="" class="search-form">
        <input type="text" name="search" placeholder="Rechercher par nom, prénom ou email" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit">Rechercher</button>
    </form>

    <h2>Liste des étudiants non archivés</h2>
    <p>Nombre d'étudiants non archivés : <?= $students_count ?></p>
    <div class="list-container">
        <table class="non-archived">
            <tr>
                <th>ID</th>
               <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Matricule</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['id']) ?></td>
                <td><?= htmlspecialchars($student['nom']) ?></td>
                <td><?= htmlspecialchars($student['prenom']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['telephone']) ?></td>
                <td><?= htmlspecialchars($student['matricule']) ?></td>
                <td>
                    <!-- Lien pour modifier -->
                    <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn-modifier">Modifier</a> |
                    <!-- Lien pour archiver -->
                    <a href="list_student.php?action=archive&id=<?= $student['id'] ?>"   class="btn-archiver"
                     onclick="return confirm('Êtes-vous sûr de vouloir archiver cet étudiant ?');"> Archiver </a>
  
  
                    <a href="add_note.php?id=<?= $student['id'] ?>" class="btn-notes">Donner des notes</a>
                   
                    <a href="add_absence.php?id=<?= $student['id'] ?>" class="btn-absence">Marquer une absence</a>
        

            
                    
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Liste des étudiants archivés</h2>
    <p>Nombre d'étudiants archivés : <?= $archived_students_count ?></p>
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
                    <a class="btn-unarchive" href="list_student.php?action=unarchive&id=<?= $archived_student['id'] ?>">Désarchiver</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
</body>
</html>
