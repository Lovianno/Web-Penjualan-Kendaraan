<?php include '../config.php'; 

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

// Ambil data user dari login
$namaUser = $_SESSION['user']['nama'];
$user_id = $_SESSION['user']['id'];
$role = $_SESSION['user']['role'];


// Ambil data dari input GET
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil semua kategori (untuk dropdown)
$kategoriList = getKategori();

$perPage = 10; // jumlah data per halaman
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$userFilterId = ($role == 1) ? 0 : $user_id;

$kendaraan = getKendaraanFiltered($kategori, $search, $perPage, $offset, $userFilterId);
$totalData = countKendaraanFiltered($kategori, $search, $userFilterId);

$totalPages = ceil($totalData / $perPage);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Toko Kendaraan - Master Kendaraan</title>
  <link rel="stylesheet" href="css/admin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 p-0">
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm px-4">
          <a class="navbar-brand" href="#">Toko Kendaraan</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
              <div class="topbar-divider d-none d-sm-block"></div>
              <li class="nav-item dropdown ms-3">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2 d-none d-lg-inline text-dark small"><?= $namaUser ?></span>
                    <i class="bi bi-person-circle fs-4 text-dark"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item " href="../index.php">Home</a></li>
                    <?php if($role == 1){ ?>
                    <li><a class="dropdown-item " href="konfirmasiPostingan.php">Konfirmasi Postingan</a></li>
                    <?php }; ?>

                    <li><a class="dropdown-item " href="../catalog.php">Katalog Kendaraan</a></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Page Content -->
        <main class="mt-4">
          <div class="container">
            <h2 class="text-center">Data Kendaraan</h2>
            <div class="row mt-4 mb-3 g-2">
              <div class="col-12 col-md">
                <form class="row g-2 align-items-center flex-column flex-md-row" method="GET">
                  <div class="col-12 col-md">
                    <input type="text" class="form-control w-100" id="search" name="search"
                      value="<?= htmlspecialchars($search) ?>" placeholder="Nama kendaraan...">
                  </div>
                  <div class="col-12 col-md-auto">
                    <select class="form-select w-100" name="kategori" id="kategori">
                      <option value="">Semua</option>
                      <?php foreach ($kategoriList as $k): ?>
                        <option value="<?= $k ?>" <?= ($kategori == $k) ? 'selected' : '' ?>><?= $k ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-12 col-md-auto">
                    <a href="kendaraan.php" class="btn btn-warning w-100">
                      <i class="bi bi-arrow-clockwise"></i>
                    </a>
                  </div>
                  <div class="col-12 col-md-auto">
                    <button class="btn btn-primary w-100">
                      <i class="bi bi-search"></i> Cari
                    </button>
                  </div>
                </form>
              </div>
              <div class="col-12 col-md-auto">
                <a href="tambahKendaraan.php" class="btn btn-success w-100">
                  Tambah Kendaraan
                </a>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped">
                <thead class="table-primary text-center">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Merek</th>
                    <th>Tipe</th>
                    <th>Tahun</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($kendaraan as $item): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $item['nama'] ?></td>
                      <td><?= $item['kategori'] ?></td>
                      <td><?= $item['merek'] ?></td>
                      <td><?= $item['tipe'] ?></td>
                      <td><?= $item['tahun'] ?></td>
                      <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                     <td>
  <?php
    $statusLabels = [
      0 => ['label' => 'Menunggu Konfirmasi', 'badge' => 'warning'],
      1 => ['label' => 'Aktif', 'badge' => 'success'],
      2 => ['label' => 'Nonaktif', 'badge' => 'secondary'],
      3 => ['label' => 'Ditolak', 'badge' => 'danger'],
     
    ];

    $status = (int)$item['status'];
    $label = $statusLabels[$status]['label'] ?? 'Tidak Diketahui';
    $badge = $statusLabels[$status]['badge'] ?? 'dark';
  ?>
  <span class="badge bg-<?= $badge ?>"><?= $label ?></span>
</td>
                      <td class="text-center">
                        <a href="detailKendaraan.php?id=<?= $item['id'] ?>" class="btn btn-info btn-sm mb-1">
                          <i class="bi bi-info-circle"></i> Info
                        </a>
                        <?php if($role != 1){?>

                          <?php if($item['status'] != 3){?>
                        <a href="editKendaraan.php?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm mb-1">
                          <i class="bi bi-pencil-square"></i> Ubah
                        </a>
                          
                          <a href="hapusKendaraan.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">
                            <i class="bi bi-trash"></i> Hapus
                          </a>
                            <?php }?>

                          <?php }?>

                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <nav>
              <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link"
                      href="?page=<?= $i ?>&kategori=<?= urlencode($kategori) ?>&search=<?= urlencode($search) ?>">
                      <?= $i ?>
                    </a>
                  </li>
                <?php endfor; ?>
              </ul>
               </nav>

            </div>
          </div>
        </main>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
