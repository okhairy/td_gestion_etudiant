<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mise à jour de l'état de l'étudiant pour le désarchiver
    $stmt = $pdo->prepare("UPDATE etudiants SET archived = 0 WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header('Location: list_students.php');
    exit();
} else {
    die("ID d'étudiant non spécifié.");
}
?>
