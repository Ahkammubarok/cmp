<?php
ob_start(); // Memulai output buffering

// Mulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tentukan Base URL (Sesuaikan dengan path proyek di server)
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/cmp/admin/";

// Pastikan semua file penting tersedia
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/database.php';

// Judul Default jika tidak diset
if (!isset($title)) {
    $title = "Admin Panel";
}

ob_end_flush(); // Akhiri output buffering
?>
