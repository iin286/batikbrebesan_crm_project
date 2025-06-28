<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$penjualan = $conn->query("
    SELECT 
        p.id, 
        pb.nama_produk, 
        pb.warna, 
        pb.harga, 
        pl.nama AS nama_pelanggan, 
        p.jumlah, 
        p.tanggal, 
        p.total_harga 
    FROM penjualan p 
    JOIN produk_batik pb ON p.id_produk = pb.id 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id 
    ORDER BY p.tanggal DESC
");

$totalPendapatan = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
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
        }

        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 12px;
        }

        .card-header {
            background-color: #EAEAEA;
            border-bottom: 1px solid #d1d1d1;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .btn-export {
            background-color: #28a745;
            color: #fff;
            transition: 0.3s;
        }

        .btn-export:hover {
            background-color: #218838;
        }

        table th, table td {
            vertical-align: middle;
        }

        thead {
            background-color: #f0f0f0;
        }

        tfoot tr {
            background-color: #d4edda;
        }
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <?php include '../layout/header.php'; ?>

    <div class="content-wrapper">
        <div class="container mt-3">
            <div class="card">
                <div class="card-header text-black d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laporan Penjualan</h5>
                    <a href="../proses/export_laporan_excel.php" class="btn btn-export btn-sm">
                        Export ke Excel
                    </a>
                </div>

                <div class="table-responsive p-3">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>
                                <th>Warna</th>
                                <th>Harga Satuan</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $penjualan->fetch_assoc()): ?>
                                <?php $totalPendapatan += $row['total_harga']; ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                    <td><?= htmlspecialchars($row['warna']) ?></td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                    <td><?= $row['jumlah'] ?></td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="6" class="text-end">Total Pendapatan</td>
                                <td>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div> <!-- end card -->
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
</div>
</body>
</html>
