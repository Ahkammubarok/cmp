<?php
// Cek apakah sesi sudah aktif sebelum memulai sesi baru
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
$title = 'Kategori Barang';

// Cek dan include file header
if (!file_exists(__DIR__ . '/../layout/header.php')) {
    die('Error: File header.php tidak ditemukan!');
} else {
    echo 'File header.php ditemukan!';
}
require_once __DIR__ . '/../layout/header.php';

// query tampil data dengan pagination
$jumlahDataPerhalaman = 5;
$jumlahData = count(select("SELECT * FROM kategori"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);
$halamanAktif = (isset($_GET['halaman']) ? $_GET['halaman'] : 1);
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;
$data_kategori = select("SELECT * FROM kategori ORDER BY id_kategori DESC LIMIT $awalData, $jumlahDataPerhalaman");
//jika tombol tambah ditekan, jalankan script berikut
if (isset($_POST['tambah'])) {
    if (create_kategori($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Ditambah');
        document.location.href = 'index.php?page=kategori';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Ditambah');
        document.location.href = 'index.php?page=kategori';
        </script>";
    }
}

//jika tombol ubah ditekan, jalankan script berikut
if (isset($_POST['ubah'])) {
    if (update_kategori($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil ubah');
        document.location.href = 'index.php?page=kategori';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal ubah');
        document.location.href = 'kindex.php?page=kategori';
        </script>";
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Kategori</h3>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <i class="fa-solid fa-square-plus"></i> Tambah
                            </button>
                            <!-- Tabel Responsif -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $no = $awalData + 1; ?>
                                    <?php foreach ($data_kategori as $kategori) : ?>
                                        <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td class="text-center"><?= $kategori['nama_kategori']; ?></td>
                                                <td class="text-center">
                                                    <?= isset($kategori['time']) && !empty($kategori['time']) ? date('d/m/Y H:i:s', strtotime($kategori['time'])) : 'Tanggal tidak tersedia'; ?>
                                                </td>
                                                <td class="project-actions text-center align-middle" width="20%">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $kategori['id_kategori']; ?>">
                                                            <i class="fas fa-pencil-alt"></i> Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $kategori['id_kategori']; ?>">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-2 justify-content-end d-flex">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <?php if ($halamanAktif > 1) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?halaman=<?= $halamanAktif - 1 ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                                            <li class="page-item <?= $i == $halamanAktif ? 'active' : '' ?>">
                                                <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($halamanAktif < $jumlahHalaman) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?halaman=<?= $halamanAktif + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <img src="/cmp/admin/assets/dist/img/logo.png" alt="Logo" height="100">
                </div>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input class="form-control" type="text" id="nama_kategori" name="nama_kategori" required placeholder="Nama Kategori">
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                        <button class="btn btn-primary" type="submit" name="tambah">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah -->
<?php foreach ($data_kategori as $kategori) : ?>
<div id="modalUbah<?= $kategori['id_kategori']; ?>" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <img src="/cmp/admin/assets/dist/img/logo.png" alt="Logo" height="100">
                </div>
                <form action="" method="post">
                    <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori']; ?>">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input class="form-control" type="text" name="nama_kategori" value="<?= $kategori['nama_kategori']; ?>" required>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                        <button class="btn btn-primary" type="submit" name="ubah">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modal Hapus -->
<?php foreach ($data_kategori as $kategori) : ?>
    <div class="modal fade" id="modalHapus<?= $kategori['id_kategori']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus kategori: <strong><?= $kategori['nama_kategori']; ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="index.php?page=delete_kategori&id_kategori=<?= $kategori['id_kategori']; ?>" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


<?php
include __DIR__ . '/../layout/footer.php';
exit;

?>