<?php
// Déclaration des variables de connexion
$host = 'localhost';         // Nom de l'hôte de la base de données (ici, le serveur local)
$dbname = 'tp_gestion_etudiants'; // Nom de la base de données
$user = 'root';              // Nom d'utilisateur pour accéder à la base de données (par défaut, 'root' pour un serveur local)
$password = '';              // Mot de passe pour accéder à la base de données (par défaut, vide pour un serveur local)

try {
    // Création d'une instance de PDO (PHP Data Objects) pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    
    // Configuration des attributs de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Définit le mode d'erreur pour lancer des exceptions en cas d'erreur SQL
} catch (PDOException $e) {
    // En cas d'échec de la connexion, un message d'erreur est affiché et le script est arrêté
    die("Erreur de connexion : " . $e->getMessage());
}
?>