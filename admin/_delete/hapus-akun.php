<?php
session_start();

// Batas waktu tidak aktif (dalam detik)
$timeout = 900; // 2 menit

// Cek apakah pengguna sudah login
if (!isset($_SESSION["login"])) {
    echo "<script>
            alert('Silakan login terlebih dahulu');
            document.location.href = '../login.php';
        </script>";
    exit;
}

// Cek apakah ada aktivitas terakhir
if (isset($_SESSION["last_activity"])) {
    $inactive_time = time() - $_SESSION["last_activity"];
    
    if ($inactive_time > $timeout) {
        session_unset(); // Hapus semua variabel sesi
        session_destroy(); // Hancurkan sesi

        echo "<script>
                alert('Sesi telah habis, silakan login kembali');
                document.location.href = '../login.php';
            </script>";
        exit;
    }
}
include '../config/app.php';

//menerima id barang yang dipilih

$id_akun = (int)$_GET['id_akun'];

if (delete_akun($id_akun) > 0) {
    echo "<script>
            alert('Data Barang Berhasil Dihapus');
            document.location.href = '../index.php?page=akun';
            </script>";
} else {
    echo "<script>
            alert('Data Barang Gagal Dihapus');
            document.location.href = '../index.php?=akun';
            </script>";
}
?>