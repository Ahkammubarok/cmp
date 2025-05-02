<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt'); // Simpan error di sini
ob_start(); // Pastikan tidak ada output sebelum header
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout = 900;

if (!isset($_SESSION["login"])) {
    echo "<script>
            alert('Silakan login terlebih dahulu');
            document.location.href = '../login.php';
          </script>";
    exit;
}

if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"] > $timeout)) {
    session_unset();
    session_destroy();
    echo "<script>
            alert('Sesi telah habis, silakan login kembali');
            document.location.href = '../login.php';
          </script>";
    exit;
}

$_SESSION["last_activity"] = time();

$base_path = dirname(__DIR__);
$configPath = $base_path . '/config/app.php';
$autoloadPath = $base_path . '/vendor/autoload.php';

if (!file_exists($configPath)) {
    die('Error: File config/app.php tidak ditemukan.');
}
if (!file_exists($autoloadPath)) {
    die('Error: File vendor/autoload.php tidak ditemukan.');
}

require_once $configPath;
require_once $autoloadPath;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'CMP Mahdalena');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$headers = ['No', 'Nama', 'Program Studi', 'Jenis Kelamin', 'Telepon', 'Email', 'Foto'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

foreach ($columns as $index => $col) {
    $sheet->setCellValue($col . '2', $headers[$index]);
    $sheet->getStyle($col . '2')->getFont()->setBold(true);
    $sheet->getStyle($col . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

$columnWidths = [5, 20, 20, 15, 15, 25, 30];
foreach ($columns as $index => $col) {
    $sheet->getColumnDimension($col)->setWidth($columnWidths[$index]);
}

if (!function_exists('select')) {
    die("Error: Fungsi select() tidak ditemukan.");
}

$data_mahasiswa = select("SELECT * FROM mahasiswa");

$no = 1;
$startRow = 3;

foreach ($data_mahasiswa as $mahasiswa) {
    $sheet->setCellValue('A' . $startRow, $no++);
    $sheet->setCellValue('B' . $startRow, $mahasiswa['nama']);
    $sheet->setCellValue('C' . $startRow, $mahasiswa['prodi']);
    $sheet->setCellValue('D' . $startRow, $mahasiswa['jk']);
    $sheet->setCellValue('E' . $startRow, $mahasiswa['telepon']);
    $sheet->setCellValue('F' . $startRow, $mahasiswa['email']);

    $foto_path = realpath($base_path . '/assets/img/' . $mahasiswa['foto']);
    if ($foto_path && file_exists($foto_path) && !empty($mahasiswa['foto'])) {
        $drawing = new Drawing();
        $drawing->setName('Foto');
        $drawing->setDescription('Foto Mahasiswa');
        $drawing->setPath($foto_path);
        $drawing->setHeight(80);
        $drawing->setCoordinates('G' . $startRow);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());
    } else {
        $sheet->setCellValue('G' . $startRow, 'Tidak Ada Foto');
    }

    $startRow++;
}

$lastRow = $startRow - 1;
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];
$sheet->getStyle('A2:G' . $lastRow)->applyFromArray($styleArray);

$tempFile = @tempnam(sys_get_temp_dir(), 'data_mahasiswa') . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($tempFile);

if (!file_exists($tempFile)) {
    die("Error: File Excel tidak dapat dibuat.");
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="data_mahasiswa.xlsx"');
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
header('Content-Length: ' . filesize($tempFile));

readfile($tempFile);
unlink($tempFile);
ob_end_clean();
echo "Script selesai, file siap di-download."; // Debug
exit;
?>
