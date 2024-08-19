<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';
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
        $_SESSION['error_message'] = "L'étudiant doit avoir au moins 18 ans.";
        header('Location: inscrire_student.php');
        exit();
    } 

    // Validation du format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Le format de l'email est invalide.";
        header('Location: inscrire_student.php');
        exit();
    }

    // Validation du numéro de téléphone
    if (!preg_match('/^(77|78|76|70|75)\d{7}$/', $telephone)) {
        $_SESSION['error_message'] = "Le numéro de téléphone doit commencer par 77, 78, 76, 70 ou 75 et contenir exactement 9 chiffres.";
        header('Location: inscrire_student.php');
        exit();
    } 

    // Vérifier si l'email est déjà utilisé
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error_message'] = "Cet email est déjà utilisé.";
        header('Location: inscrire_student.php');
        exit();
    }

    // Vérifier si le téléphone est déjà utilisé
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE telephone = :telephone");
    $stmt->execute(['telephone' => $telephone]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error_message'] = "Ce numéro de téléphone est déjà utilisé.";
        header('Location: inscrire_student.php');
        exit();
    }

    // Génération d'un matricule unique basé sur l'année en cours et un nombre aléatoire
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

    // Message de succès et redirection
    $_SESSION['success_message'] = "Étudiant ajouté avec succès.";
    header('Location: list_student.php');
    exit();
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
        <form action="" method="POST">
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
            
            <!-- Le champ matricule est généré automatiquement, donc pas besoin de champ ici -->
            
            <button type="submit">Ajouter</button>
        </form>

        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="success"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <button onclick="window.location.href='admin_dashboard.php'" class="btn-back">Retour au tableau de bord</button>
    </div>
</body>
</html>
