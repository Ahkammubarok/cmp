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

// Pagination
$jumlahDataPerhalaman = 3;
$jumlahData = count(select("SELECT * FROM akun"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);
$halamanAktif = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;
$data_akun = select("SELECT * FROM akun ORDER BY id_akun DESC LIMIT $awalData, $jumlahDataPerhalaman");

// Tambah Akun
if (isset($_POST['tambah'])) {
    $halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
    $message = create_akun($_POST) > 0 ? 'Data Berhasil Ditambah' : 'Data Gagal Ditambah';
    echo "<script>
            alert('$message');
            document.location.href = 'index.php?page=akun&halaman=$halaman';
            </script>";
}


// Ubah Akun
if (isset($_POST['ubah'])) {
    $halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
    $message = update_akun($_POST) > 0 ? 'Data Berhasil Diubah' : 'Data Gagal Diubah';
    echo "<script>
            alert('$message');
            document.location.href = 'index.php?page=akun&halaman=$halaman';
            </script>";
}

?>
<style>@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto; /* Biar bisa di-scroll ke samping */
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive table {
        min-width: 600px; /* Pastikan tabel tidak mengecil */
    }

    /* Agar teks dalam tabel tidak terlalu panjang */
    .table-responsive td, .table-responsive th {
        white-space: nowrap;
    }
}
</style>
<!-- Content Wrapper -->
<div class="content-wrapper">
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
                            <h3 class="card-title">Data Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <i class="fa-solid fa-square-plus"></i> Tambah
                            </button>
                            <div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = $awalData + 1; ?>
            <?php foreach ($data_akun as $akun) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $akun['nama']; ?></td>
                    <td><?= $akun['username']; ?></td>
                    <td><?= $akun['email']; ?></td>
                    <td><?= $akun['level']; ?></td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $akun['id_akun']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i> Ubah
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $akun['id_akun']; ?>">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


                            <!-- Pagination -->
                            <nav class="mt-2 d-flex justify-content-end">
                                <ul class="pagination">
                                    <?php if ($halamanAktif > 1) : ?>
                                        <li class="page-item">
                                        <a class="page-link" href="?page=akun&halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                                        <li class="page-item <?= $i == $halamanAktif ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=akun&halaman=<?= $i; ?>"><?= $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($halamanAktif < $jumlahHalaman) : ?>
                                        <li class="page-item">
                                        <a class="page-link" href="?page=akun&halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="formTambahAkun">
                    <div class="mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="6">
                        <span id="errorText" style="color: red;"></span>
                    </div>
                    <div class="mb-3">
                        <label for="level">Level</label>
                        <select name="level" id="level" class="form-control" required>
                            <option value="">-- pilih level --</option>
                            <option value="admin">Admin</option>
                            <option value="operator_barang">Operator Barang</option>
                            <option value="operator_mahasiswa">Operator Mahasiswa</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const form = document.querySelector("#formTambahAkun");
                        const password = document.getElementById("password");
                        const confirmPassword = document.getElementById("confirm_password");
                        const errorText = document.getElementById("errorText");
                        form.addEventListener("submit", function (event) {
                            if (password.value !== confirmPassword.value) {
                                event.preventDefault();
                                errorText.textContent = "Password dan Konfirmasi Password harus sama!";
                            } else {
                                errorText.textContent = "";
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah -->
<?php foreach ($data_akun as $akun) : ?>
    <div class="modal fade" id="modalUbah<?= $akun['id_akun']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Ubah Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="id_akun" value="<?= $akun['id_akun']; ?>">
                        <div class="mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= $akun['nama']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $akun['username']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $akun['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control" minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" minlength="6">
                            <span class="text-danger" id="password_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="level">Level</label>
                            <select name="level" class="form-control" required>
                                <?php $level = $akun['level']; ?>
                                <option value="admin" <?= $level == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="operator_barang" <?= $level == 'operator_barang' ? 'selected' : ''; ?>>Operator Barang</option>
                                <option value="operator_mahasiswa" <?= $level == 'operator_mahasiswa' ? 'selected' : ''; ?>>Operator Mahasiswa</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" name="ubah" class="btn btn-success">Ubah</button>
                        </div>
                    </form>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll("form").forEach(form => {
                                const passwordInput = form.querySelector("input[name='password']");
                                const confirmPasswordInput = form.querySelector("input[name='confirm_password']");
                                const passwordError = form.querySelector("#password_error");
                                form.addEventListener("submit", function (event) {
                                    // Cek apakah password diisi atau tidak
                                    if (passwordInput.value.length > 0 || confirmPasswordInput.value.length > 0) {
                                        if (passwordInput.value !== confirmPasswordInput.value) {
                                            event.preventDefault(); // Hentikan submit form
                                            passwordError.textContent = "Konfirmasi password tidak cocok!";
                                        } else {
                                            passwordError.textContent = "";
                                        }
                                    }
                                });
                                // Live check saat mengetik
                                confirmPasswordInput.addEventListener("input", function () {
                                    if (passwordInput.value !== confirmPasswordInput.value) {
                                        passwordError.textContent = "Konfirmasi password tidak cocok!";
                                    } else {
                                        passwordError.textContent = "";
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal Hapus -->
<?php foreach ($data_akun as $akun) : ?>
    <div class="modal fade" id="modalHapus<?= $akun['id_akun']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus akun: <strong><?= $akun['nama']; ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="_delete/hapus-akun.php?id_akun=<?= $akun['id_akun']; ?>" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>
