<?php
include '../config/db.php';

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

$penjualan = $conn->query("
    SELECT 
        p.id, 
        pb.nama_produk, 
        pb.warna, 
        pb.harga, 
        pl.nama AS nama_pelanggan, 
        p.jumlah, 
        p.tanggal, 
        p.total_harga 
    FROM penjualan p 
    JOIN produk_batik pb ON p.id_produk = pb.id 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id 
    ORDER BY p.tanggal DESC
");
?>

<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Produk</th>
            <th>Warna</th>
            <th>Harga Satuan</th>
            <th>Nama Pelanggan</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php $totalPendapatan = 0; ?>
        <?php while($row = $penjualan->fetch_assoc()): ?>
        <?php $totalPendapatan += $row['total_harga']; ?>
        <tr>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['warna'] ?></td>
            <td><?= $row['harga'] ?></td>
            <td><?= $row['nama_pelanggan'] ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= $row['total_harga'] ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="6"><strong>Total Pendapatan</strong></td>
            <td><strong><?= $totalPendapatan ?></strong></td>
        </tr>
    </tbody>
</table>
