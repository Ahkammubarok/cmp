<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Batas waktu tidak aktif (dalam detik)
$timeout = 900; // 15 menit

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

// Perbarui waktu aktivitas terakhir
$_SESSION["last_activity"] = time();

include __DIR__ . '/../config/app.php'; // Pastikan koneksi database terhubung

// Cek apakah id_barang ada di URL dan valid
if (!isset($_GET['id_barang']) || empty($_GET['id_barang'])) {
    echo "<script>
            alert('ID barang tidak ditemukan!');
            document.location.href = '../index.php?page=inventory';
        </script>";
    exit;
}

// Ambil ID barang dari URL dan pastikan berupa integer
$id_barang = (int)$_GET['id_barang'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (delete_barang($id_barang) > 0) {
    echo "<script>
            alert('Data Barang Berhasil Dihapus');
            document.location.href = 'index.php?page=inventory';
        </script>";
    exit; // Tambahkan ini
} else {
    echo "<script>
            alert('Data Barang Gagal Dihapus');
            document.location.href = 'index.php?page=inventory';
        </script>";
    exit; // Tambahkan ini
}

?>
