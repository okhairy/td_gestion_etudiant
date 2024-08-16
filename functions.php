<?php
include 'db.php';

// Fonction pour hasher le mot de passe
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Fonction pour vérifier le mot de passe
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Fonction pour générer un matricule
function generateMatricule() {
    return uniqid('ETU');
}

// Fonction pour vérifier l'unicité de l'email
function isEmailUnique($email, $type = 'student') {
    global $pdo;
    $table = $type === 'admin' ? 'administrators' : 'students';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() == 0;
}
?>
