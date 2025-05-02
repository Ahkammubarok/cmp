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
$title = 'Data Barang';


require_once __DIR__ . '/../layout/header.php';




// query tampil data dengan pagination
$jumlahDataPerhalaman = 5;
$jumlahData = count(select("SELECT * FROM barang"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);
$halamanAktif = (isset($_GET['halaman']) ? $_GET['halaman'] : 1);
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;
// 
$data_barang = select("SELECT barang.*, kategori.nama_kategori 
                    FROM barang 
                    JOIN kategori ON barang.id_kategori = kategori.id_kategori 
                    ORDER BY barang.id_barang DESC 
                    LIMIT $awalData, $jumlahDataPerhalaman");

?>
<!-- Table Wrapper -->
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
                            <h3 class="card-title">Data Barang</h3>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <a href="index.php?page=add_barang" class="btn btn-primary mb-2">
                                <i class="fa-solid fa-square-plus"></i> Tambah
                            </a>
                            <!-- <a href="../download-excel-mahasiswa.php" class="btn btn-success mb-2">
                                <i class="fa-solid fa-file-excel"></i> Download
                            </a> -->

                            <!-- Search Bar -->
                            <div class="d-flex justify-content-end align-items-center mb-2">
                                <label for="searchInput" class="mr-2 mb-0" style="font-size: 14px;">Cari:</label>
                                <input type="text" id="searchInput" class="form-control form-control-sm w-20" 
                                    style="max-width: 180px;" placeholder="Nama barang..." onkeyup="searchTable()">
                            </div>

                            <!-- Tabel Responsif -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Kode Barang</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_barang as $barang) : ?>
                                            <tr>
                                                <td class="text-center"><?= $awalData += 1; ?></td>
                                                <td class="text-center"><?= $barang['kode_barang']; ?></td>
                                                <td class="text-center"><?= $barang['nama']; ?></td>
                                                <td class="text-center"><?= $barang['nama_kategori']; ?></td>
                                                <td class="text-center"><?= $barang['jumlah']; ?></td>
                                                <td class="text-center">Rp. <?= number_format($barang['harga'], 0, ',', '.'); ?></td>
                                                <td class="text-center"><?= date('d/m/Y H:i:s', strtotime($barang['tanggal'])); ?></td>
                                                <td class="project-actions text-center align-middle" width="20%">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a class="btn btn-info btn-sm" href="index.php?page=edit_barang&id_barang=<?= $barang['id_barang']; ?>">
                                                            <i class="fas fa-pencil-alt"></i> Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-sm" href="index.php?page=delete_barang&id_barang=<?= $barang['id_barang']; ?>" 
                                                        onclick="return confirm('Apakah anda akan menghapus...?')">
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
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <?php if ($halamanAktif > 1) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?halaman=<?= $halamanAktif - 1 ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                                            <?php if ($i == $halamanAktif) : ?>
                                                <li class="page-item active"><a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a></li>
                                            <?php else : ?>
                                                <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a></li>
                                            <?php endif; ?>
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

<!-- CSS Responsif -->
<style>
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        /* Mengurangi padding pada tombol di HP */
        .btn {
            padding: 6px 10px;
            font-size: 14px;
        }

        /* Menyesuaikan ukuran teks pada tabel */
        .table th, .table td {
            font-size: 14px;
        }
    }
</style>

<!-- JavaScript untuk Pencarian -->
<script>
    function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector(".table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2]; // Kolom Nama Barang
            if (td) {
                txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }
</script>


<script>
function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector("table"); // Pastikan sesuai dengan tabel data
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) { // Mulai dari 1 agar tidak mengubah header
        td = tr[i].getElementsByTagName("td");
        let found = false;
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}
</script>

<!-- /.content-wrapper -->
<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>