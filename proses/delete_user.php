<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Cegah admin menghapus dirinya sendiri (opsional)
    $currentUser = $_SESSION['username'];
    $cek = $conn->query("SELECT * FROM users WHERE id = $id");
    $data = $cek->fetch_assoc();

    if ($data && $data['username'] === $currentUser) {
        echo "<script>alert('Tidak dapat menghapus akun yang sedang digunakan.'); window.location.href='kelola_pengguna.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ../pages/kelola_pengguna.php");
    exit;
} else {
    echo "ID pengguna tidak ditemukan.";
}
?>
