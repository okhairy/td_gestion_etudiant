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
    
    // Vérifier que le nom et le prénom ne contiennent que des lettres (sans espaces ni caractères spéciaux)
    if (!preg_match('/^[a-zA-Z]+$/', $nom)) {
        $_SESSION['error_message'] = "Le nom ne doit contenir que des lettres sans espaces ni caractères spéciaux.";
        $_SESSION['form_data'] = $_POST;
        header('Location: inscrire_student.php');
        exit();
    } elseif (!preg_match('/^[a-zA-Z]+$/', $prenom)) {
        $_SESSION['error_message'] = "Le prénom ne doit contenir que des lettres sans espaces ni caractères spéciaux.";
        $_SESSION['form_data'] = $_POST;
        header('Location: inscrire_student.php');
        exit();
    }
   
    // Vérifier l'âge de l'étudiant (doit avoir au moins 18 ans)
    $date_naissance_obj = new DateTime($date_naissance);
    $today = new DateTime('today');
    $age = $today->diff($date_naissance_obj)->y;

    if ($age < 18) {
        $_SESSION['error_message'] = "L'étudiant doit avoir au moins 18 ans.";
        $_SESSION['form_data'] = $_POST;
        header('Location: inscrire_student.php');
        exit();
    } 

    // Validation du format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Le format de l'email est invalide.";
        $_SESSION['form_data'] = $_POST;
        header('Location: inscrire_student.php');
        exit();
    }

    // Validation du numéro de téléphone
    if (!preg_match('/^(77|78|76|70|75)\d{7}$/', $telephone)) {
        $_SESSION['error_message'] = "Le numéro de téléphone doit commencer par 77, 78, 76, 70 ou 75 et contenir exactement 9 chiffres.";
        $_SESSION['form_data'] = $_POST;
        header('Location: inscrire_student.php');
        exit();
    } 

    // Vérifier si l'email est déjà utilisé
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error_message'] = "Cet email est déjà utilisé.";
        $_SESSION['form_data'] = $_POST;
        header('Location: inscrire_student.php');
        exit();
    }

    // Vérifier si le téléphone est déjà utilisé
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE telephone = :telephone");
    $stmt->execute(['telephone' => $telephone]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error_message'] = "Ce numéro de téléphone est déjà utilisé.";
        $_SESSION['form_data'] = $_POST;
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
    header('Location: list_students.php');
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
            <input type="text" id="nom" name="nom" placeholder="Nom" value="<?= htmlspecialchars($_SESSION['form_data']['nom'] ?? '') ?>" required>
            
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" placeholder="Prénom" value="<?= htmlspecialchars($_SESSION['form_data']['prenom'] ?? '') ?>" required>
            
            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" required max="2006-12-31">

            
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>" required>
            
            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone" placeholder="Téléphone" value="<?= htmlspecialchars($_SESSION['form_data']['telephone'] ?? '') ?>" required>
            
            <label for="niveau">Niveau :</label>
            <select id="niveau" name="niveau" required>
                <option value="L1" <?= isset($_SESSION['form_data']['niveau']) && $_SESSION['form_data']['niveau'] == 'L1' ? 'selected' : '' ?>>L1</option>
                <option value="L2" <?= isset($_SESSION['form_data']['niveau']) && $_SESSION['form_data']['niveau'] == 'L2' ? 'selected' : '' ?>>L2</option>
                <option value="L3" <?= isset($_SESSION['form_data']['niveau']) && $_SESSION['form_data']['niveau'] == 'L3' ? 'selected' : '' ?>>L3</option>
                <option value="M1" <?= isset($_SESSION['form_data']['niveau']) && $_SESSION['form_data']['niveau'] == 'M1' ? 'selected' : '' ?>>M1</option>
                <option value="M2" <?= isset($_SESSION['form_data']['niveau']) && $_SESSION['form_data']['niveau'] == 'M2' ? 'selected' : '' ?>>M2</option>
            </select>
            
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

    <script>
        // Restriction de la date de naissance à au moins 18 ans
        document.addEventListener('DOMContentLoaded', function () {
            const dateNaissanceInput = document.getElementById('date_naissance');
            const today = new Date();
            const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            dateNaissanceInput.max = maxDate.toISOString().split('T')[0];
        });
    </script>
</body>
</html>
