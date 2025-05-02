<?php
// Cek apakah sesi sudah dimulai sebelum memanggil session_start()
if (session_status() == PHP_SESSION_NONE) {
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
$title = 'Detail Mahasiswa';


require_once __DIR__ . '/../layout/header.php';

// Mengambil id mahasiswa dari URL
$id_mahasiswa = (int)$_GET['id_mahasiswa'];

// Menampilkan data mahasiswa
$mahasiswa = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];
?>

<!-- Pindahkan CSS ke <head> -->
<style>
   /* Overlay untuk zoom gambar */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Pastikan lebih tinggi dari elemen lain */
}

/* Gambar dalam overlay */
.overlay img {
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
}

/* Tombol Close */
.close-btn {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 30px;
    color: white;
    cursor: pointer;
    background: rgba(0, 0, 0, 0.5);
    padding: 5px 10px;
    border-radius: 5px;
}

.close-btn:hover {
    background: red;
}

/* Menyembunyikan elemen notifikasi saat overlay aktif */
.overlay-active .navbar, 
.overlay-active .sidebar, 
.overlay-active .header {
    pointer-events: none; /* Tidak bisa diklik */
    opacity: 0.3; /* Membuatnya samar */
}
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <a href="index.php?page=mahasiswa" class="btn btn-success btn-sm btn-back">Back</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <!-- <a href="index.php?page=mahasiswa" class="btn btn-secondary btn-sm btn-back">Back</a> -->
                        <li class="breadcrumb-item active">Data Mahasiswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Mahasiswa</h3>
                        </div>
                        <table class="table table-bordered table-striped mt-3">
                            <tr>
                                <td>Nama</td>
                                <td>: <?= $mahasiswa['nama']; ?></td>
                            </tr>
                            <tr>
                                <td>Prodi</td>
                                <td>: <?= $mahasiswa['prodi']; ?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>: <?= $mahasiswa['jk']; ?></td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>: <?= $mahasiswa['telepon']; ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: <?= $mahasiswa['alamat']; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: <?= $mahasiswa['email']; ?></td>
                            </tr>
                            <tr>
                                <td width="50%">Foto</td>
                                <td>
                                    <a href="javascript:void(0);" onclick="zoomImage('assets/img/<?= $mahasiswa['foto']; ?>')">
                                        <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="foto" width="50%">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Overlay Zoom -->
<div class="overlay" id="imageOverlay">
    <span class="close-btn" onclick="closeZoom()">&times;</span>
    <img id="zoomedImage" src="" alt="Zoomed Image">
</div>

<!-- JavaScript diletakkan sebelum </body> -->
<script>
    function zoomImage(src) {
    let overlay = document.getElementById("imageOverlay");
    let zoomedImage = document.getElementById("zoomedImage");

    zoomedImage.src = src;
    overlay.style.display = "flex";

    // Tambahkan class ke <body> untuk menyembunyikan elemen lain
    document.body.classList.add("overlay-active");
}

function closeZoom() {
    document.getElementById("imageOverlay").style.display = "none";

    // Hapus class overlay-active saat ditutup
    document.body.classList.remove("overlay-active");
}

</script>

<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>
