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
$title = 'Data Mahasiswa';


require_once __DIR__ . '/../layout/header.php';


//menampilkan data mahasiswa

$data_mahasiswa = select("SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC");

?>


<div class="content-wrapper">
    <!-- Content Header -->
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
                    <div class="card-header d-flex left-content-end align-items-center">
                        <a href="index.php?page=add_mahasiswa" class="btn btn-primary me-2">
                            <i class="fa-solid fa-square-plus"></i> Tambah
                        </a>
                        <!-- <a href="download-excel-mahasiswa.php" class="btn btn-success">
                            <i class="fa-solid fa-file-excel"></i> Download
                        </a> -->
                    </div>


                        <div class="card-body">
                            <!-- Tambahkan wrapper responsif -->
                            <div class="table-responsive">
                                <table id="serverside" class="table table-striped table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Prodi</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- jQuery (wajib) -->



<!-- DataTables Script -->
<script>
$(document).ready(function() {
    $('#serverside').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
        url: "_mahasiswa/mahasiswa-serverside.php", // Sesuaikan path ini
        type: "POST",
        data: { action: "fetch" },
        dataType: "json",
        error: function(xhr, status, error) {
            console.log("AJAX Error: ", xhr.responseText);
        }
        },
        responsive: true,
        autoWidth: false,
        fixedHeader: true,
        columnDefs: [{ orderable: false, targets: [7] }],
        language: {
            searchPlaceholder: "Cari data...",
            search: "",
        }
    });

    setTimeout(function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }, 500);
});
</script>

<!-- Tambahkan CSS agar form search tidak ikut bergeser -->
<style>
    div.dataTables_filter {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        padding: 10px 0;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Event delegation untuk elemen dinamis
    $(document).on("click", ".delete-btn", function(e) {
        e.preventDefault();
        var id_mahasiswa = $(this).data("id");

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data mahasiswa akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("Menghapus ID:", id_mahasiswa);

                // AJAX request untuk menghapus data
                $.ajax({
                    url: "_delete/hapus-mahasiswa.php", // Pastikan path benar
                    type: "POST",
                    data: { id_mahasiswa: id_mahasiswa },
                    dataType: "json",
                    success: function(response) {
                        console.log("Response dari server:", response); // Debugging

                        if (response.status === "success") {
                            Swal.fire("Terhapus!", "Data mahasiswa telah dihapus.", "success")
                                .then(() => {
                                    $('#serverside').DataTable().ajax.reload(); // Refresh tabel
                                });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX Error: ", xhr.responseText);
                        Swal.fire("Error!", "Gagal menghapus data.", "error");
                    }
                });
            }
        });
    });
});

</script>

<?php
include __DIR__ . '/../layout/footer.php';
exit;
?>



