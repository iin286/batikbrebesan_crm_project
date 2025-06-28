<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil notifikasi penjualan hari ini
$today = date('Y-m-d');
$notif_result = $conn->query("SELECT * FROM penjualan WHERE tanggal = '$today' ORDER BY id DESC LIMIT 5");
$notif_count = $notif_result->num_rows;
?>

<!-- Header -->
<div class="border-bottom shadow-sm py-3 px-4 d-flex justify-content-between align-items-center"
     style="position: fixed; top: 0; left: 250px; right: 0; height: 70px; z-index: 1000; background-color: #EAEAEA;">

    <span class="fw-semibold text-dark">Selamat datang, <?= htmlspecialchars($_SESSION['role']) ?></span>

    <!-- Notifikasi Dropdown -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell fs-5"></i>
            <?php if ($notif_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $notif_count ?>
                </span>
            <?php endif; ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notifDropdown" style="min-width: 300px;">
            <li class="dropdown-header fw-bold text-dark">Penjualan Hari Ini</li>
            <?php if ($notif_count > 0): ?>
                <?php while($n = $notif_result->fetch_assoc()): ?>
                    <li class="dropdown-item small">
                        <div><strong>ID Produk:</strong> <?= $n['id_produk'] ?></div>
                        <div><strong>Jumlah:</strong> <?= $n['jumlah'] ?> | <strong>Total:</strong> Rp <?= number_format($n['total_harga'], 0, ',', '.') ?></div>
                        <div class="text-muted small"><?= $n['tanggal'] ?></div>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li><span class="dropdown-item text-muted">Belum ada penjualan hari ini</span></li>
            <?php endif; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a href="../pages/penjualan.php" class="dropdown-item">Lihat Semua Penjualan</a></li>
        </ul>
    </div>
</div>
