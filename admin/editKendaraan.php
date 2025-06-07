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

$kendaraan = getDetailKendaraan($_GET['id']);

if ($kendaraan['user_id'] != $_SESSION['user']['id']) {
    // Redirect jika bukan miliknya
    echo "<script>alert('Anda tidak memiliki izin untuk mengakses data ini!'); history.back();</script>"; // bisa diarahkan ke halaman error atau dashboard
    exit;
}

$kategoriList = getKategori();

if (isset($_POST['submit'])) {
    $hasil = updateKendaraan($_GET['id'], $_POST, $_FILES, $kendaraan['gambar']);

    if ($hasil['success']) {
        echo "<script>alert('Data kendaraan berhasil diupdate!'); window.location='kendaraan.php';</script>";
    } else {
        echo "<script>alert('Gagal: {$hasil['message']}'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Kendaraan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    .preview-img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 0.5rem;
      border: 1px solid #ddd;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4 text-center">Edit Data Kendaraan</h2>
  <form method="POST" enctype="multipart/form-data" class="card shadow p-4">

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Kendaraan</label>
        <input type="text" class="form-control" id="nama" name="nama" value="<?= $kendaraan['nama'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" name="kategori" id="kategori" required>
            <?php foreach ($kategoriList as $k): ?>
                <option value="<?= htmlspecialchars($k) ?>" <?= $kendaraan['kategori'] == $k ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="merek" class="form-label">Merek</label>
        <input type="text" class="form-control" id="merek" name="merek" value="<?= $kendaraan['merek'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="tipe" class="form-label">Tipe</label>
        <input type="text" class="form-control" id="tipe" name="tipe" value="<?= $kendaraan['tipe'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="tahun" class="form-label">Tahun Produksi</label>
        <input type="number" class="form-control" id="tahun" name="tahun" value="<?= $kendaraan['tahun'] ?>" min="1900" max="<?= date('Y') ?>" required>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= $kendaraan['deskripsi'] ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Gambar Saat Ini:</label><br>
        <img src="uploads/<?= $kendaraan['gambar'] ?>" class="preview-img" alt="<?= $kendaraan['nama'] ?>">
    </div>

    <div class="mb-3">
        <label for="gambar" class="form-label">Upload Gambar Baru (opsional)</label>
        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
    </div>

    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" class="form-control" id="harga" name="harga" value="<?= $kendaraan['harga'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="1" <?= $kendaraan['status'] == 1 ? 'selected' : '' ?>>Tersedia</option>
            <option value="0" <?= $kendaraan['status'] == 0 ? 'selected' : '' ?>>Tidak Tersedia</option>
        </select>
    </div>

    <div class="d-flex justify-content-between">
        <a href="kendaraan.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
  </form>
</div>
</body>
</html>
