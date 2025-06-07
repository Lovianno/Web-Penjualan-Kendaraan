<?php
require '../config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID kendaraan tidak ditemukan'); window.location='kendaraan.php';</script>";
    exit;
}

$id = $_GET['id'];

// Ambil data kendaraan berdasarkan ID
$kendaraan = getDetailKendaraan($id);

if ($kendaraan['user_id'] != $_SESSION['user']['id']) {
    // Redirect jika bukan miliknya
    echo "<script>alert('Anda tidak memiliki izin untuk mengakses data ini!'); history.back();</script>"; // bisa diarahkan ke halaman error atau dashboard
    exit;
}

if (!$kendaraan) {
    echo "<script>alert('Data kendaraan tidak ditemukan'); window.location='kendaraan.php';</script>";
    exit;
}

// Hapus gambar jika ada
$gambarPath = "uploads/" . $kendaraan['gambar'];
if (!empty($kendaraan['gambar']) && file_exists($gambarPath)) {
    unlink($gambarPath);
}

// Hapus dari database
if (hapusKendaraan($id)) {
    echo "<script>alert('Data kendaraan berhasil dihapus'); window.location='kendaraan.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data kendaraan'); window.location='kendaraan.php';</script>";
}
?>
