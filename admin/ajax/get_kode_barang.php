<?php
require_once __DIR__ . '/../config/database.php';
header("Content-Type: application/json");

// Cek apakah parameter diterima
if (!isset($_GET['nama']) || !isset($_GET['id_kategori'])) {
    echo json_encode(["error" => "Parameter tidak lengkap"]);
    exit;
}

$nama = htmlspecialchars($_GET['nama']);
$id_kategori = htmlspecialchars($_GET['id_kategori']);

// Ambil nama kategori berdasarkan id_kategori
$queryKategori = "SELECT nama_kategori FROM kategori WHERE id_kategori = ?";
$stmtKategori = mysqli_prepare($db, $queryKategori);
mysqli_stmt_bind_param($stmtKategori, "s", $id_kategori);
mysqli_stmt_execute($stmtKategori);
$resultKategori = mysqli_stmt_get_result($stmtKategori);

if (!$resultKategori || mysqli_num_rows($resultKategori) == 0) {
    echo json_encode(["error" => "Kategori tidak ditemukan"]);
    exit;
}

$kategori = mysqli_fetch_assoc($resultKategori);
$kode_kategori = strtoupper(substr($kategori['nama_kategori'], 0, 3));

// Buat kode barang dari nama barang (3 huruf pertama)
$kode_nama = strtoupper(substr($nama, 0, 3));

// Cek nomor urut terbaru
$queryNomorUrut = "SELECT COUNT(*) AS jumlah FROM barang WHERE kode_barang LIKE ?";
$kodePattern = "$kode_kategori-$kode_nama-%";
$stmtNomorUrut = mysqli_prepare($db, $queryNomorUrut);
mysqli_stmt_bind_param($stmtNomorUrut, "s", $kodePattern);
mysqli_stmt_execute($stmtNomorUrut);
$resultNomorUrut = mysqli_stmt_get_result($stmtNomorUrut);
$row = mysqli_fetch_assoc($resultNomorUrut);
$nomor_urut = str_pad($row['jumlah'] + 1, 3, '0', STR_PAD_LEFT);

$kode_barang = "$kode_kategori-$kode_nama-$nomor_urut";

// Kirim response dalam format JSON
echo json_encode(["kode_barang" => $kode_barang]);
?>
