<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}
// Si le formulaire d'ajout d'étudiant est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $niveau = $_POST['niveau'];

    // Génération d'un matricule unique
    $matricule = strtoupper(substr($nom, 0, 2)) . date('Y') . rand(100, 999);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO students (nom, prenom, date_naissance, email, telephone, niveau, matricule) VALUES (:nom, :prenom, :date_naissance, :email, :telephone, :niveau, :matricule)");
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'date_naissance' => $date_naissance,
        'email' => $email,
        'telephone' => $telephone,
        'niveau' => $niveau,
        'matricule' => $matricule
    ]);

    // Redirection pour éviter la soumission multiple du formulaire
    header('Location: list_student.php');
    exit();
}

// Récupérer les étudiants non archivés
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE archived = 0");
$stmt->execute();
$students_active = $stmt->fetchAll();

// Récupérer les étudiants archivés
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE archived = 1");
$stmt->execute();
$students_archived = $stmt->fetchAll();
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
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Matricule</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students_active) > 0): ?>
                <?php foreach ($students_active as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['nom']) ?></td>
                        <td><?= htmlspecialchars($student['prenom']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><?= htmlspecialchars($student['telephone']) ?></td>
                        <td><?= htmlspecialchars($student['matricule']) ?></td>
                        <td>
                            <a href="edit_student.php?id=<?= $student['id'] ?>">Modifier</a> |
                            <a href="archive_student.php?id=<?= $student['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir archiver cet étudiant ?')">Archiver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Aucun étudiant non archivé trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Liste des étudiants archivés</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Matricule</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students_archived) > 0): ?>
                <?php foreach ($students_archived as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['nom']) ?></td>
                        <td><?= htmlspecialchars($student['prenom']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><?= htmlspecialchars($student['telephone']) ?></td>
                        <td><?= htmlspecialchars($student['matricule']) ?></td>
                        <td>
                            <a href="edit_student.php?id=<?= $student['id'] ?>">Modifier</a> |
                            <a href="unarchive_student.php?id=<?= $student['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir désarchiver cet étudiant ?')">Désarchiver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Aucun étudiant archivé trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
