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
$title = 'Ubah Barang';


require_once __DIR__ . '/../layout/header.php';

//memanggil id_barang dari URL
$id_barang = (int)$_GET['id_barang'];
$barang = select("SELECT * FROM barang WHERE id_barang = $id_barang")[0];
// Ambil daftar kategori
$kategoriList = select("SELECT * FROM kategori");



//check apakah tombol tambah ditekan
if (isset($_POST['ubah'])) {
    if (update_barang($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Diubah');
        document.location.href = 'index.php?page=inventory';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Diubah');
        document.location.href = 'index.php?page=inventory';
        </script>";
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-pen-to-square"></i> Ubah Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php?page=inventory">Data Barang</a></li>
                        <li class="breadcrumb-item active">Ubah Barang</li>
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
                            <h3 class="card-title">Form Ubah Barang</h3>
                        </div>

                        <form action="" method="post">
                            <input type="hidden" name="id_barang" value="<?= $barang['id_barang']; ?>">
                            <div class="card-body">
                                
                                <!-- Nama Barang -->
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $barang['nama']; ?>" placeholder="Nama barang..." required onkeyup="generateKode()">
                                </div>

                                <!-- Kode Barang (readonly) -->
                                <div class="form-group">
                                    <label for="kode_barang" class="form-label">Kode Barang</label>
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="<?= $barang['kode_barang']; ?>" readonly>
                                </div>

                                <!-- Kategori Barang -->
                                <div class="form-group">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select class="form-control" id="id_kategori" name="id_kategori" required onchange="generateKode()">
                                        <option value="" disabled>Pilih Kategori</option>
                                        <?php foreach ($kategoriList as $kategori) : ?>
                                            <option value="<?= $kategori['id_kategori']; ?>" 
                                                <?= ($kategori['id_kategori'] == $barang['id_kategori']) ? 'selected' : ''; ?>>
                                                <?= $kategori['nama_kategori']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Jumlah Barang -->
                                <div class="form-group">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= $barang['jumlah']; ?>" placeholder="Jumlah barang..." required>
                                </div>

                                <!-- Harga Barang -->
                                <div class="form-group">
                                    <label for="harga" class="form-label">Harga Barang</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga" name="harga" value="<?= $barang['harga']; ?>" placeholder="Harga barang..." required>
                                    </div>
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
    </section>
</div>
<script>
function generateKode() {
    let namaBarang = document.getElementById("nama").value.trim().toUpperCase();
    let kategoriSelect = document.getElementById("id_kategori");
    let kategoriText = kategoriSelect.options[kategoriSelect.selectedIndex]?.text?.trim().toUpperCase() || "";

    if (namaBarang && kategoriText) {
        let kodeKategori = kategoriText.substring(0, 3).replace(/[^A-Z]/g, ''); 
        let kodeNama = namaBarang.substring(0, 3).replace(/[^A-Z]/g, '');
        let kodeFinal = kodeKategori + '-' + kodeNama + '-001';
        
        document.getElementById("kode_barang").value = kodeFinal;
    } else {
        document.getElementById("kode_barang").value = "";
    }
}
</script>

<!-- /.content-wrapper -->
<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>