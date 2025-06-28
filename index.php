<?php
session_start();

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: auth/login.php");
    exit;
}

// Jika belum login, arahkan ke halaman login
header("Location: auth/login.php");
exit;
