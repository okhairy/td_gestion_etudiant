<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer et exécuter la requête de suppression
    $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Rediriger après la suppression
    header('Location: list_students.php');
    exit();
} else {
    die("ID non spécifié.");
}
?>
