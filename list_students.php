<?php
include 'db.php';

// Récupérer l'action à partir de l'URL
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $student_id = $_GET['id'];

    if ($action === 'archive') {
        // Archiver l'étudiant
        $stmt = $pdo->prepare("UPDATE etudiants SET archived = 1 WHERE id = :id");
        $stmt->execute(['id' => $student_id]);
    } elseif ($action === 'unarchive') {
        // Désarchiver l'étudiant
        $stmt = $pdo->prepare("UPDATE etudiants SET archived = 0 WHERE id = :id");
        $stmt->execute(['id' => $student_id]);
    }

    // Rediriger vers la page actuelle pour rafraîchir la liste
    header("Location: list_students.php?type=" . ($_GET['type'] ?? 'non_archived'));
    exit();
}

// Récupérer le type d'affichage à partir de l'URL, avec une valeur par défaut
$types = ['non_archived', 'archived'];
$type = isset($_GET['type']) && in_array($_GET['type'], $types) ? $_GET['type'] : 'non_archived';

// Préparer les requêtes selon le type sélectionné
if ($type === 'non_archived') {
    $query = "SELECT * FROM etudiants WHERE archived = 0";
} elseif ($type === 'archived') {
    $query = "SELECT * FROM etudiants WHERE archived = 1";
}

$stmt = $pdo->query($query);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
$students_count = count($students);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-bar">
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>
    <h2>Liste des étudiants</h2>

    <div class="button-group">
        <div class="dropdown">
            <button class="dropbtn"><?= $type === 'non_archived' ? 'Étudiants non archivés' : 'Étudiants archivés' ?></button>
            <div class="dropdown-content">
                <a href="list_students.php?type=non_archived">Étudiants non archivés</a>
                <a href="list_students.php?type=archived">Étudiants archivés</a>
            </div>
        </div>
    </div>

    <?php if ($type === 'non_archived'): ?>
    <div class="button-group">
        <a href="inscrire_student.php" class="btn">Ajouter un étudiant</a>
    </div>
    <?php endif; ?>

    <div class="list-container">
        <p>Nombre d'étudiants : <?= $students_count ?></p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Matricule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                    <td><?= htmlspecialchars($student['nom']) ?></td>
                    <td><?= htmlspecialchars($student['prenom']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['telephone']) ?></td>
                    <td><?= htmlspecialchars($student['matricule']) ?></td>
                    <td>
                        <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn-modifier">Modifier</a>
                        <?php if ($type === 'non_archived'): ?>
                            <a href="list_students.php?action=archive&id=<?= $student['id'] ?>" class="btn-archiver" onclick="return confirm('Êtes-vous sûr de vouloir archiver cet étudiant ?');">Archiver</a>
                        <?php elseif ($type === 'archived'): ?>
                            <a href="list_students.php?action=unarchive&id=<?= $student['id'] ?>" class="btn-unarchive" onclick="return confirm('Êtes-vous sûr de vouloir désarchiver cet étudiant ?');">Désarchiver</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
