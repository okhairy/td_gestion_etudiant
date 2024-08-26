<?php
include 'functions.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE etudiants SET archived = 1 WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header('Location: list_students.php');
    exit();
} else {
    die("ID non spécifié.");
}
?>
