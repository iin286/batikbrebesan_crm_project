<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_produk'];
    $warna = $_POST['warna'];
    $harga = $_POST['harga'];

    // Upload Gambar
    $gambar_name = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $upload_dir = '../uploads/';
    $gambar_path = $upload_dir . basename($gambar_name);
    move_uploaded_file($gambar_tmp, $gambar_path);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO produk_batik (nama_produk, warna, harga, gambar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nama, $warna, $harga, $gambar_name);
    $stmt->execute();

    header("Location: ../pages/batik.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Batik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .main-content { margin-left: 250px; padding: 20px; }
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>
<div class="main-content">
<?php include '../layout/header.php'; ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Tambah Produk Batik</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Nama Produk</label>
                    <select name="nama_produk" class="form-select" required>
                        <option value="">-- Pilih Motif --</option>
                        <option value="Batik motif kopi pecah">Batik motif kopi pecah</option>
                        <option value="Batik motif manggar">Batik motif manggar</option>
                        <option value="Batik motif ukel">Batik motif ukel</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Warna</label>
                    <select name="warna" class="form-select" required>
                        <option value="">-- Pilih Warna --</option>
                        <option value="biru">Biru</option>
                        <option value="merah">Merah</option>
                        <option value="hijau">Hijau</option>
                        <option value="hitam">Hitam</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Upload Gambar</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="../pages/batik.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
</div>
</body>
</html>
