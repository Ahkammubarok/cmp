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

//menerima id mahasiswa yang dipilih

$id_visi = (int)$_GET['id_visi'];

if (delete_visi($id_visi) > 0) {
    echo "<script>
            alert('Data Visi Berhasil Dihapus');
            document.location.href = '../sejarah.php';
            </script>";
} else {
    echo "<script>
            alert('Data Visi Gagal Dihapus');
            document.location.href = '../sejarah.php';
            </script>";
}

?>