<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$produk = $conn->query("SELECT * FROM produk_batik");
$pelanggan = $conn->query("SELECT * FROM pelanggan");
$promosi = $conn->query("SELECT * FROM promosi");

$penjualan = $conn->query("
    SELECT 
        p.id, pb.nama_produk, pb.warna, pl.nama AS nama_pelanggan, pl.no_hp, pl.alamat, 
        p.tanggal, p.jumlah, p.total_harga, pr.judul AS promo_judul, pr.diskon AS promo_diskon
    FROM penjualan p 
    JOIN produk_batik pb ON p.id_produk = pb.id 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id 
    LEFT JOIN promosi pr ON p.id_promosi = pr.id
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjualan</title>
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
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <?php include '../layout/header.php'; ?>
    <div class="content-wrapper">
        <div class="container mt-4">

            <!-- Form Tambah Penjualan -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-black" style="background-color: #EAEAEA;">
                    <h5 class="mb-0">Tambah Pemesanan</h5>
                </div>
                <div class="card-body">
                    <form action="../proses/tambah_penjualan.php" method="POST">
                        <div class="mb-3">
                            <label for="id_produk" class="form-label">Nama Produk</label>
                            <select name="id_produk" id="id_produk" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php while ($p = $produk->fetch_assoc()): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= $p['nama_produk'] ?> - <?= $p['warna'] ?> (Rp <?= number_format($p['harga'], 0, ',', '.') ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_pelanggan" class="form-label">Nama Pelanggan</label>
                            <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php while ($pl = $pelanggan->fetch_assoc()): ?>
                                    <option value="<?= $pl['id'] ?>">
                                        <?= $pl['nama'] ?> - <?= $pl['no_hp'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_promosi" class="form-label">Promosi (Diskon)</label>
                            <select name="id_promosi" id="id_promosi" class="form-select">
                                <option value="">-- Pilih Diskon --</option>
                                <?php while ($pr = $promosi->fetch_assoc()): ?>
                                    <option value="<?= $pr['id'] ?>">
                                        <?= $pr['judul'] ?> (<?= $pr['diskon'] ?>%)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat Penjualan -->
            <div class="card">
                <div class="card-header text-black" style="background-color: #EAEAEA;">
                    <h5 class="mb-0">Daftar Penjualan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Produk</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Promo</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $penjualan->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['nama_produk'] ?> (<?= $row['warna'] ?>)</td>
                                    <td><?= $row['nama_pelanggan'] ?> - <?= $row['no_hp'] ?><br><small><?= $row['alamat'] ?></small></td>
                                    <td class="text-center"><?= $row['jumlah'] ?></td>
                                    <td class="text-center"><?= $row['tanggal'] ?></td>
                                    <td><?= $row['promo_judul'] ? $row['promo_judul'] . ' (' . $row['promo_diskon'] . '%)' : '-' ?></td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="../proses/delete_penjualan.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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
