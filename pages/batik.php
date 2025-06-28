<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';
$result = $conn->query("SELECT * FROM produk_batik");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Produk Batik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 250px;
            padding-top: 70px;
            padding-bottom: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .content-wrapper {
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
            margin-bottom: 0;
            font-weight: 600;
        }

        .table img {
            width: 80px;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: transform 0.3s;
        }

        .table img:hover {
            transform: scale(1.05);
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

        .btn-success {
            background-color: #198754;
            border: none;
        }

        .btn-success:hover {
            background-color: #157347;
        }
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <?php include '../layout/header.php'; ?>

    <div class="content-wrapper">
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Data Produk Batik</h5>
                    <a href="../proses/tambah_batik.php" class="btn btn-success btn-sm">
                        Tambah Produk
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Warna</th>
                                    <th>Harga</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if (!empty($row['gambar']) && file_exists("../uploads/" . $row['gambar'])): ?>
                                                <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Produk">
                                            <?php else: ?>
                                                <img src="../assets/no-image.png" alt="Tidak ada gambar">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                        <td><?= htmlspecialchars($row['warna']) ?></td>
                                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <a href="../proses/edit_batik.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <a href="../proses/delete_batik.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="btn btn-danger btn-sm">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if ($result->num_rows === 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada data produk batik.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end card -->
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
</div>

</body>
</html>
