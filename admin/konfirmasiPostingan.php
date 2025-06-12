<?php include '../config.php'; 

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

$namaUser = $_SESSION['user']['nama'];
$user_id = $_SESSION['user']['id'];
$role = $_SESSION['user']['role'];

if ($role == 2) {
   echo "<script>alert('Anda tidak memiliki izin untuk mengakses!'); history.back();</script>"; // bisa diarahkan ke halaman error atau dashboard
    exit;
}

$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategoriList = getKategori();

$perPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$userFilterId = ($role == 1) ? 0 : $user_id;

// Ambil kendaraan yang status-nya 0 (Menunggu Konfirmasi)
$kendaraan = getKendaraanFiltered($kategori, $search, $perPage, $offset, $userFilterId, 0);
$totalData = countKendaraanFiltered($kategori, $search, $userFilterId, 0, 0);

$totalPages = ceil($totalData / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Toko Kendaraan - Konfirmasi Postingan</title>
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
              <li class="nav-item dropdown ms-3">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2 d-none d-lg-inline text-dark small"><?= $namaUser ?></span>
                    <i class="bi bi-person-circle fs-4 text-dark"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item " href="../index.php">Home</a></li>
                    <?php if($role == 1){ ?>
                    <li><a class="dropdown-item" href="kendaraan.php">Master Kendaraan</a></li>
                    <li><a class="dropdown-item" href="konfirmasiPostingan.php">Konfirmasi Postingan</a></li>
                    <?php }; ?>
                    <li><a class="dropdown-item " href="../catalog.php">Katalog Kendaraan</a></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <main class="mt-4">
          <div class="container">
            <h2 class="text-center">Konfirmasi Postingan</h2>
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
                    <a href="konfirmasiPostingan.php" class="btn btn-warning w-100">
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
                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                      </td>
                      <td class="text-center">
                      
                          <button class="btn btn-primary btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#buktiModal<?= $item['id'] ?>">
                            <i class="bi bi-info-circle"></i> Detail
                          </button>
                          <!-- <button class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#buktiModal<?= $item['id'] ?>">
                            <i class="bi bi-check-circle"></i> Konfirmasi
                          </button> -->
                          <a href="ubahStatusKendaraan.php?id=<?= $item['id'] ?>&status=1" class="btn btn-success btn-sm mb-1" onclick="return confirm('Yakin ingin konfirmasi kendaraan ini?')">
                          <i class="bi bi-check-circle"></i> Konfirmasi
                          </a>
                          <a href="ubahStatusKendaraan.php?id=<?= $item['id'] ?>&status=3" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin ingin tolak kendaraan ini?')">
                          <i class="bi bi-x-circle"></i> Tolak
                          </a>
                          <!-- <button class="btn btn-danger btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#buktiModal<?= $item['id'] ?>">
                            <i class="bi bi-x-circle"></i> Tolak
                          </button> -->
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>

              <!-- Modal Bukti Transfer -->
              <!-- Modal Bukti Transfer -->
<?php foreach ($kendaraan as $item): ?>
  <?php if (!empty($item['bukti_transfer'])): ?>
    <div class="modal fade" id="buktiModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="buktiModalLabel<?= $item['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="buktiModalLabel<?= $item['id'] ?>">Detail Kendaraan & Bukti Transfer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Gambar Kendaraan -->
              <div class="col-md-6 text-center">
                <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>" alt="Gambar Kendaraan" class="img-fluid rounded border mb-2" style="max-height: 300px;">
                <p class="text-muted">Gambar Kendaraan</p>
              </div>

              <!-- Bukti Transfer -->
              <div class="col-md-6 text-center">
                <img src="uploads/<?= htmlspecialchars($item['bukti_transfer']) ?>" alt="Bukti Transfer" class="img-fluid rounded border mb-2" style="max-height: 300px;">
                <p class="text-muted">Bukti Transfer</p>
              </div>
            </div>

            <!-- Informasi Detail Kendaraan -->
            <hr>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Nama:</strong> <?= htmlspecialchars($item['nama']) ?></p>
                <p><strong>Kategori:</strong> <?= htmlspecialchars($item['kategori']) ?></p>
                <p><strong>Merek:</strong> <?= htmlspecialchars($item['merek']) ?></p>
                <p><strong>Pemilik:</strong> <?= htmlspecialchars($item['nama_user']) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Tipe:</strong> <?= htmlspecialchars($item['tipe']) ?></p>
                <p><strong>Tahun:</strong> <?= htmlspecialchars($item['tahun']) ?></p>
                <p><strong>Harga:</strong> Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
              </div>
            </div>
            <p style="text-align: justify;"><strong>Deskripsi:</strong> <?= htmlspecialchars($item['deskripsi']) ?></p>
            
            <!-- Tombol Konfirmasi dan Tolak -->
            <hr>
            <div class="text-center">
              <a href="ubahStatusKendaraan.php?id=<?= $item['id'] ?>&status=1" class="btn btn-success btn-sm mb-1" onclick="return confirm('Yakin ingin konfirmasi kendaraan ini?')">
                          <i class="bi bi-check-circle"></i> Konfirmasi
                          </a>
                          <a href="ubahStatusKendaraan.php?id=<?= $item['id'] ?>&status=3" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin ingin tolak kendaraan ini?')">
                          <i class="bi bi-x-circle"></i> Tolak
                          </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endforeach; ?>



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
