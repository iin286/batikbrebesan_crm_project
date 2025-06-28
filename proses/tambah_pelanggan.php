<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Proses simpan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    $stmt = $conn->prepare("INSERT INTO pelanggan (nama, no_hp, alamat) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $no_hp, $alamat);
    $stmt->execute();

    // Setelah disimpan, redirect ke halaman pelanggan.php
    header("Location: ../pages/pelanggan.php");
    exit;
}

// Ambil data pelanggan untuk ditampilkan (opsional)
$result = $conn->query("SELECT * FROM pelanggan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
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

<?php include '../layout/sidebar.php'; ?>
<div class="main-content">
    <?php include '../layout/header.php'; ?>

    <div class="content-wrapper">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header bg-primary text-white">Tambah Pelanggan</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>No. HP</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="../pages/pelanggan.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../layout/footer.php'; ?>
</div>
</body>
</html>
