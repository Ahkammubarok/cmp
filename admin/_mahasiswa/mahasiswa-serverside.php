<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Pastikan bisa membaca input JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

// Debug untuk memastikan data masuk
file_put_contents('debug_log.txt', print_r($_POST, true));

if (!isset($_POST['draw'], $_POST['start'], $_POST['length'])) {
    echo json_encode(["error" => "Parameter DataTables tidak lengkap", "post_data" => $_POST]);
    exit;
}

include '../config/database.php';

// Cek apakah request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Debug untuk melihat data POST
file_put_contents('debug_log.txt', print_r($_POST, true)); // Simpan ke file

// Pastikan parameter DataTables lengkap
if (!isset($_POST['draw'], $_POST['start'], $_POST['length'], $_POST['order'])) {
    echo json_encode(["error" => "Parameter DataTables tidak lengkap", "post_data" => $_POST]);
    exit;
}

// Pastikan variabel database ada
if (!isset($db)) {
    echo json_encode(["error" => "Database connection error"]);
    exit;
}

// Pastikan `action` dikirim
$action = isset($_POST['action']) ? $_POST['action'] : 'fetch';

if ($action !== "fetch") {
    echo json_encode(["error" => "Invalid action", "received_action" => $action]);
    exit;
}

// Query untuk menghitung total data
$querycount = $db->query("SELECT count(id_mahasiswa) as jumlah FROM mahasiswa");
$datacount = $querycount->fetch_array();
$totalData = $datacount['jumlah'];
$totalFiltered = $totalData;

// Cek apakah query berhasil
if (!$querycount) {
    echo json_encode(["error" => "Query error: " . $db->error]);
    exit;
}

// Set limit dan offset dari DataTables
$limit  = (int) $_POST['length'];
$start  = (int) $_POST['start'];
$orderColumnIndex = (int) $_POST['order'][0]['column'];
$orderColumn = "id_mahasiswa"; // Default
$columns = ["id_mahasiswa", "nama", "prodi", "jk", "telepon", "alamat", "email", "id_mahasiswa"];

// Pastikan kolom yang diurutkan valid
if (isset($columns[$orderColumnIndex])) {
    $orderColumn = $columns[$orderColumnIndex];
}

$dir = $_POST['order'][0]['dir'] === "asc" ? "ASC" : "DESC";

// Jika ada pencarian
$search = $_POST['search']['value'];
if (!empty($search)) {
    $query = $db->query("SELECT id_mahasiswa, nama, prodi, jk, telepon, alamat, email
    FROM mahasiswa 
    WHERE nama LIKE '%$search%' 
    OR telepon LIKE '%$search%' 
    OR prodi LIKE '%$search%' 
    OR jk LIKE '%$search%' 
    OR alamat LIKE '%$search%' 
    OR email LIKE '%$search%' 
    ORDER BY id_mahasiswa DESC, $orderColumn $dir 
    LIMIT $limit OFFSET $start");

    $querycount = $db->query("SELECT count(id_mahasiswa) as jumlah 
        FROM mahasiswa 
        WHERE nama LIKE '%$search%' 
        OR telepon LIKE '%$search%' 
        OR prodi LIKE '%$search%' 
        OR jk LIKE '%$search%' 
        OR alamat LIKE '%$search%' 
        OR email LIKE '%$search%'");

    $datacount = $querycount->fetch_array();
    $totalFiltered = $datacount['jumlah'];
} else {
    $query = $db->query("SELECT id_mahasiswa, nama, prodi, jk, telepon, alamat, email
    FROM mahasiswa 
    ORDER BY id_mahasiswa DESC, $orderColumn $dir 
    LIMIT $limit OFFSET $start");
}

// Debug: Cek apakah query berjalan
if (!$query) {
    echo json_encode(["error" => "Query error: " . $db->error]);
    exit;
}

// Ambil data
$data = [];
if ($query) {
    $no = $start + 1;
    while ($value = $query->fetch_array()) {
        $nestedData['no'] = $no;
        $nestedData['nama'] = $value['nama'];
        $nestedData['prodi'] = $value['prodi'];
        $nestedData['jk'] = $value['jk'];
        $nestedData['telepon'] = $value['telepon'];
        $nestedData['alamat'] = trim(strip_tags(html_entity_decode($value['alamat'])));
        $nestedData['email'] = $value['email'];
        $nestedData['aksi'] = '<div class="text-center">
            <a href="index.php?page=detail_mahasiswa&id_mahasiswa='.$value['id_mahasiswa'].'" class="btn btn-secondary btn-sm">
                <i class="fa fa-eye"></i> Detail
            </a>
            <a href="index.php?page=edit_mahasiswa&id_mahasiswa='.$value['id_mahasiswa'].'" class="btn btn-success btn-sm">
                <i class="fa fa-pen"></i> Edit
            </a>
            <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="'.$value['id_mahasiswa'].'">
                <i class="fa fa-trash"></i> Hapus
            </a>

        </div>';
        $data[] = $nestedData;
        $no++;
    }
}


// Kirim response JSON ke DataTables
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);

exit;

?>