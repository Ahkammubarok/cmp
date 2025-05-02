<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
$title = 'Ubah Mahasiswa';


require_once __DIR__ . '/../layout/header.php';

if (isset($_POST['ubah'])) {
    if (update_mahasiswa($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Diubah');
        window.location.href = 'index.php?page=mahasiswa';
        </script>";
        exit();
    } else {
        echo "<script>
        alert('Data Gagal Diubah atau Tidak Ada Perubahan');
        window.location.href = 'index.php?page=mahasiswa';
        </script>";
        exit();
    }
}

//ambil id mahasiswa dari url
$id_mahasiswa = (int)$_GET['id_mahasiswa'];

//query ambil data mahasiswa
$mahasiswa = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];
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
                        <li class="breadcrumb-item"><a href="index.php?page=mahasiswa">Data Mahasiswa</a></li>
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
                        <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_mahasiswa" value="<?= $mahasiswa['id_mahasiswa']; ?>">
                        <input type="hidden" name="fotolama" value="<?= $mahasiswa['foto']; ?>">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama Mahasiswa</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama mahasiswa..." required value="<?= $mahasiswa['nama']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="prodi">Program Studi</label>
                                        <select name="prodi" id="prodi" class="form-control custom-select" required>
                                            <?php $prodi = $mahasiswa['prodi']; ?>
                                            <option value="Teknik Informatika" <?= $prodi == 'Teknik Informatika' ? 'selected' : null ?>>Teknik Informatika</option>
                                            <option value="Sistem Informasi" <?= $prodi == 'Sistem Informasi' ? 'selected' : null ?>>Sistem Informasi</option>
                                            <option value="Teknik Mesin" <?= $prodi == 'Teknik Mesin' ? 'selected' : null ?>>Teknik Mesin</option>
                                            <option value="Teknik Listrik" <?= $prodi == 'Teknik Listrik' ? 'selected' : null ?>>Teknik Listrik</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jk">Jenis Kelamin</label>
                                        
                                        <select name="jk" id="jk" class="form-control custom-select" required>
                                            <?php $jk = $mahasiswa['jk']; ?>
                                            <option value="Laki-laki" <?= $jk == 'Laki-laki' ? 'selected' : null ?>>Laki-laki</option>
                                            <option value="Perempuan" <?= $jk == 'Perempuan' ? 'selected' : null ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="telepon" class="form-label">No Telepon</label>
                                        <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Nomor telepon..." required value="<?= $mahasiswa['telepon']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat" required><?= $mahasiswa['alamat']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email..." required value="<?= $mahasiswa['email']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" class="form-control" id="foto" name="foto" placeholder="Foto..." onchange="previewImg()">
                                        <img src="/cmp/admin/assets/img/<?= $mahasiswa['foto']; ?>" alt="" class="img-thumbnail img-preview mt-2" width="100px">
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
<script>
    function previewImg() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('.img-preview');

        const fileFoto = new FileReader();
        fileFoto.readAsDataURL(foto.files[0]);

        fileFoto.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>

<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>