<?php

//render halaman menjadi json
header('Content-Type: application/json');

require '../config/app.php';

//menerima requst put/delete
parse_str(file_get_contents('php://input'), $delete);

//menerima input id data yang akan di hapus
$id_barang = $delete['id_barang'];

//query hapus data
$query = "DELETE FROM barang WHERE id_barang = $id_barang";
mysqli_query($db, $query);

if ($query) {
    echo json_encode(['pesan' => 'Data Barang Berhasil hapus']);
} else {
    echo json_encode(['pesan' => 'Data Barang Gagal']);
}
echo json_encode(['data_barang' => $query]);
