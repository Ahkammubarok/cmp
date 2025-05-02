<?php include __DIR__ . '/../config/app.php'; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title><?= $title; ?></title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- AdminLTE -->
    
    <link rel="stylesheet" href="<?= $base_url; ?>assets/dist/css/adminlte.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <!-- JQVMap -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/jqvmap/jqvmap.min.css">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/daterangepicker/daterangepicker.css">

    <!-- Summernote -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/summernote/summernote-bs4.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $base_url; ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/css/style.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown text-center">
                    <a class="nav-link d-flex flex-column align-items-center" data-toggle="dropdown" href="#">
                        <i class="fa-solid fa-user fa-lg mt-2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fa-solid fa-power-off"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?=$base_url;?>index.php" class="brand-link">
                <img src="<?=$base_url;?>assets/dist/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">LOGO</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?=$base_url;?>assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?=$_SESSION['nama'] ?? 'Guest';?></a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <?php $current_page = $_GET['page'] ?? 'dashboard'; ?>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-header">EXAMPLES</li>
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="index.php?page=index" class="nav-link <?= ($current_page == 'index') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-gauge" style="margin-right: 8px;"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <!-- Inventory -->
                        <li class="nav-item <?= ($current_page == 'inventory' || $current_page == 'kategori') ? 'menu-open' : ''; ?>">
                            <a href="#" class="nav-link <?= ($current_page == 'inventory' || $current_page == 'kategori') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Inventory <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="index.php?page=inventory" class="nav-link <?= ($current_page == 'inventory') ? 'active' : ''; ?>" style="margin-left: 15px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Barang</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?page=kategori" class="nav-link <?= ($current_page == 'kategori') ? 'active' : ''; ?>" style="margin-left: 15px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kategori</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Mahasiswa -->
                        <li class="nav-item">
                            <a href="index.php?page=mahasiswa" class="nav-link <?= ($current_page == 'mahasiswa') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-users" style="margin-right: 8px;"></i>
                                <p>Mahasiswa</p>
                            </a>
                        </li>
                        <!-- Akun -->
                        <li class="nav-item">
                            <a href="index.php?page=akun" class="nav-link <?= ($current_page == 'akun') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-user-gear" style="margin-right: 8px;"></i>
                                <p>Akun</p>
                            </a>
                        </li>
                        <!-- Kirim Email -->
                        <li class="nav-item">
                            <a href="index.php?page=email" class="nav-link <?= ($current_page == 'email') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-envelope" style="margin-right: 8px;"></i>
                                <p>Kirim Email</p>
                            </a>
                        </li>
                        <!-- Sejarah Singkat -->
                        <li class="nav-item">
                            <a href="index.php?page=sejarah" class="nav-link <?= ($current_page == 'sejarah') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-book-open-reader" style="margin-right: 8px;"></i>
                                <p>Sejarah Singkat</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>