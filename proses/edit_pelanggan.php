<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil ID pelanggan dari parameter GET
$id = $_GET['id'] ?? null;

// Jika ID tidak ada, redirect
if (!$id) {
    header("Location: ../pages/pelanggan.php");
    exit;
}

// Ambil data pelanggan berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$row) {
    echo "<script>alert('Data pelanggan tidak ditemukan.'); window.location='../pages/pelanggan.php';</script>";
    exit;
}

// Proses update data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    $update = $conn->prepare("UPDATE pelanggan SET nama = ?, no_hp = ?, alamat = ? WHERE id = ?");
    $update->bind_param("sssi", $nama, $no_hp, $alamat, $id);
    $update->execute();

    header("Location: ../pages/pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
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
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Data Pelanggan</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($row['no_hp']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" required><?= htmlspecialchars($row['alamat']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
