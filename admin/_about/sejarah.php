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
$title = 'Data Akun';

require_once __DIR__ . '/../layout/header.php';

// 🔹 Menghitung total data dalam tabel sejarah
$query = mysqli_query($db, "SELECT COUNT(*) AS total FROM sejarah");
// 🔹 Ambil hasil query
$data = mysqli_fetch_assoc($query);
$total_sejarah = $data['total'];

// 🔹 Menghitung total data dalam tabel visi
$query = mysqli_query($db, "SELECT COUNT(*) AS total FROM visi");
// 🔹 Ambil hasil query
$data = mysqli_fetch_assoc($query);
$total_visi = $data['total'];

// 🔹 Menghitung total data dalam tabel misi
$query = mysqli_query($db, "SELECT COUNT(*) AS total FROM misi");
// 🔹 Ambil hasil query
$data = mysqli_fetch_assoc($query);
$total_misi = $data['total'];

//menampilkan data sejarah
$data_sejarah = select("SELECT * FROM sejarah ORDER BY id_sejarah DESC");
//menampilkan data visi
$data_visi = select("SELECT * FROM visi ORDER BY id_visi DESC");
//menampilkan data misi
$data_misi = select("SELECT * FROM misi ORDER BY id_misi DESC");
?>

<style>@media (max-width: 576px) { 
    .btn-sm i {
        font-size: 12px; /* Perkecil ikon di HP */
    }
    .btn-sm {
        padding: 4px 6px; /* Kurangi padding agar lebih kecil */
    }
}
</style>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 d-flex justify-content-between align-items-center">
                <div class="col-md-6 col-12">
                    <h1 class="m-0">Sejarah, Visi dan Misi</h1>
                </div>
                <div class="col-md-6 col-12 text-md-right text-center">
                    <ol class="breadcrumb float-md-right justify-content-center">
                        <li class="breadcrumb-item active">Total Data</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small Boxes -->
            <div class="row justify-content-center">
                <!-- Box Sejarah -->
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Sejarah</h3>
                            <p>Total Data: <?= $total_sejarah; ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <a href="_add/tambah-sejarah.php" class="small-box-footer">Tambah Data <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <!-- Box Visi -->
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Visi</h3>
                            <p>Total Data: <?= $total_visi; ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <a href="_add/tambah-visi.php" class="small-box-footer">Tambah Data <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <!-- Box Misi -->
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Misi</h3>
                            <p>Total Data: <?= $total_misi; ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <a href="_add/tambah-misi.php" class="small-box-footer">Tambah Data <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- Data Tables -->
            <?php $tables = [['Sejarah', 'sejarah'], ['Visi', 'visi'], ['Misi', 'misi']]; ?>
            <?php foreach ($tables as [$title, $var]) : ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data <?= $title; ?></h3>
                        </div>
                        <!-- Tambahkan class "table-responsive" di dalam card-body -->
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Judul Sejarah</th>
                                        <th class="text-center">Isi Sejarah</th>
                                        <th class="text-center">Tanggal Update</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1; ?>
                                    <?php foreach ($data_sejarah as $sejarah) : ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-truncate" style="max-width: 150px;"><?= $sejarah['judul']; ?></td>
                                            <td class="text-truncate" style="max-width: 200px;"><?= $sejarah['isi']; ?></td>
                                            <td class="text-center"><?= date('d/m/Y H:i:s', strtotime($sejarah['time'])); ?></td>
                                            <td class="text-center">
                                                <a href="_edit/ubah-sejarah.php?id_sejarah=<?= $sejarah['id_sejarah']; ?>" 
                                                class="btn btn-success btn-sm me-1">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#modalHapus<?= $sejarah['id_sejarah']; ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<!-- /.content-wrapper -->
<!-- Modal Hapus Sejarah -->
<!-- Modal Hapus untuk Tabel Sejarah -->
<?php foreach ($data_sejarah as $sejarah) : ?>
    <div class="modal fade" id="modalHapus<?= $sejarah['id_sejarah']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $sejarah['id_sejarah']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusLabel<?= $sejarah['id_sejarah']; ?>">Hapus Sejarah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus sejarah: <strong><?= htmlspecialchars($sejarah['judul']); ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="_delete/hapus-sejarah.php?id_sejarah=<?= $sejarah['id_sejarah']; ?>" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal Hapus Visi -->
<!-- Modal Hapus untuk Tabel Visi -->
<?php foreach ($data_visi as $visi) : ?>
    <div class="modal fade" id="modalHapusVisi<?= $visi['id_visi']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $visi['id_visi']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusLabel<?= $visi['id_visi']; ?>">Hapus Visi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Visi: <strong><?= htmlspecialchars($visi['judul_visi']); ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="_delete/hapus-visi.php?id_visi=<?= $visi['id_visi']; ?>" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal Hapus Misi -->
<!-- Modal Hapus untuk Tabel Misi -->
<?php foreach ($data_misi as $misi) : ?>
    <div class="modal fade" id="modalHapusMisi<?= $misi['id_misi']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $misi['id_misi']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusLabel<?= $misi['id_misi']; ?>">Hapus Misi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Misi: <strong><?= htmlspecialchars($misi['judul_misi']); ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="_delete/hapus-misi.php?id_misi=<?= $misi['id_misi']; ?>" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>
