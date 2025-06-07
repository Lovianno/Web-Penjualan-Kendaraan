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

$kendaraan = getDetailKendaraan($_GET['id']);
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
                            <img src="uploads/<?= $kendaraan['gambar']; ?>" class="square-img img-thumbnail" alt="<?= $kendaraan['nama']; ?>">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3"><?= $kendaraan['nama']; ?></h4>
                                <p class="card-text"><strong>Kategori:</strong> <?= $kendaraan['kategori']; ?></p>
                                <p class="card-text"><strong>Merek:</strong> <?= $kendaraan['merek']; ?></p>
                                <p class="card-text"><strong>Tipe:</strong> <?= $kendaraan['tipe']; ?></p>
                                <p class="card-text"><strong>Tahun:</strong> <?= $kendaraan['tahun']; ?></p>
                                <p class="card-text"><strong>Deskripsi:</strong><br><?= nl2br($kendaraan['deskripsi']); ?></p>
                                <p class="card-text"><strong>Harga:</strong> Rp<?= number_format($kendaraan['harga'], 0, ',', '.'); ?></p>
                                <p class="card-text"><strong>Status:</strong> <?= $kendaraan['status'] == 1 ? 'Tersedia' : 'Tidak Tersedia'; ?></p>

                                <a href="kendaraan.php" class="btn btn-secondary mt-3">← Kembali</a>
                                <a href="editKendaraan.php?id=<?= $_GET['id'] ?>" class="btn btn-warning mt-3">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </a>
                                <a href="hapusKendaraan.php?id=<?= $_GET['id'] ?>" class="btn btn-danger mt-3" onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>