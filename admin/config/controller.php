<?php

//fungsi menampilkan
function select($query)
{
    //panggil koneksi database
    global $db;

    $result = mysqli_query($db, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}
// Fungsi tambah data barang dengan kode unik berdasarkan kategori dan nama barang
function create_barang($data) {
    global $db;

    // Aktifkan error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $nama = htmlspecialchars($data["nama"]);
    $id_kategori = htmlspecialchars($data["id_kategori"]);
    $jumlah = htmlspecialchars($data["jumlah"]);
    $harga = htmlspecialchars($data["harga"]);

    // Ambil nama kategori berdasarkan ID
    $queryKategori = "SELECT nama_kategori FROM kategori WHERE id_kategori = '$id_kategori'";
    $resultKategori = mysqli_query($db, $queryKategori);

    if (!$resultKategori || mysqli_num_rows($resultKategori) == 0) {
        die("Kategori tidak ditemukan!");
    }

    $rowKategori = mysqli_fetch_assoc($resultKategori);
    $kode_kategori = strtoupper(substr($rowKategori['nama_kategori'], 0, 3)); // Ambil 3 huruf pertama kategori

    // Ambil 3 huruf pertama nama barang
    $kode_barang = strtoupper(substr($nama, 0, 3));
    $kode_barang = preg_replace('/[^A-Z]/', '', $kode_barang); // Hanya huruf

    // Pastikan panjang kode minimal 3 huruf
    if (strlen($kode_barang) < 3) {
        $kode_barang = str_pad($kode_barang, 3, 'X'); // Tambahkan 'X' jika kurang dari 3 huruf
    }

    // Cek jumlah barang dengan kode kategori + kode barang
    $query = "SELECT COUNT(*) AS jumlah FROM barang WHERE kode_barang LIKE '$kode_kategori-$kode_barang%'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $nomor_urut = str_pad($row['jumlah'] + 1, 3, '0', STR_PAD_LEFT);

    // Format kode unik: KATEGORI-NAMA-001 (misalnya: LTP-LEN-001)
    $kode_barang_final = "$kode_kategori-$kode_barang-$nomor_urut";

    // Query insert data ke database
    $queryInsert = "INSERT INTO barang (kode_barang, nama, id_kategori, jumlah, harga) 
                    VALUES ('$kode_barang_final', '$nama', '$id_kategori', '$jumlah', '$harga')";

    $insert = mysqli_query($db, $queryInsert);

    // Cek apakah query berhasil
    if (!$insert) {
        die("Error saat insert data: " . mysqli_error($db)); // Tampilkan error MySQL
    }

    return mysqli_affected_rows($db);
}
// Fungsi ubah data barang
function update_barang($post) {
    global $db;

    $id_barang   = mysqli_real_escape_string($db, $post['id_barang']);
    $nama        = mysqli_real_escape_string($db, $post['nama']);
    $id_kategori = mysqli_real_escape_string($db, $post['id_kategori']);
    $jumlah      = mysqli_real_escape_string($db, $post['jumlah']);
    $harga       = mysqli_real_escape_string($db, $post['harga']);

    // Ambil nama kategori berdasarkan ID
    $queryKategori = "SELECT nama_kategori FROM kategori WHERE id_kategori = '$id_kategori'";
    $resultKategori = mysqli_query($db, $queryKategori);
    $kategori = mysqli_fetch_assoc($resultKategori);
    $nama_kategori = strtoupper(substr($kategori['nama_kategori'], 0, 3));

    // Generate kode barang baru
    $kode_barang = strtoupper(substr($nama, 0, 3)); // Ambil 3 huruf pertama nama barang
    $kode_barang = preg_replace('/[^A-Z]/', '', $kode_barang); // Hanya huruf

    // Cek jumlah barang dengan kategori & nama yang sama di database
    $queryCount = "SELECT COUNT(*) AS jumlah FROM barang WHERE kode_barang LIKE '$nama_kategori-$kode_barang%'";
    $resultCount = mysqli_query($db, $queryCount);
    $rowCount = mysqli_fetch_assoc($resultCount);
    $nomor_urut = str_pad($rowCount['jumlah'] + 1, 3, '0', STR_PAD_LEFT); // Urutan

    // Bentuk kode barang final dengan tanda "-"
    $kode_barang = $nama_kategori . "-" . $kode_barang . "-" . $nomor_urut;

    // Query update data barang
    $query = "UPDATE barang 
            SET nama = '$nama', id_kategori = '$id_kategori', kode_barang = '$kode_barang', jumlah = '$jumlah', harga = '$harga' 
            WHERE id_barang = '$id_barang'";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// Fungsi menghapus data barang
function delete_barang($id_barang)
{
    global $db;

    $id_barang = mysqli_real_escape_string($db, $id_barang);

    // Cek apakah barang dengan id ini ada
    $cek = mysqli_query($db, "SELECT * FROM barang WHERE id_barang = '$id_barang'");
    if (mysqli_num_rows($cek) == 0) {
        return 0; // Barang tidak ditemukan
    }

    // Hapus barang jika ditemukan
    $query = "DELETE FROM barang WHERE id_barang = ?";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_barang);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_affected_rows($db);
    } else {
        die("Error: " . mysqli_error($db));
    }
}

//fungsi menambahkan data kategori
function create_kategori($post)
{
    global $db;

    $nama_kategori = $post['nama_kategori'];

    //query tambah data
    $query = "INSERT INTO kategori VALUES(null, '$nama_kategori', CURRENT_TIMESTAMP())";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi ubah data kategori

function update_kategori($post)
{
    global $db;
    $id_kategori    = $post['id_kategori'];
    $nama_kategori  = $post['nama_kategori'];

    //query ubah data
    $query = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = $id_kategori";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//Fungsi menghapus data kategori

function delete_kategori($id_kategori)
{
    global $db;

    //query hapus data kategori
    $query = "DELETE FROM kategori WHERE id_kategori = $id_kategori";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}


//fungsi tambah mahasiswa
function create_mahasiswa($post)
{
    global $db;

    $nama = mysqli_real_escape_string($db, htmlspecialchars($post['nama']));
    $prodi = mysqli_real_escape_string($db, htmlspecialchars($post['prodi']));
    $jk = mysqli_real_escape_string($db, htmlspecialchars($post['jk']));
    $telepon = mysqli_real_escape_string($db, htmlspecialchars($post['telepon']));
    $alamat = mysqli_real_escape_string($db, htmlspecialchars($post['alamat']));
    $email = mysqli_real_escape_string($db, htmlspecialchars($post['email']));

    // Upload file, tetapi jika tidak ada file maka gunakan default
    $foto = upload_file();
    if ($foto === 'invalid_format') {
        echo "<script>alert('Format file tidak valid. Hanya JPG, JPEG, atau PNG yang diperbolehkan!');</script>";
        return false;
    } elseif ($foto === 'too_large') {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.');</script>";
        return false;
    } elseif ($foto === 'upload_failed') {
        echo "<script>alert('Gagal mengupload file! Silakan coba lagi.');</script>";
        return false;
    }

    if ($foto === false || $foto === '') {
        $foto = 'default.jpg'; // Bisa gunakan gambar default
    }

    // Cek nama kolom yang benar dalam database
    $query = "INSERT INTO mahasiswa (nama, prodi, jk, telepon, alamat, email, foto) 
              VALUES ('$nama', '$prodi', '$jk', '$telepon', '$alamat', '$email', '$foto')";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}


//Fungsi Ubah Mahasiswa
// Fungsi Ubah Mahasiswa
function update_mahasiswa($data)
{
    global $db;

    $id_mahasiswa = $data['id_mahasiswa'];
    $nama = htmlspecialchars($data['nama']);
    $prodi = htmlspecialchars($data['prodi']);
    $jk = htmlspecialchars($data['jk']);
    $telepon = htmlspecialchars($data['telepon']);
    $alamat = htmlspecialchars($data['alamat']);
    $email = htmlspecialchars($data['email']);

    // Ambil foto lama dari form
    $fotolama = $data['fotolama'];

    // Cek apakah ada file yang diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Jika ada file baru, upload dan ganti foto lama
        $namaFoto = upload_file();

        // Hapus foto lama dari folder jika ada
        if ($fotolama && file_exists(__DIR__ . '/../assets/img/' . $fotolama)) {
            unlink(__DIR__ . '/../assets/img/' . $fotolama);
        }
    } else {
        // Jika tidak ada file baru, gunakan foto lama
        $namaFoto = $fotolama;
    }

    // Update database dengan foto baru atau foto lama
    $query = "UPDATE mahasiswa SET 
                nama = '$nama',
                prodi = '$prodi',
                jk = '$jk',
                telepon = '$telepon',
                alamat = '$alamat',
                email = '$email',
                foto = '$namaFoto'
            WHERE id_mahasiswa = $id_mahasiswa";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// Fungsi upload file
function upload_file()
{
    // Pastikan ada file yang diunggah
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        echo "<script>
                alert('Harap pilih file foto sebelum mengunggah');
                document.location.href = 'index.php?page=mahasiswa';
            </script>";
        die();
    }

    $namaFile   = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error      = $_FILES['foto']['error'];
    $tmpName    = $_FILES['foto']['tmp_name'];

    // Cek apakah terjadi error
    if ($error !== UPLOAD_ERR_OK) {
        echo "<script>alert('Terjadi kesalahan saat mengunggah file!');</script>";
        var_dump(error_get_last());
        die();
    }

    // Ekstensi yang diperbolehkan
    $extensifileValid = ['jpg', 'jpeg', 'png'];
    $extensifile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    // Cek format file
    if (!in_array($extensifile, $extensifileValid)) {
        echo "<script>
                alert('Format file tidak valid! Hanya JPG, JPEG, atau PNG yang diperbolehkan');
                document.location.href = 'index.php?page=mahasiswa';
            </script>";
        die();
    }

    // Cek ukuran file (maksimal 2MB)
    if ($ukuranFile > 2048000) {
        echo "<script>
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                document.location.href = 'index.php?page=mahasiswa';
            </script>";
        die();
    }

    // Buat nama file baru agar unik
    $namaFileBaru = uniqid() . '.' . $extensifile;

    // Tentukan folder tujuan
    $targetDir = __DIR__ . '/../assets/img/';
    $targetPath = $targetDir . $namaFileBaru;

    // Pastikan folder tujuan ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($tmpName, $targetPath)) {
        return $namaFileBaru;
    } else {
        echo "<script>alert('Upload gagal! Cek permission folder.');</script>";
        var_dump(error_get_last());
        die();
    }
}





//Fungsi menghapus data mahasiswa

function delete_mahasiswa($id_mahasiswa)
{
    global $db;

    // Pastikan ID adalah angka untuk menghindari SQL Injection
    $id_mahasiswa = intval($id_mahasiswa);

    // Ambil data mahasiswa untuk mendapatkan nama file foto
    $query = $db->prepare("SELECT foto FROM mahasiswa WHERE id_mahasiswa = ?");
    $query->bind_param("i", $id_mahasiswa);
    $query->execute();
    $result = $query->get_result();
    $foto = $result->fetch_assoc();

    if ($foto && !empty($foto['foto'])) {
        $foto_path = __DIR__ . "/assets/img/" . $foto['foto'];

        // Cek apakah file foto ada sebelum menghapusnya
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    // Query hapus data mahasiswa
    $delete_query = $db->prepare("DELETE FROM mahasiswa WHERE id_mahasiswa = ?");
    $delete_query->bind_param("i", $id_mahasiswa);
    $delete_query->execute();

    // Kembalikan jumlah baris yang terpengaruh
    return $delete_query->affected_rows;
}


//tambah akun
function create_akun($post)
{
    global $db;

    $nama = mysqli_real_escape_string($db, trim($post['nama']));
    $username = mysqli_real_escape_string($db, trim($post['username']));
    $email = mysqli_real_escape_string($db, trim($post['email']));
    $password = trim($post['password']);
    $confirm_password = trim($post['confirm_password']);
    $level = mysqli_real_escape_string($db, trim($post['level']));

    // Pastikan level yang dipilih sesuai dengan ENUM di database
    $allowed_levels = ['admin', 'operator_barang', 'operator_mahasiswa'];
    if (!in_array($level, $allowed_levels)) {
        echo "<script>
                alert('Level tidak valid!');
                document.location.href = 'akun.php';
            </script>";
        return 0;
    }

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>
                alert('Konfirmasi password tidak cocok!');
                document.location.href = 'akun.php';
            </script>";
        return 0;
    }

    // Enkripsi password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Query tambah data
    $query = "INSERT INTO akun (nama, username, email, password, level) 
                VALUES ('$nama', '$username', '$email', '$passwordHash', '$level')";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi update akun
function update_akun($post)
{
    global $db;

    $id_akun = mysqli_real_escape_string($db, $post['id_akun']);
    $nama = mysqli_real_escape_string($db, $post['nama']);
    $username = mysqli_real_escape_string($db, $post['username']);
    $email = mysqli_real_escape_string($db, $post['email']);
    $level = mysqli_real_escape_string($db, $post['level']);
    $password = trim($post['password']);
    $confirm_password = trim($post['confirm_password']);

    // Pastikan level yang dipilih sesuai dengan ENUM di database
    $allowed_levels = ['admin', 'operator_barang', 'operator_mahasiswa'];
    if (!in_array($level, $allowed_levels)) {
        echo "<script>
                alert('Level tidak valid!');
                document.location.href = 'akun.php';
            </script>";
        return 0;
    }

    // Jika password diisi, cek konfirmasinya
    if (!empty($password)) {
        if ($password !== $confirm_password) {
            echo "<script>
                    alert('Konfirmasi password tidak cocok!');
                    document.location.href = 'akun.php';
                </script>";
            return 0;
        }
        // Enkripsi password baru
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Query update dengan password baru
        $query = "UPDATE akun SET 
                    nama = '$nama', 
                    username = '$username', 
                    email = '$email', 
                    password = '$passwordHash', 
                    level = '$level' 
                WHERE id_akun = '$id_akun'";
    } else {
        // Query update tanpa mengubah password
        $query = "UPDATE akun SET 
                    nama = '$nama', 
                    username = '$username', 
                    email = '$email', 
                    level = '$level' 
                WHERE id_akun = '$id_akun'";
    }

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi menghapus data akun

function delete_akun($id_akun)
{
    global $db;



    //query hapus data akun
    $query = "DELETE FROM akun WHERE id_akun = $id_akun";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi menambahkan sejarah
function create_sejarah($post)
{
    global $db;

    $judul = $post['judul'];
    $isi = $post['isi'];

    //query tambah data
    $query = "INSERT INTO sejarah VALUES(null, '$judul', '$isi', CURRENT_TIMESTAMP())";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi ubah data sejarah
function update_sejarah($post)
{
    global $db;
    $id_sejarah  = $post['id_sejarah'];
    $judul      = $post['judul'];
    $isi     = $post['isi'];

    //query ubah data
    $query = "UPDATE sejarah SET judul = '$judul', isi = '$isi' WHERE id_sejarah = $id_sejarah";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//Fungsi menghapus data sejarah

function delete_sejarah($id_sejarah)
{
    global $db;

    //query hapus data sejarah
    $query = "DELETE FROM sejarah WHERE id_sejarah = $id_sejarah";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi menambahkan visi
function create_visi($post)
{
    global $db;

    $judul_visi = $post['judul_visi'];
    $isi_visi = $post['isi_visi'];

    //query tambah data
    $query = "INSERT INTO visi VALUES(null, '$judul_visi', '$isi_visi', CURRENT_TIMESTAMP())";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi ubah data visi
function update_visi($post)
{
    global $db;
    $id_visi  = $post['id_visi'];
    $judul_visi      = $post['judul_visi'];
    $isi_visi     = $post['isi_visi'];

    //query ubah data
    $query = "UPDATE visi SET judul_visi = '$judul_visi', isi_visi = '$isi_visi' WHERE id_visi = $id_visi";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//Fungsi menghapus data visi

function delete_visi($id_visi)
{
    global $db;

    //query hapus data visi
    $query = "DELETE FROM visi WHERE id_visi = $id_visi";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}





//fungsi menambahkan misi
function create_misi($post)
{
    global $db;

    $judul_misi = $post['judul_misi'];
    $isi_misi = $post['isi_misi'];

    //query tambah data
    $query = "INSERT INTO misi VALUES(null, '$judul_misi', '$isi_misi', CURRENT_TIMESTAMP())";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi ubah data misi
function update_misi($post)
{
    global $db;
    $id_misi  = $post['id_misi'];
    $judul_misi      = $post['judul_misi'];
    $isi_misi     = $post['isi_misi'];

    //query ubah data
    $query = "UPDATE misi SET judul_misi = '$judul_misi', isi_misi = '$isi_misi' WHERE id_misi = $id_misi";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//Fungsi menghapus data misi

function delete_misi($id_misi)
{
    global $db;

    //query hapus data misi
    $query = "DELETE FROM misi WHERE id_misi = $id_misi";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}
?>