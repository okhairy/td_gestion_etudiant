<?php
include 'db.php';
// header('Location: admin_dashboard.php');
//         exit();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $note1 = floatval($_POST['note1']);
    $note2 = floatval($_POST['note2']);
    $note3 = floatval($_POST['note3']);
    $note4 = floatval($_POST['note4']);

    // Vérifier si l'ID existe dans la base de données
    $sql = "SELECT id FROM etudiants WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $existingId = $stmt->fetchColumn();

    if ($existingId) {
        // Calcul de la moyenne
        $moyenne = ($note1 + $note2 + $note3 + $note4) / 4;

        // Déterminer l'état d'admission
        $admission = $moyenne >= 10 ? 'admis' : 'recale';

        // Mettre à jour les notes et l'état d'admission
        $sql = "UPDATE etudiants SET noteM1 = :note1, noteM2 = :note2, noteM3 = :note3, noteM4 = :note4, Admission = :admission WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':note1' => $note1,
            ':note2' => $note2,
            ':note3' => $note3,
            ':note4' => $note4,
            ':admission' => $admission,
            ':id' => $id
        ]);

        echo "Les notes et l'état d'admission ont été mis à jour avec succès pour l'étudiant avec ID $id.";
    } else {
        echo "L'étudiant avec l'ID $id n'existe pas dans la base de données.";
    }
    
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'enregistrement des notes</title>
    <link rel="stylesheet" href="inscrire.css">
</head>
<body>
    <div class="form-container">
    <h2>Entrer les notes des modules</h2>
    <form method="POST" action="">
        Id de l'étudiant: <input type="text" name="id" required><br><br>
        Note Module 1: <input type="number" step="0.01" name="note1" min= "0" max="20" required><br><br>
        Note Module 2: <input type="number" step="0.01" name="note2" min= "0" max="20" required><br><br>
        Note Module 3: <input type="number" step="0.01" name="note3" min= "0" max="20" required><br><br>
        Note Module 4: <input type="number" step="0.01" name="note4" min= "0" max="20" required><br><br>
        <button type="submit">Soumettre</button>
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </form>
    </div>
</body>
</html>
