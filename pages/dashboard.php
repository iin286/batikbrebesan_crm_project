<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Hitung data
$jml_pelanggan = $conn->query("SELECT COUNT(*) AS total FROM pelanggan")->fetch_assoc()['total'];
$jml_produk = $conn->query("SELECT COUNT(*) AS total FROM produk_batik")->fetch_assoc()['total'];
$jml_diskon = $conn->query("SELECT COUNT(*) AS total FROM promosi")->fetch_assoc()['total'];

// Ambil data produk
$produk = $conn->query("SELECT * FROM produk_batik ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body { margin: 0; height: 100%; overflow: hidden; }
        .main-content { margin-left: 250px; padding-top: 70px; padding-bottom: 60px; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        .content-wrapper { flex: 1; overflow-y: auto; padding: 20px; }
        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
<?php include '../layout/header.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <h2>Dashboard</h2>

        <!-- Card Statistik -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row text-white">
                    <div class="col-md-4 mb-3">
                        <div class="bg-warning rounded p-3 d-flex align-items-center">
                            <div class="icon-circle bg-white text-primary"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div>Jumlah Pelanggan</div>
                                <h4 class="mb-0"><?= $jml_pelanggan ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-success rounded p-3 d-flex align-items-center">
                            <div class="icon-circle bg-white text-success"><i class="bi bi-bag-fill"></i></div>
                            <div>
                                <div>Jumlah Produk Batik</div>
                                <h4 class="mb-0"><?= $jml_produk ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-warning rounded p-3 d-flex align-items-center">
                            <div class="icon-circle bg-white text-warning"><i class="bi bi-tags-fill"></i></div>
                            <div>
                                <div>Diskon / Promosi</div>
                                <h4 class="mb-0"><?= $jml_diskon ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Katalog Produk -->
        <div class="card shadow-sm">
            <div class="card-header text-black" style="background-color: #EAEAEA;">
                <h5 class="mb-0">Katalog Produk</h5>
            </div>
            <div class="card-body" >
                <div class="row">
                    <?php while ($row = $produk->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm" style="background-color: #EAEAEA;">
                            <?php if (!empty($row['gambar']) && file_exists('../uploads/' . $row['gambar'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Produk">
                            <?php else: ?>
                                <div class="bg-light text-muted text-center py-5">Tidak ada gambar</div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                                <p class="card-text">Warna: <?= htmlspecialchars($row['warna']) ?></p>
                                <p class="card-text fw-bold text-primary">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php if ($produk->num_rows === 0): ?>
                        <div class="col-12 text-center text-muted">Belum ada produk ditambahkan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../layout/footer.php'; ?>
</div>

</body>
</html>
