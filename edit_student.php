<?php
include 'functions.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $student = $stmt->fetch();

    if (!$student) {
        die("Étudiant non trouvé");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $niveau = $_POST['niveau'];

        $stmt = $pdo->prepare("UPDATE etudiants SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, niveau = :niveau WHERE id = :id");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'niveau' => $niveau,
            'id' => $id
        ]);

        header('Location: student_list.php');
        exit();
    }
} else {
    die("ID non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Modifier étudiant</h2>
    <form action="edit_student.php?id=<?= $id ?>" method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($student['nom']) ?>" required>
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($student['prenom']) ?>" required>
        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($student['telephone']) ?>" required>
        <label>Niveau :</label>
        <select name="niveau" required>
            <option value="L1" <?= $student['niveau'] == 'L1' ? 'selected' : '' ?>>L1</option>
            <option value="L2" <?= $student['niveau'] == 'L2' ? 'selected' : '' ?>>L2</option>
            <option value="L3" <?= $student['niveau'] == 'L3' ? 'selected' : '' ?>>L3</option>
            <option value="M1" <?= $student['niveau'] == 'M1' ? 'selected' : '' ?>>M1</option>
            <option value="M2" <?= $student['niveau'] == 'M2' ? 'selected' : '' ?>>M2</option>
        </select>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
