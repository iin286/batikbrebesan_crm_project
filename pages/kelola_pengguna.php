<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }

        .card-header {
            background-color: #EAEAEA;
            border-bottom: 1px solid #d1d1d1;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
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

<!-- Sidebar -->
<?php include '../layout/sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">

    <!-- Header -->
    <?php include '../layout/header.php'; ?>

    <!-- Page Content -->
      <div class="content-wrapper">
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-header text-black" style="background-color: #EAEAEA;">
                    <h5 class="mb-0">Kelola Pengguna</h5>
                </div>
                <div class="card-body">

                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Username</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../config/db.php';
                            $result = $conn->query("SELECT * FROM users");
                            while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['role']) ?></td>
                                <td class="text-center" style="width: 150px;">
                                    <a href="../proses/edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="../proses/delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    <!-- Footer -->
    <?php include '../layout/footer.php'; ?>

</div>
</body>
</html>
