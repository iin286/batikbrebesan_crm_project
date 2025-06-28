<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$result = $conn->query("SELECT * FROM promosi ORDER BY tanggal_mulai DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Promosi</title>
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
        <div class="container">

            <!-- Form Tambah Promosi -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-black" style="background-color: #EAEAEA;">
                    <h5 class="mb-0">Tambah Promosi</h5>
                </div>
                <div class="card-body">
                    <form action="../proses/tambah_promosi.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Judul Promosi</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diskon (%)</label>
                            <input type="number" name="diskon" class="form-control" min="1" max="100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                Simpan Promosi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar Promosi -->
            <div class="card shadow-sm">
                <div class="card-header text-black" style="background-color: #EAEAEA;">
                    <h5 class="mb-0">Daftar Promosi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Judul</th>
                                    <th>Diskon</th>
                                    <th>Periode</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['judul']) ?></td>
                                        <td class="text-center"><?= $row['diskon'] ?>%</td>
                                        <td class="text-center"><?= $row['tanggal_mulai'] ?> s/d <?= $row['tanggal_selesai'] ?></td>
                                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $now = date("Y-m-d");
                                            echo ($now >= $row['tanggal_mulai'] && $now <= $row['tanggal_selesai'])
                                                ? '<span class="badge bg-success">Aktif</span>'
                                                : '<span class="badge bg-secondary">Nonaktif</span>';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="../proses/delete_promosi.php?id=<?= $row['id'] ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Hapus promosi ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if ($result->num_rows === 0): ?>
                                    <tr><td colspan="6" class="text-center text-muted">Belum ada promosi ditambahkan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
</div>
</body>
</html>
