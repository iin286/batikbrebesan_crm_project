<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Cek apakah username sudah digunakan
    $cek_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $cek_stmt->bind_param("s", $username);
    $cek_stmt->execute();
    $cek_stmt->store_result();

    if ($cek_stmt->num_rows > 0) {
        $error = "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Gagal mendaftar. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - Toko Batiku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdfaf7;
            height: 100vh;
            overflow: hidden;
            font-family: 'Segoe UI', sans-serif;
        }

        body::before, body::after {
            content: "";
            background-repeat: no-repeat;
            background-size: 300px;
            position: absolute;
            width: 300px;
            height: 300px;
            z-index: 0;
            opacity: 0.2;
        }

        body::before {
            top: 0;
            left: 0;
        }

        body::after {
            bottom: 0;
            right: 0;
            transform: scaleX(-1);
        }

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            z-index: 1;
            position: relative;
        }

        .register-card {
            display: flex;
            width: 800px;
            height: auto;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background-color: white;
        }

        .left-side {
            width: 45%;
            background-color: #a87c4d;
            color: #fff;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .left-side img {
            width: 70px;
            margin-bottom: 15px;
        }

        .left-side h3 {
            font-weight: 600;
            font-size: 26px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .right-side {
            width: 55%;
            padding: 40px 30px;
        }

        .right-side h4 {
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .form-control, .form-select {
            border-radius: 8px;
        }

        .btn-custom {
            background-color: #c29664;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-custom:hover {
            background-color: #a87c4d;
        }

        .text-small {
            font-size: 0.9rem;
        }

        .alert {
            font-size: 0.9rem;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-card">
        <!-- Kiri -->
        <div class="left-side">
            <img src="../assets/logo/logo.png" alt="Logo Toko">
            <h3>BATIKU</h3>
            <p class="text-light text-center mt-2">Selamat datang di Batiku. Silakan daftar untuk melanjutkan.</p>
        </div>

        <!-- Kanan -->
        <div class="right-side">
            <h4 class="text-center">Form Pendaftaran</h4>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="mt-3">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>
                <div class="mb-4">
                    <select name="role" class="form-select" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="sales">Sales</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-custom w-100">Daftar</button>
            </form>

            <div class="text-center text-small mt-3">
                <p>Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
