
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Batas waktu tidak aktif (dalam detik)
$timeout = 900; // 15 menit

// Cek apakah pengguna sudah login
if (!isset($_SESSION["login"])) {
    echo "<script>
            alert('Silakan login terlebih dahulu');
            document.location.href = 'login.php';
        </script>";
    exit;
}

// Perbarui waktu aktivitas terakhir
$_SESSION["last_activity"] = time();

// Konfigurasi aplikasi
require_once 'config/controller.php';

// ✅ Pindahkan $page_map ke atas sebelum digunakan
$page_map = [
    'index'      => 'index.php',
    'inventory'  => '_barang/inventory.php',
                'add_barang'        => '_add/tambah-barang.php',
                'get_kode_barang'   => 'ajax/get_kode_barang.php',
                'edit_barang'       => '_edit/ubah-barang.php',
                'delete_barang'     => '_delete/hapus-barang.php',
    'kategori'   => '_barang/kategori.php',
                'delete_kategori'   => '_delete/hapus-kategori.php',
    'mahasiswa'  => '_mahasiswa/mahasiswa.php',
                'add_mahasiswa'        => '_add/tambah-mahasiswa.php',
                'edit_mahasiswa'       => '_edit/ubah-mahasiswa.php',
                'delete_mahasiswa'     => '_delete/hapus-mahasiswa.php',
                'detail_mahasiswa'     => '_mahasiswa/detail-mahasiswa.php',
                'download_mahasiswa'     => '_mahasiswa/download-excel-mahasiswa.php',
    'akun'       => '_users/akun.php',
    'email'      => '_about/email.php',
    'sejarah'    => '_about/sejarah.php',
    'login'      => 'login.php'
];

// ✅ Sekarang $page_map sudah terdefinisi sebelum dipakai di sini
if (isset($_GET['page']) && $_GET['page'] === 'get_kode_barang') {
    require_once $page_map['get_kode_barang'];
    exit;
}

// Ambil parameter page dengan validasi
$page = $_GET['page'] ?? 'index';

// Pastikan halaman valid, jika tidak, arahkan ke dashboard
if (!array_key_exists($page, $page_map)) {
    $page = 'index';
}

// Tentukan judul halaman
$title = ucfirst($page);

// Include header sebelum konten halaman
require_once 'layout/header.php';

// Cek apakah file ada sebelum di-include
if (file_exists($page_map[$page])) {
    require_once $page_map[$page];
} else {
    echo "<h1>Error 404: Halaman tidak ditemukan.</h1>";
}


?>





<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>
                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
if (!file_exists('layout/footer.php')) {
    die("Error: File 'layout/footer.php' tidak ditemukan.");
}
require_once 'layout/footer.php';
?>
