<?php
require_once __DIR__ . '/../config/app.php'; // Pastikan koneksi ke database benar

header('Content-Type: application/json');
ob_clean(); // Bersihkan output buffer sebelum mengirim JSON

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_mahasiswa"])) {
    $id_mahasiswa = intval($_POST["id_mahasiswa"]); // Hindari SQL Injection

    // Cek apakah mahasiswa memiliki foto
    $query_foto = $db->query("SELECT foto FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa");
    $data = $query_foto->fetch_assoc();
    
    // Jika ada foto, hapus dari folder
    if (!empty($data['foto'])) {
        $foto_path = __DIR__ . "/../assets/img/" . $data['foto'];
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    // Hapus data mahasiswa dari database
    $query = $db->query("DELETE FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa");

    if ($query) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $db->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Permintaan tidak valid"]);
}
exit;
?>
