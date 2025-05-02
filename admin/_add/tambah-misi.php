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
$title = 'Tambah Misi';
include '../layout/header.php';

//check apakah tombol tambah ditekan
if (isset($_POST['tambah'])) {
    if (create_misi($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Ditambah');
        document.location.href = '../sejarah.php';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Ditambah');
        document.location.href = '../sejarah.php';
        </script>";
    }
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-plus"></i> Tambah Misi</h1>
                </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../sejarah.php">Misi</a></li>
                        <li class="breadcrumb-item active">Tambah Misi</li>
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
                        <h3 class="card-title">Form Misi</h3>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="judul_misi" class="form-label">Judul Misi</label>
                                        <input type="text" class="form-control" id="judul_misi" name="judul_misi" placeholder="Judul Misi..." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="isi_misi" class="form-label">Isi Visi</label>
                                        <textarea name="isi_misi" id="isi_misi"></textarea>
                                    </div>
                                </div>
                                        <div class="card-footer">
                                        <button type="submit" name="tambah" class="btn btn-primary" style="float: right;">Tambah</button>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


</script>
<?php include '../layout/footer.php'; ?>
<script>
