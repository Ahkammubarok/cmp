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
$title = 'Tambah Barang';


require_once __DIR__ . '/../layout/header.php';

//check apakah tombol tambah ditekan
if (isset($_POST['tambah'])) {
    if (create_barang($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Ditambah');
        document.location.href = 'index.php?page=inventory';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Ditambah');
        document.location.href = 'index.php?page=inventory';
        </script>";
    }
}
// Ambil data kategori
$data_kategori = select("SELECT * FROM kategori ORDER BY nama_kategori ASC");

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-plus"></i> Tambah Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php?page=inventory">Data Barang</a></li>
                        <li class="breadcrumb-item active">Tambah Barang</li>
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
                            <h3 class="card-title">Form Barang</h3>
                        </div>
                        
                        <form action="" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama" name="nama" 
                                        placeholder="Nama barang..." required onkeyup="generateKodeBarang()">
                                </div>
                                <div class="form-group">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select class="form-control" id="id_kategori" name="id_kategori" required onchange="generateKodeBarang()">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($data_kategori as $kategori) : ?>
                                            <option value="<?= $kategori['id_kategori']; ?>" 
                                                    data-nama="<?= strtoupper(substr($kategori['nama_kategori'], 0, 3)); ?>">
                                                <?= $kategori['nama_kategori']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_barang" class="form-label">Kode Barang</label>
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" 
                                        placeholder="Kode barang..." readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                        placeholder="Jumlah barang..." required>
                                </div>
                                <div class="form-group">
                                    <label for="harga" class="form-label">Harga Barang</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga" name="harga" 
                                            placeholder="Harga barang..." required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer text-right">
                                <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Tambahkan Script JavaScript -->
<script>
function generateKodeBarang() {
    let nama = document.getElementById("nama").value.trim();
    let id_kategori = document.getElementById("id_kategori").value;
    let kodeBarangInput = document.getElementById("kode_barang");

    // Pastikan nama barang sudah diisi
    if (nama !== "") {
        // Jika kategori sudah dipilih, lakukan fetch
        if (id_kategori !== "") {
            fetch(`index.php?page=get_kode_barang&nama=${encodeURIComponent(nama)}&id_kategori=${id_kategori}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Response Data:", data);
                    if (data.kode_barang) {
                        kodeBarangInput.value = data.kode_barang;
                    } else {
                        kodeBarangInput.value = "Kode tidak ditemukan";
                    }
                })
                .catch(error => console.error("Error fetching kode barang:", error));
        } else {
            // Jika kategori belum dipilih, tetap tampilkan awalan kode
            let kode_nama = nama.substring(0, 3).toUpperCase();
            kodeBarangInput.value = `XXX-${kode_nama}-???`; // Placeholder sebelum kategori dipilih
        }
    } else {
        kodeBarangInput.value = ""; // Kosongkan jika belum ada nama
    }
}

</script>




<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>