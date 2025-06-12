<?php
include '../config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID kendaraan tidak ditemukan'); window.location='kendaraan.php';</script>";
    exit;
}

$role = $_SESSION['user']['role'];
$id = (int)$_GET['id'];

$kendaraan = getDetailKendaraan($id);



if (!$kendaraan) {
    echo "<script>alert('Data kendaraan tidak ditemukan'); window.location='kendaraan.php';</script>";
    exit;
}

$statusLabel = [
    0 => 'Menunggu Konfirmasi',
    1 => 'Aktif',
    2 => 'Nonaktif',
    3 => 'Ditolak'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rental Mobil - Detail Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .square-img {
            width: 100%;
            max-width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
        }

        .card-body h4 {
            font-weight: bold;
        }

        .card-text strong {
            display: inline-block;
            width: 120px;
        }
    </style>
</head>

<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4 text-center">Detail Kendaraan</h2>

            <div class="card shadow p-3">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="uploads/<?= htmlspecialchars($kendaraan['gambar']); ?>" class="square-img img-thumbnail" alt="<?= htmlspecialchars($kendaraan['nama']); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h4 class="card-title mb-3"><?= htmlspecialchars($kendaraan['nama']); ?></h4>
                            <p class="card-text"><strong>Kategori:</strong> <?= htmlspecialchars($kendaraan['kategori']); ?></p>
                            <p class="card-text"><strong>Merek:</strong> <?= htmlspecialchars($kendaraan['merek']); ?></p>
                            <p class="card-text"><strong>Tipe:</strong> <?= htmlspecialchars($kendaraan['tipe']); ?></p>
                            <p class="card-text"><strong>Tahun:</strong> <?= htmlspecialchars($kendaraan['tahun']); ?></p>
                            <p class="card-text"><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($kendaraan['deskripsi'])); ?></p>
                            <p class="card-text"><strong>Harga:</strong> Rp<?= number_format($kendaraan['harga'], 0, ',', '.'); ?></p>
                            <p class="card-text"><strong>Pemilik :</strong> <?= htmlspecialchars($kendaraan['no_telepon']); ?></p>
                            
                            <p class="card-text"><strong>Status:</strong>
                                <?= $statusLabel[$kendaraan['status']] ?? 'Status Tidak Diketahui'; ?>
                            </p>

                            <a href="kendaraan.php" class="btn btn-secondary mt-3">← Kembali</a>

                            <?php if ($role != 1 && $kendaraan['status'] != 3): ?>
                                <a href="editKendaraan.php?id=<?= $id ?>" class="btn btn-warning mt-3">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </a>
                                <a href="hapusKendaraan.php?id=<?= $id ?>" class="btn btn-danger mt-3" onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
