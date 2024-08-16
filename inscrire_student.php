<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Traitement du formulaire d'ajout d'étudiant
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $niveau = $_POST['niveau'];

    // Vérifier l'âge de l'étudiant (doit avoir au moins 18 ans)
    $date_naissance_obj = new DateTime($date_naissance);
    $today = new DateTime('today');
    $age = $today->diff($date_naissance_obj)->y;

    if ($age < 18) {
        $error = "L'étudiant doit avoir au moins 18 ans.";
    } else {

    // Validation du numéro de téléphone (doit contenir exactement 9 chiffres)
    if (!preg_match('/^\d{9}$/', $telephone)) {
        $error = "Le numéro de téléphone doit comporter exactement 9 chiffres.";
    } else {

        // Génération d'un matricule unique
        $matricule = strtoupper(substr($nom, 0, 2)) . date('Y') . rand(100, 999);

        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, date_naissance, email, telephone, niveau, matricule) VALUES (:nom, :prenom, :date_naissance, :email, :telephone, :niveau, :matricule)");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $date_naissance,
            'email' => $email,
            'telephone' => $telephone,
            'niveau' => $niveau,
            'matricule' => $matricule
        ]);

        // Redirection après l'ajout
        header('Location: list_student.php');
        exit();
    }
}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2 id="baisse">Ajouter un étudiant</h2>
    <div class="form-container">
        <form action="inscrire_student.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" required>
            
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
            
            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" required>
            
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            
            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone" placeholder="Téléphone" required>
            
            <label for="niveau">Niveau :</label>
            <select id="niveau" name="niveau" required>
                <option value="L1">L1</option>
                <option value="L2">L2</option>
                <option value="L3">L3</option>
                <option value="M1">M1</option>
                <option value="M2">M2</option>
            </select>
            <label for="matricule">matricule :</label>
            <input type="text" id="matricule" name="matricule" placeholder="matricule" required>
            
            <button type="submit">Ajouter</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>
</body>
</html>
