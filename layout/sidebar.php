<!-- Sidebar Start -->
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #a87c4d;
        position: fixed;
        top: 0;
        left: 0;
        color: white;
        z-index: 999;
    }

    .sidebar-logo {
        width: 60px;
        height: auto;
        margin-bottom: 10px;
    }

    .sidebar-header {
        text-align: center;
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .sidebar-header h5 {
        margin: 0;
        font-weight: bold;
    }

    .sidebar-header small {
        font-size: 13px;
    }

    .sidebar-links {
        padding-top: 20px;
        display: flex;
        flex-direction: column;
        height: calc(100% - 150px); 
    }

    .sidebar-link {
        padding: 10px 20px;
        text-decoration: none;
        color: white;
        transition: background-color 0.2s ease, padding-left 0.2s ease;
    }

    .sidebar-link:hover {
        background-color: #c29664;
        padding-left: 30px;
        border-radius: 4px;
        color: #fff;
    }

    .sidebar-logout {
        border-top: 1px solid rgba(255,255,255,0.2);
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="../assets/logo/logo.png" alt="Logo Batiku" class="sidebar-logo">
        <h5 class="mt-2 mb-0">BATIKU</h5>
    </div>

    <div class="sidebar-links">
        <a class="sidebar-link" href="../pages/dashboard.php">Dashboard</a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a class="sidebar-link" href="../pages/kelola_pengguna.php">Kelola Pengguna</a>
            <a class="sidebar-link" href="../pages/batik.php">Kelola Produk</a>
            <a class="sidebar-link" href="../pages/pelanggan.php">Kelola Pelanggan</a>
            <a class="sidebar-link" href="../pages/laporan.php">Laporan</a>

        <?php elseif ($_SESSION['role'] === 'sales'): ?>
            <a class="sidebar-link" href="../pages/pelanggan.php">Tambah Pelanggan</a>
            <a class="sidebar-link" href="../pages/penjualan.php">Tambah Penjualan</a>

        <?php elseif ($_SESSION['role'] === 'marketing'): ?>
            <a class="sidebar-link" href="../pages/promosi.php">Promosi</a>
            <a class="sidebar-link" href="../pages/laporan.php">Laporan</a>
        <?php endif; ?>

        <a class="sidebar-link sidebar-logout" href="../auth/logout.php">Logout</a>
    </div>
</div>
<!-- Sidebar End -->
