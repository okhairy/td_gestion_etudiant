<?php
include 'functions.php';
session_start();

// Afficher les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer l'ID de l'étudiant
$student_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$student_id) {
    die('ID d\'étudiant invalide.');
}

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

    // Déterminer le statut d'admission
    $Admission = 'en cours'; // Valeur par défaut
    if ($noteM1 !== null && $noteM2 !== null && $noteM3 !== null && $noteM4 !== null) {
        $moyenne = ($noteM1 + $noteM2 + $noteM3 + $noteM4) / 4;
        $Admission = $moyenne >= 10 ? 'admis' : 'recale';
    }

    // Mettre à jour les notes et le statut d'admission dans la base de données
    $stmt = $pdo->prepare("UPDATE etudiants SET noteM1 = :noteM1, noteM2 = :noteM2, noteM3 = :noteM3, noteM4 = :noteM4, Admission = :Admission WHERE id = :id");
    $stmt->execute([
        'noteM1' => $noteM1,
        'noteM2' => $noteM2,
        'noteM3' => $noteM3,
        'noteM4' => $noteM4,
        'Admission' => $Admission,
        'id' => $student_id
    ]);

    // Redirection après la mise à jour
    header('Location: list_etudiants_ordre.php');
    exit();
}

// Récupérer les informations actuelles de l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->execute(['id' => $student_id]);
$student = $stmt->fetch();

if (!$student) {
    die('Étudiant non trouvé.');
}

// Définir les notes à zéro si elles ne sont pas définies
$noteM1 = $student['noteM1'] !== null ? $student['noteM1'] : 0;
$noteM2 = $student['noteM2'] !== null ? $student['noteM2'] : 0;
$noteM3 = $student['noteM3'] !== null ? $student['noteM3'] : 0;
$noteM4 = $student['noteM4'] !== null ? $student['noteM4'] : 0;

// Vérifier si toutes les notes sont remplies
$allNotesFilled = ($noteM1 !== '' && $noteM2 !== '' && $noteM3 !== '' && $noteM4 !== '');
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
    
    <form class="form-container" method="post" action="">
        <label for="noteM1">Note Module 1 :</label>
        <input type="number" id="noteM1" name="noteM1" step="0.01" min="0" max="20" value="<?= htmlspecialchars($noteM1) ?>"><br>
        
        <label for="noteM2">Note Module 2 :</label>
        <input type="number" id="noteM2" name="noteM2" step="0.01" min="0" max="20" value="<?= htmlspecialchars($noteM2) ?>"><br>
        
        <label for="noteM3">Note Module 3 :</label>
        <input type="number" id="noteM3" name="noteM3" step="0.01" min="0" max="20" value="<?= htmlspecialchars($noteM3) ?>"><br>
        
        <label for="noteM4">Note Module 4 :</label>
        <input type="number" id="noteM4" name="noteM4" step="0.01" min="0" max="20" value="<?= htmlspecialchars($noteM4) ?>"><br>
        
        <button type="submit"><?= $allNotesFilled ? 'Modifier' : 'Ajouter les notes' ?></button>
        <button type="button" onclick="window.location.href='list_etudiants_ordre.php'" class="btn-back">Retour à la liste des étudiants</button>
    </form>

</body>
</html>
