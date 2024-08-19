<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer l'ID de l'étudiant
$student_id = $_GET['id'];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les notes saisies dans le formulaire, ou NULL si non saisies
    $noteM1 = isset($_POST['noteM1']) && $_POST['noteM1'] !== '' ? $_POST['noteM1'] : null;
    $noteM2 = isset($_POST['noteM2']) && $_POST['noteM2'] !== '' ? $_POST['noteM2'] : null;
    $noteM3 = isset($_POST['noteM3']) && $_POST['noteM3'] !== '' ? $_POST['noteM3'] : null;
    $noteM4 = isset($_POST['noteM4']) && $_POST['noteM4'] !== '' ? $_POST['noteM4'] : null;

    // Validation des valeurs : elles doivent être entre 0 et 20 si elles sont renseignées
    if (($noteM1 !== null && ($noteM1 < 0 || $noteM1 > 20)) ||
        ($noteM2 !== null && ($noteM2 < 0 || $noteM2 > 20)) ||
        ($noteM3 !== null && ($noteM3 < 0 || $noteM3 > 20)) ||
        ($noteM4 !== null && ($noteM4 < 0 || $noteM4 > 20))) {
        die('Les notes doivent être comprises entre 0 et 20.');
    }

    // Mettre à jour les notes dans la base de données
    $stmt = $pdo->prepare("UPDATE etudiants SET noteM1 = :noteM1, noteM2 = :noteM2, noteM3 = :noteM3, noteM4 = :noteM4 WHERE id = :id");
    $stmt->execute([
        'noteM1' => $noteM1,
        'noteM2' => $noteM2,
        'noteM3' => $noteM3,
        'noteM4' => $noteM4,
        'id' => $student_id
    ]);

    // Redirection après la mise à jour
    header('Location: list_student.php');
    exit();
}

// Récupérer les informations actuelles de l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->execute(['id' => $student_id]);
$student = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Donner des notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Donner des notes à <?= htmlspecialchars($student['nom']) ?> <?= htmlspecialchars($student['prenom']) ?></h2>
    
    <form  class="form-container"  method="post" action="" >
    <label for="noteM1">Note Module 1 :</label>
    <input type="number" id="noteM1" name="noteM1" step="0.01" min="0" max="20" value="<?= htmlspecialchars($student['noteM1']) ?>"><br>
    
    <label for="noteM2">Note Module 2 :</label>
    <input type="number" id="noteM2" name="noteM2" step="0.01" min="0" max="20" value="<?= htmlspecialchars($student['noteM2']) ?>"><br>
    
    <label for="noteM3">Note Module 3 :</label>
    <input type="number" id="noteM3" name="noteM3" step="0.01" min="0" max="20" value="<?= htmlspecialchars($student['noteM3']) ?>"><br>
    
    <label for="noteM4">Note Module 4 :</label>
    <input type="number" id="noteM4" name="noteM4" step="0.01" min="0" max="20" value="<?= htmlspecialchars($student['noteM4']) ?>"><br>
    
    <button type="submit">Enregistrer les notes</button>

    <button onclick="window.location.href='list_student.php'" class="btn-back">Retour à la liste des étudiants</button>
    </form>


    

</body>
</html>
