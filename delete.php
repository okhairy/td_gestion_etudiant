<?php
include 'functions.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM administrateurs WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header('Location: admin_list.php');
    exit();
} else {
    die("ID non spécifié.");
}
?>
