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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Email</title>
</head>
<body>

<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'mahdalena.mubarok@gmail.com'; // Menghapus spasi ekstra
$mail->Password   = 'pxwvnoqmduyqmxqa'; // Gantilah dengan App Password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port       = 465;

if (isset($_POST['kirim'])) {
    try {
        $mail->setFrom('mahdalena.mubarok@gmail.com', 'CMP');
        $mail->addAddress($_POST['email_penerima']);
        $mail->addReplyTo('mahdalena.mubarok@gmail.com', 'CMP'); 

        $mail->Subject = $_POST['subject'];
        $mail->Body    = $_POST['pesan'];

        if ($mail->send()) {
            echo "<script>
                    alert('Email berhasil dikirim');
                    document.location.href = 'index.php?page=email';
                </script>";
        } else {
            echo "<script>
                    alert('Email gagal dikirim');
                    document.location.href = 'index.php?page=email';
                </script>";
        }
    } catch (Exception $e) {
        echo "<script>
                alert('Email gagal dikirim: " . $mail->ErrorInfo . "');
                document.location.href = 'index.php?page=email';
            </script>";
    }
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa-solid fa-plus"></i> Kirim Email</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Form Email</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Email</h3>
                        </div>
                        <form action="" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="email_penerima" class="form-label">Email Penerima</label>
                                    <input type="email" class="form-control" id="email_penerima" name="email_penerima" placeholder="Masukkan email penerima..." required>
                                </div>
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Masukkan subject email..." required>
                                </div>
                                <div class="form-group">
                                    <label for="pesan" class="form-label">Pesan</label>
                                    <textarea name="pesan" id="pesan" cols="30" rows="10" class="form-control" placeholder="Tulis pesan Anda di sini..." required></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="kirim" class="btn btn-primary float-right">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>

</body>
</html>
