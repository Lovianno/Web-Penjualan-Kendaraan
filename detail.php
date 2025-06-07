<?php
include 'config.php';

// Cek apakah parameter ID ada
if (!isset($_GET['id'])) {
  echo "<div style='padding: 2rem; font-family: sans-serif; text-align: center;'>
          <h2>Permintaan tidak valid!</h2>
          <a href='catalog.php'>← Kembali ke katalog</a>
        </div>";
  exit;
}

// Ambil data detail kendaraan
$kendaraan = getDetailKendaraan($_GET['id']);

if (!$kendaraan) {
  echo "<div style='padding: 2rem; font-family: sans-serif; text-align: center;'>
          <h2>Kendaraan tidak ditemukan!</h2>
          <a href='catalog.php'>← Kembali ke katalog</a>
        </div>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rental Mobil - Detail Kendaraan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="background-color: #f1efec !important;">
  <div class="container py-5">
    <h2 class="text-center mb-4">Detail Kendaraan</h2>
    <div class="col-md-4 mx-auto">
      <img src="admin/uploads/<?= htmlspecialchars($kendaraan['gambar']) ?>" alt="<?= htmlspecialchars($kendaraan['nama']) ?>" class="w-100 img-fluid rounded-3 border mb-3" />
    </div>
    <div class="card p-4 shadow rounded-4">
      <div class="row">
        <div class="col-md-8">
          <h3><?= htmlspecialchars($kendaraan['merek'] . ' ' . $kendaraan['nama'] . ' ' . $kendaraan['tahun']) ?></h3>
          <p><strong>Kategori:</strong> <?= htmlspecialchars($kendaraan['kategori']) ?></p>
          <p><strong>Tipe:</strong> <?= htmlspecialchars($kendaraan['tipe']) ?></p>
          <p><strong>Tahun:</strong> <?= htmlspecialchars($kendaraan['tahun']) ?></p>
          <p style="text-align: justify;"><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($kendaraan['deskripsi'])) ?></p>
          <p><strong>Harga Jual :</strong> Rp<?= number_format($kendaraan['harga'], 0, ',', '.'); ?></p>
          <p><strong>Status:</strong> <span class=""><?= $kendaraan['status'] == 1 ? 'Tersedia' : 'Tidak Tersedia'; ?></span></p>

          <a href="catalog.php" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>

          <?php if ($kendaraan['status'] == 1) { ?>
            <a href="https://wa.me/6285335599526?text=Halo,%20saya%20ingin%20merental%20kendaraan%20<?= urlencode($kendaraan['nama']) ?>"
              class="btn btn-success mt-3" target="_blank">
              <i class="bi bi-whatsapp"></i> Kontak Penjual
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>