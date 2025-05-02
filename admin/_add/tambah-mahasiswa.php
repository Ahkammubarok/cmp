<?php
// Cek apakah sesi sudah dimulai sebelum memanggil session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = 'Tambah Mahasiswa';
require_once __DIR__ . '/../layout/header.php';

// Pastikan hanya memproses data jika tombol tambah ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['tambah'])) {
    if (create_mahasiswa($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Ditambah');
        document.location.href = 'index.php?page=mahasiswa';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Ditambah');
        document.location.href = 'index.php?page=tambah-mahasiswa';
        </script>";
    }
}
?>

<div class="content-wrapper">
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Mahasiswa</h3>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" id="formMahasiswa">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama Mahasiswa</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama mahasiswa..." required>
                                </div>
                                <div class="form-group">
                                    <label for="prodi">Program Studi</label>
                                    <select name="prodi" id="prodi" class="form-control custom-select" required>
                                        <option selected disabled value="">Pilih Program Studi</option>
                                        <option value="Teknik Informatika">Teknik Informatika</option>
                                        <option value="Sistem Informasi">Sistem Informasi</option>
                                        <option value="Teknik Mesin">Teknik Mesin</option>
                                        <option value="Teknik Listrik">Teknik Listrik</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jk">Jenis Kelamin</label>
                                    <select name="jk" id="jk" class="form-control custom-select" required>
                                        <option selected disabled value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="telepon" class="form-label">No Telepon</label>
                                    <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Nomor telepon..." required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" id="alamat" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email..." required>
                                </div>
                                <div class="form-group">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="foto" name="foto" onchange="previewImg()">
                                    <img src="" alt="" class="img-thumbnail img-preview mt-2" width="100px">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="tambah" class="btn btn-primary float-right">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Validasi Form sebelum submit
document.querySelector("#formMahasiswa").addEventListener("submit", function(event) {
    const foto = document.querySelector("#foto");

    if (foto.files.length > 0) {
        const file = foto.files[0];
        const allowedExtensions = ["jpg", "jpeg", "png"];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert("Format file tidak valid. Hanya JPG, JPEG, atau PNG yang diperbolehkan!");
            event.preventDefault();
            return;
        }

        if (file.size > 2000000) {
            alert("Ukuran file terlalu besar! Maksimal 2MB.");
            event.preventDefault();
            return;
        }
    }
});

// Preview gambar sebelum upload
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

<?php include __DIR__ . '/../layout/footer.php'; ?>
