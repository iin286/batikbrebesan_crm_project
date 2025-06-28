<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';
$result = $conn->query("SELECT * FROM pelanggan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }

        .main-content {
            margin-left: 250px;
            padding-top: 70px;
            padding-bottom: 60px;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }

        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .card-header {
            background-color: #EAEAEA;
            border-bottom: 1px solid #ccc;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .btn-sm {
            font-size: 0.8rem;
        }

        .btn-warning:hover {
            background-color: #d39e00;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-primary {
            background-color: #0069d9;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table thead {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>
<div class="main-content">
    <?php include '../layout/header.php'; ?>

    <div class="content-wrapper">
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Data Pelanggan</h5>
                    <a href="../proses/tambah_pelanggan.php" class="btn btn-primary btn-sm">
                       Tambah Pelanggan
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="text-center">
                            <tr>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td class="text-center">
                                    <a href="../proses/edit_pelanggan.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <a href="../proses/delete_pelanggan.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelanggan ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($result->num_rows === 0): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada data pelanggan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
</div>
</body>
</html>
