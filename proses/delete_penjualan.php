<?php
session_start();
include '../config/db.php';

// Cek apakah user login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Validasi ID dari parameter URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID penjualan tidak valid!";
    exit;
}

$id = (int)$_GET['id'];

// Hapus data dari database
$stmt = $conn->prepare("DELETE FROM penjualan WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../pages/penjualan.php");
    exit;
} else {
    echo "Gagal menghapus data.";
}

$stmt->close();
$conn->close();
