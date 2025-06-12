<?php
require '../config.php'; // Pastikan koneksi $pdo dan session dimulai di sini

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Validasi input ID dan status
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    echo "<script>alert('Parameter tidak lengkap!'); window.location='konfirmasiPostingan.php';</script>";
    exit;
}

$id = (int)$_GET['id'];
$status = (int)$_GET['status'];

// Validasi role user (hanya admin / role 1 yang bisa ubah status)
if ($_SESSION['user']['role'] != 1) {
    echo "<script>alert('Anda tidak memiliki izin untuk mengakses fitur ini!'); history.back();</script>";
    exit;
}

// Jalankan fungsi ubah status kendaraan
if (ubahStatusKendaraan($status, $id)) {
    echo "<script>alert('Status kendaraan berhasil diubah'); window.location='konfirmasiPostingan.php';</script>";
} else {
    echo "<script>alert('Gagal mengubah status kendaraan'); window.location='konfirmasiPostingan.php';</script>";
}
