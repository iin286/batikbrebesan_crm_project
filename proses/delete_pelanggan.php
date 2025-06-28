<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM pelanggan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../pages/pelanggan.php");
exit;
