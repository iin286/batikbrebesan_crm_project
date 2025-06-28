<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Koneksi database
include '../config/db.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit;
}

$id = intval($_GET['id']);
$success = '';
$error = '';

// Ambil data user berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    // Cek apakah username sudah digunakan oleh user lain
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $checkStmt->bind_param("si", $username, $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $error = "Username sudah digunakan oleh pengguna lain.";
    } else {
        $updateStmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $username, $role, $id);

        if ($updateStmt->execute()) {
            $success = "Data pengguna berhasil diperbarui.";
            // Refresh data
            $user['username'] = $username;
            $user['role'] = $role;
        } else {
            $error = "Terjadi kesalahan saat memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }

        .card-header {
            background-color: #EAEAEA;
            border-bottom: 1px solid #d1d1d1;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .main-content {
            margin-left: 250px;
            padding-top: 70px; /* header height */
            padding-bottom: 60px; /* footer height */
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include '../layout/sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">


    <!-- Page Content -->
    <div class="content-wrapper">
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-header text-black">
                    <h5 class="mb-0">Edit Pengguna</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username (Email)</label>
                            <input type="email" name="username" id="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="../pages/kelola_pengguna.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../layout/footer.php'; ?>

</div>
</body>
</html>
