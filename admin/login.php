<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config/app.php';

// Check apakah tombol login ditekan
if (isset($_POST['login'])) {
    // Ambil input username dan password
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Secret key reCAPTCHA
    $secret_key = "6Ldfy-cqAAAAAA5Ko5SsZOGDf1a5Cm761izRE0ft";
    // $secret_key = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";
    $response_key = $_POST['g-recaptcha-response'];
    $verifikasi = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response_key");
    $response = json_decode($verifikasi);

    if ($response->success) {
        // Check username di database
        $result = mysqli_query($db, "SELECT * FROM akun WHERE username = '$username'");

        // Jika username ditemukan
        if (mysqli_num_rows($result) == 1) {
            $hasil = mysqli_fetch_assoc($result);

            // Verifikasi password
            if (password_verify($password, $hasil['password'])) {
                // Set session
                $_SESSION['login']      = true;
                $_SESSION['id_akun']    = $hasil['id_akun'];
                $_SESSION['nama']       = $hasil['nama'];
                $_SESSION['username']   = $hasil['username'];
                $_SESSION['email']      = $hasil['email'];
                $_SESSION['level']      = $hasil['level'];

                header("Location: index.php?login=success");
                exit;
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
    } else {
        $errorRecaptcha = true;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/icon/login.png">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <link href="assets/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
    <main class="form-signin">
        <form action="" method="POST">
            <img class="mb-4" src="assets/icon/login.png" alt="" width="100" height="100">
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger text-center">
                    <b>Username atau Password Salah</b>
                </div>
            <?php endif; ?>

            <?php if (isset($errorRecaptcha)) : ?>
                <div class="alert alert-danger text-center">
                    <b>Recaptcha tidak valid</b>
                </div>
            <?php endif; ?>

            <div class="form-floating">
                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <div class="mb-3">
                <div class="g-recaptcha" data-sitekey="6Ldfy-cqAAAAAMswzmjpUWbqGMgq5PmFpJutwDGT"></div>
                <!-- <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div> -->
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Sign in</button>
            <p class="mt-5 mb-3 text-muted">Copyright &copy; FM Tech <?= date('Y') ?></p>
        </form>
    </main>

    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>

</html>
