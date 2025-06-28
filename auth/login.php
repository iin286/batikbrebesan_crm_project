<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../pages/dashboard.php");
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "User tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - CRM Batik Brebesan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: #f8f9fa;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 300px;
            height: 300px;
            background-size: contain;
            z-index: -1;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background-size: contain;
            transform: scaleX(-1);
            z-index: 1;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            display: flex;
            width: 800px;
            height: 450px;
            background: #fff;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }

        .login-left {
            background-color: #a87c4d;
            color: white;
            padding: 40px 30px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        .login-left h2 {
            font-weight: bold;
        }

        .login-right {
            width: 50%;
            padding: 40px;
        }

        .form-control {
            background-color: #f1f4f9;
        }

        .btn-primary {
            background-color: #0066ff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056d2;
        }

        .text-small {
            font-size: 14px;
        }

        .logo-batiku {
            width: 150px ;      /* Ukuran lebih besar */
            height: auto;
            margin-bottom: 20px;
        }

        .login-left {
            background-color: #a87c4d;
            color: white;
            padding: 40px 30px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; 
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <div class="login-left text-center">
            <img src="../assets/logo/logo.png" alt="Logo Toko" class="logo-batiku">
            <h3 class="mt-1">BATIKU</h3>
        </div>
        <div class="login-right">
            <h3 class="text-center mb-4">Login</h3>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn w-100" style="background-color: #C29664; border-color: #C29664; color: #fff;">
                Login
                </button>
            </form>
            <div class="text-center text-small mt-3">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
