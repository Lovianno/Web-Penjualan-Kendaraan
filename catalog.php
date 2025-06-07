<?php
include 'config.php';

// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$kategori = $_GET['kategori'] ?? '';
$search = $_GET['search'] ?? '';

// Ambil semua kategori (untuk dropdown)
$kategoriList = getKategori();

$perPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Ambil data Kendaraan berdasarkan filter
$kendaraan = getKendaraanFiltered($kategori, $search, $perPage, $offset, 0);
$totalData = countKendaraanFiltered($kategori, $search, 0);
$totalPages = ceil($totalData / $perPage);



?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Toko Kendaraan - Katalog Kendaraan</title>
  <link rel="stylesheet" href="assets/css/catalog.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-soft">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark" id="home">
    <div class="container">
      <a class="navbar-brand text-tiga judul text-racing" href="index.php">Toko Kendaraan</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav mx-auto gap-4">
          <a class="nav-link text-racing" href="index.php">Home</a>
          <a class="nav-link text-racing" href="catalog.php">Catalog</a>
          <a class="nav-link text-racing" href="index.php#about">About Us</a>
          <a class="nav-link text-racing" href="index.php#contact">Contact Us</a>
          <?php if (isset($_SESSION['user'])): ?>
            <a class="nav-link" href="admin/kendaraan.php">Master Kendaraan</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <form class="d-flex" method="get">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari Kendaraan..." name="search" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-tiga" type="submit"><i class="bi bi-search"></i></button>
          </div>
        </form>
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="admin/login.php" class="btn btn-sm btn-tiga ms-2">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </a>
          <a href="admin/login.php" class="btn btn-sm btn-empat ms-2">
            <i class="bi bi-box-arrow-in-right me-1"></i> Register
          </a>
        <?php else: ?>
          <a href="admin/logout.php" class="btn btn-warning ms-2 text-light">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- CONTENT -->
  <div class="container py-5">
    <h2 class="text-center text-racing mb-5">Katalog Kendaraan</h2>
    <div class="row">
      <!-- Sidebar Filter -->
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm p-3 bg-light border-0 rounded-4">
          <h5 class="mb-3 text-tiga text-center">Pencarian</h5>
          <form method="get" action="catalog.php">
            <div class="mb-3">
              <label for="search" class="form-label fw-semibold">Cari Kendaraan</label>
              <input type="text" class="form-control" id="search" name="search"
                value="<?= htmlspecialchars($search) ?>" placeholder="Nama Kendaraan...">
            </div>
            <div class="mb-3">
              <label for="kategori" class="form-label fw-semibold">Kategori</label>
              <select class="form-select" name="kategori" id="kategori">
                <option value="">Semua</option>
                <?php foreach ($kategoriList as $k): ?>
                  <option value="<?= $k ?>" <?= ($kategori == $k) ? 'selected' : '' ?>><?= $k ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <a href="catalog.php" class="btn btn-warning" style="width: 15%;">
              <i class="bi bi-arrow-clockwise"></i>
            </a>
            <button type="submit" class="btn btn-tiga" style="width: 83%;">Cari</button>
          </form>
        </div>
      </div>

      <!-- Katalog Kendaraan -->
      <div class="col-md-9">
        <div class="row g-4">
          <?php if (count($kendaraan) === 0): ?>
            <div class="col-12">
              <div class="alert alert-warning text-center">Kendaraan tidak ditemukan.</div>
            </div>
          <?php endif; ?>

          <?php foreach ($kendaraan as $item): ?>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 product-card shadow-sm position-relative">
                <img src="admin/uploads/<?= $item['gambar'] ?>" class="card-img-top" alt="<?= $item['nama'] ?>" style="object-fit: cover;" />
                <div class="card-body">
                  <h5 class="card-title text-tiga text-racing"><?= $item['merek'] . ' ' . $item['nama'] . ' ' . $item['tahun'] ?></h5>
                  <p class="card-text text-muted text-tiga mb-1">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                  <p class="card-text"><?= mb_strimwidth($item['deskripsi'], 0, 60, '...') ?></p>
                </div>
                <div class="mx-auto pb-3 pt-5">
                  <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-sm  btn-tiga">
                    <i class="bi bi-info-circle"></i> Lihat Detail
                  </a>
                  <?php if ($item['status'] == 1): ?>
                    <a href="https://wa.me/<?= getNoTeleponByKendaraanId($item['id']) ?>?text=Halo,%20saya%20ingin%20memesan%20kendaraan%20<?= urlencode($item['nama']) ?>"
                      class="btn btn-sm btn-success" target="_blank">
                      <i class="bi bi-whatsapp"></i> Hubungi Penjual
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?kategori=<?= urlencode($kategori) ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>

    </div>
  </div>