<?php
include 'db.php';
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $date_absence = $_POST['date_absence'];
    $motif = $_POST['motif'];

    try {
        // Mettre à jour la table `etudiants` avec la nouvelle absence
        $stmt = $pdo->prepare("UPDATE etudiants 
                               SET date_absence = :date_absence, 
                                   motif = :motif, 
                                   nombre_absences = COALESCE(nombre_absences, 0) + 1 
                               WHERE id = :etudiant_id");
        $stmt->execute([
            'date_absence' => $date_absence,
            'motif' => $motif,
            'etudiant_id' => $etudiant_id,
        ]);

        // Rediriger vers la liste des absences
        header('Location: absence_list.php');
        exit();
    } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement de l'absence : " . htmlspecialchars($e->getMessage());
    }
}

$etudiants = $pdo->query("SELECT id, nom, prenom FROM etudiants")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Marquer une absence</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Marquer une absence</h2>
    <div id="absence">
        <form action="add_absence.php" method="POST">
            <label for="etudiant_id">Étudiant :</label>
            <select name="etudiant_id" id="etudiant_id" required>
                <?php foreach ($etudiants as $etudiant): ?>
                    <option value="<?= htmlspecialchars($etudiant['id']) ?>">
                        <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date_absence">Date :</label>
            <input type="date" id="date_absence" name="date_absence" required>

            <label for="motif">Motif :</label>
            <input type="text" id="motif" name="motif" placeholder="Motif (facultatif)">

            <button type="submit">Enregistrer l'absence</button>
        </form>

        <button onclick="window.location.href='list_student.php'" class="btn-back">Retour à la liste des étudiants</button>
    </div>
</body>
</html>
