<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM produk_batik WHERE id='$id'");
$row = $data->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_produk'];
    $warna = $_POST['warna'];
    $harga = $_POST['harga'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar_baru = $_FILES['gambar']['name'];
        $tmp_gambar = $_FILES['gambar']['tmp_name'];
        $upload_path = '../uploads/' . $gambar_baru;

        // Hapus gambar lama jika ada
        if (file_exists('../uploads/' . $row['gambar'])) {
            unlink('../uploads/' . $row['gambar']);
        }

        move_uploaded_file($tmp_gambar, $upload_path);

        $stmt = $conn->prepare("UPDATE produk_batik SET nama_produk = ?, warna = ?, harga = ?, gambar = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $nama, $warna, $harga, $gambar_baru, $id);
    } else {
        $stmt = $conn->prepare("UPDATE produk_batik SET nama_produk = ?, warna = ?, harga = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nama, $warna, $harga, $id);
    }

    $stmt->execute();
    header("Location: ../pages/batik.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Batik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

<?php include '../layout/sidebar.php'; ?>
<div class="main-content">
<?php include '../layout/header.php'; ?>

<div class="content-wrapper">
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">Edit Produk Batik</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <select name="nama_produk" class="form-select" required>
                            <option value="Batik motif kopi pecah" <?= $row['nama_produk'] == 'Batik motif kopi pecah' ? 'selected' : '' ?>>Batik motif kopi pecah</option>
                            <option value="Batik motif manggar" <?= $row['nama_produk'] == 'Batik motif manggar' ? 'selected' : '' ?>>Batik motif manggar</option>
                            <option value="Batik motif ukel" <?= $row['nama_produk'] == 'Batik motif ukel' ? 'selected' : '' ?>>Batik motif ukel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Warna</label>
                        <select name="warna" class="form-select" required>
                            <option value="biru" <?= $row['warna'] == 'biru' ? 'selected' : '' ?>>Biru</option>
                            <option value="merah" <?= $row['warna'] == 'merah' ? 'selected' : '' ?>>Merah</option>
                            <option value="hijau" <?= $row['warna'] == 'hijau' ? 'selected' : '' ?>>Hijau</option>
                            <option value="hitam" <?= $row['warna'] == 'hitam' ? 'selected' : '' ?>>Hitam</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Ganti Gambar (Opsional)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <strong>Gambar Saat Ini:</strong><br>
                            <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" width="120">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="../pages/batik.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
</div>
</body>
</html>
