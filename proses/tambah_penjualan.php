<?php
session_start();
include '../config/db.php';

$id_produk     = $_POST['id_produk'];
$id_pelanggan  = $_POST['id_pelanggan'];
$jumlah        = $_POST['jumlah'];
$tanggal       = $_POST['tanggal'];
$id_promosi    = !empty($_POST['id_promosi']) ? $_POST['id_promosi'] : null;

// Ambil harga produk
$stmt = $conn->prepare("SELECT harga FROM produk_batik WHERE id = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$harga_satuan = $row['harga'];
$stmt->close();

// Hitung subtotal
$subtotal = $harga_satuan * $jumlah;
$diskon_total = 0;

// Ambil diskon dari promosi jika dipilih
if ($id_promosi !== null) {
    $stmt = $conn->prepare("SELECT diskon FROM promosi WHERE id = ?");
    $stmt->bind_param("i", $id_promosi);
    $stmt->execute();
    $result = $stmt->get_result();
    $promo = $result->fetch_assoc();
    $diskon_total += ($promo['diskon'] / 100) * $subtotal;
    $stmt->close();
}

// Tambahan diskon 10% jika beli 3 atau lebih
if ($jumlah >= 3) {
    $diskon_total += 0.10 * $subtotal;
}

$total_harga = $subtotal - $diskon_total;

// Insert ke tabel penjualan
if ($id_promosi !== null) {
    $stmt = $conn->prepare("INSERT INTO penjualan (id_produk, id_pelanggan, tanggal, jumlah, total_harga, id_promosi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiid", $id_produk, $id_pelanggan, $tanggal, $jumlah, $total_harga, $id_promosi);
} else {
    $stmt = $conn->prepare("INSERT INTO penjualan (id_produk, id_pelanggan, tanggal, jumlah, total_harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisi d", $id_produk, $id_pelanggan, $tanggal, $jumlah, $total_harga);
}

$stmt->execute();
$stmt->close();

header("Location: ../pages/penjualan.php");
exit;
