<?php 
require '../config.php';


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];


if (isset($_POST['submit'])) {
    $hasil = simpanKendaraan($_POST, $_FILES);

    if ($hasil['success']) {
        echo "<script>alert('Data kendaraan berhasil disimpan!'); window.location='kendaraan.php';</script>";
    } else {
        echo "<script>alert('Gagal: {$hasil['message']}'); window.history.back();</script>";
    }
}

$kategoriList = getKategori(); // misalnya berisi jenis kendaraan: Mobil, Motor, dll
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data Kendaraan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4 text-center">Tambah Data Kendaraan</h2>

  <form method="POST" enctype="multipart/form-data" class="card shadow p-4">

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Kendaraan</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
    </div>

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" name="kategori" id="kategori" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($kategoriList as $k): ?>
                <option value="<?= htmlspecialchars($k) ?>"><?= htmlspecialchars($k) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="merek" class="form-label">Merek</label>
        <input type="text" class="form-control" id="merek" name="merek" required>
    </div>

    <div class="mb-3">
        <label for="tipe" class="form-label">Tipe</label>
        <input type="text" class="form-control" id="tipe" name="tipe" required>
    </div>

    <div class="mb-3">
        <label for="tahun" class="form-label">Tahun Produksi</label>
        <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="<?= date('Y') ?>" required>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
    </div>

    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar</label>
        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
    </div>

    <div class="mb-3">
        <label for="harga" class="form-label">Harga Jual</label>
        <input type="number" class="form-control" id="harga" name="harga" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="1">Tersedia</option>
            <option value="0">Tidak Tersedia</option>
        </select>
    </div>
    <input type="hidden" name="user_id" value="<?= $user_id?>">
    <div class="d-flex justify-content-between">
        <a href="kendaraan.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>
</body>
</html>
