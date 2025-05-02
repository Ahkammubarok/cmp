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
$title = 'Ubah Data Sejarah';

include '../layout/header.php';

//memanggil id_barang dari URL
$id_sejarah = (int)$_GET['id_sejarah'];

$sejarah = select("SELECT * FROM sejarah WHERE id_sejarah = $id_sejarah")[0];


//check apakah tombol tambah ditekan
if (isset($_POST['ubah'])) {
    if (update_sejarah($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Diubah');
        document.location.href = '../sejarah.php';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Diubah');
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
                    <h1 class="m-0"><i class="fa-solid fa-plus"></i> Tambah Mahasiswa</h1>
                </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../sejarah.php">Data Mahasiswa</a></li>
                        <li class="breadcrumb-item active">Tambah Mahasiswa</li>
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
                        <form action="" method="post">
                        <input type="hidden" name="id_sejarah" value="<?= $sejarah['id_sejarah']; ?>">
                        
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="judul" class="form-label">Judul</label>
                                        <input type="text" class="form-control" id="judul" name="judul" value="<?= $sejarah['judul']; ?>" placeholder="Judul sejarah..." required>
                                    </div>
                                    
                                    
                                    
                                    <div class="form-group">
                                        <label for="isi" class="form-label">Isi sejarah</label>
                                        <textarea name="isi" id="isi" required><?= $sejarah['isi']; ?></textarea>
                                    </div>
                                    
                                </div>
                                        <div class="card-footer">
                                        <button type="submit" name="ubah" class="btn btn-primary" style="float: right;">Ubah</button>
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

<!-- preview imagae sebelum di ubah -->


<?php include '../layout/footer.php'; ?>