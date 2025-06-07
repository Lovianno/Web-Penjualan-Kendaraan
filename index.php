<?php
require 'config.php';

if (isset($_GET['submit'])) {
  header("Location: catalog.php?search=" . $_GET['search'] . "&kategori=");
  exit;
}
$bestSellers = getKendaraanRandom(3);


?>

<!DOCTYPE html>
<html class="main-body" lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Kendaraan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark " id="home">
    <div class="container">
      <a class="navbar-brand text-tiga judul" href="index.php">Toko Kendaraan</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav mx-auto gap-4">
          <a class="nav-link" aria-current="page" href="index.php">Home</a>
          <a class="nav-link" href="catalog.php">Catalog</a>
          <a class="nav-link" href="#about">About Us</a>
          <a class="nav-link" href="#contact">Contact Us</a>
          <?php
          if (isset($_SESSION['user'])) { ?>
            <a class="nav-link" href="admin/kendaraan.php">Master Kendaraan</a>

          <?php } ?>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <form class="d-flex" method="get" role="search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari Kendaraan..." aria-label="Search" name="search">
            <button class="btn btn-tiga" type="submit" name="submit">
              <i class="bi bi-search text-white"></i>
            </button>
          </div>
        </form>

        <?php if (!isset($_SESSION['user'])): ?>
          <a href="admin/login.php" class="btn btn-tiga ms-2">
            <i class="bi bi-box-arrow-in-right me-1 "></i> Login
          </a>
        <?php else: ?>
          <a href="admin/logout.php" class="btn btn-danger ms-2 text-light">
            <i class="bi bi-box-arrow-right me-1 text-white"></i> Logout
          </a>
        <?php endif; ?>
      </div>


    </div>
  </nav>

  <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner position-relative">
      <div class="carousel-item active" data-bs-interval="2000">
        <img src="assets/img/Carousel_1.jpg" class="d-block w-100" alt="..." style="height: 90vh; object-fit: cover;">
        <div class="carousel-caption d-none d-md-block position-absolute top-70 start-35 translate-middle">
          <h5 class="carousel-title1">Elegant and <span class="text-warning">classic</span></h5>
          <p class="carousel-text1">cari unit yang menggambarkan dirimu</p>
        </div>
      </div>
      <div class="carousel-item " data-bs-interval="2000">
        <img src="assets/img/Carousel_2.jpg" class="d-block w-100" alt="..." style="height: 90vh; object-fit: cover;">
        <div class="carousel-caption d-none d-md-block position-absolute top-5 start-50 translate-middle">
          <h5 class="carousel-title2">fast & <span class="text-danger">classic</span></h5>
        </div>
      </div>
      <div class="carousel-item " data-bs-interval="2000">
        <img src="assets/img/Carousel_3.jpg" class="d-block w-100" alt="..." style="height: 90vh; object-fit: cover;">
        <div class="carousel-caption d-none d-md-block position-absolute top-50 start-50 translate-middle">
          <h5 class="carousel-title3">Fast <span class="text-dark">and</span> <span class="text-danger">dangerous</span></h5>
        </div>
      </div>
    </div>
  </div>
  <section class="container mb-5 mt-5 pt-5 pb-5">
    <div class="mb-5 text-center">
      <h2 class="text-empat text-racing">Rekomendasi Unit</h2>
      <p class="text-empat">Berikut beberapa rekomendasi unit</p>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($bestSellers as $kendaraan): ?>
        <div class="col">
          <div class="card h-100 card-box-shadow">
            <img src="admin/uploads/<?= $kendaraan['gambar'] ?>" class="card-img-top" style="object-fit: cover; height: 200px;" alt="<?= $kendaraan['nama'] ?>">
            <div class="card-body">
              <h5 class="card-title text-tiga text-racing"><?= $kendaraan['merek'] . ' ' . $kendaraan['nama'] . ' ' . $kendaraan['tahun'] ?></h5>
              <p class="card-text"><?= substr($kendaraan['deskripsi'], 0, 100) ?>...</p>
              <p class="card-text text-empat fw-bold">Rp<?= number_format($kendaraan['harga'], 0, ',', '.') ?></p>
              <a href="detail.php?id=<?= $kendaraan['id'] ?>" class="btn btn-tiga btn-md">Lihat Detail</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="about-section" id="about">
    <div class="container-card">
      <h2 class="about-title text-racing text-empat">Tentang Kami</h2>
      <p class="about-description text-empat">
        Kami adalah penyedia layanan jual beli mobil terpercaya yang berkomitmen untuk memberikan kendaraan berkualitas dengan harga terbaik.
      </p>
      <div class="row">
        <div class="col-md-4">
          <div class="about-card h-100 card-tiga">
            <i class="text-dua fas fa-bullseye about-icon mission-icon"></i>
            <h3 class="about-card-title text-racing text-dua">Misi</h3>
            <p class="about-card-text text-dua">
              Menyediakan mobil berkualitas dengan pelayanan profesional dan transparan demi kepuasan pelanggan.
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="about-card h-100 card-tiga">
            <i class="text-dua fas fa-eye about-icon vision-icon"></i>
            <h3 class="about-card-title text-racing text-dua">Visi</h3>
            <p class="about-card-text text-dua">
              Menjadi platform jual beli mobil terpercaya dan terdepan di Indonesia.
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="about-card h-100 card-tiga">
            <i class="text-dua fas fa-star about-icon value-icon"></i>
            <h3 class="about-card-title text-racing text-dua">Nilai</h3>
            <p class="about-card-text text-dua">
              Integritas, Keamanan transaksi, dan Kepuasan pelanggan dalam setiap layanan kami.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <div class="container my-5">
    <div class="custom-contact-wrapper" id="contact">
      <h2 class="contact-title text-racing text-empat text-center">Contact Kami</h2>
      <section class="py-5 px-3 px-md-5">
        <div class="container">
          <div class="row g-5 align-items-stretch">
            <div class="col-lg-6 d-flex flex-column">
              <div class="form-box p-4 p-md-5 rounded-4 shadow-lg bg-tiga">
                <form class="" action="">
                  <div class="mb-3">
                    <label class="form-label text-dua">Nama</label>
                    <input type="text" class="form-control bg-dua" placeholder="Nama Anda" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-dua">Email</label>
                    <input type="email" class="form-control bg-dua" placeholder="email@example.com" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-dua">Pesan</label>
                    <textarea class="form-control bg-dua" rows="5" placeholder="Tulis pesan Anda di sini." required></textarea>
                  </div>
                  <button type="submit" class="btn btn-dua w-100">Kirim Pesan</button>
                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex flex-column justify-content-between text-white">
              <p class="text-empat"><i class="fas fa-envelope me-2 text-tiga"></i> tokokendaraan@gmail.com</p>
              <p class="text-empat"><i class="fas fa-phone me-2 text-tiga"></i> +62-838-555-364</p>
              <p class="text-empat"><i class="fas fa-map-marker-alt me-2 text-tiga"></i> Jl. Rungkut Madya, Gn. Anyar, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294</p>
              <div class="ratio ratio-16x9 mt-3 rounded overflow-hidden">
                <iframe src="https://www.google.com/maps/embed?pb=..." allowfullscreen="" loading="lazy"></iframe>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <footer class="footer text-light pt-5 pb-4" id="footer">
    <div class="container text-center text-md-start">
      <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4">
          <h2 class="text-uppercase text-racing fw-bold text-tiga">Toko Kendaraan</h2>
          <p class="text-empat" style="text-align: justify;">
            Selamat datang di Toko Kendaraan – solusi terpercaya untuk jual beli mobil baru dan bekas berkualitas! Kami menawarkan beragam pilihan kendaraan dari berbagai merek ternama, dengan proses transaksi yang aman, harga bersaing, dan layanan ramah pelanggan. Temukan mobil impian Anda bersama kami!
          </p>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4">
          <h2 class="text-uppercase text-racing fw-bold text-tiga">Navigasi</h2>
          <ul class="list-unstyled">
            <li class="text-empat"><a href="#home" class="footer-link">Home</a></li>
            <li class="text-empat"><a href="catalog.php" class="footer-link">Katalog</a></li>
            <li class="text-empat"><a href="#about" class="footer-link">Tentang Kami</a></li>
            <li class="text-empat"><a href="#contact" class="footer-link">Kontak</a></li>
          </ul>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4">
          <h2 class="text-uppercase text-racing fw-bold text-tiga">Hubungi Kami</h2>
          <p class="text-empat mb-2">Jl. Rungkut Madya, Gn. Anyar, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294</p>
          <p class="text-empat mb-2">info@tokokendaraan.com</p>
          <p class="text-empat mb-2">+62-838-555-364</p>
        </div>
        <hr class="mb-4 text-secondary">
        <div class="row">
          <div class="copyright text-center text-empat ">
            &copy; 2025 Toko Kendaraan | All Rights Reserved
          </div>
        </div>
      </div>
    </div>
  </footer>


  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="assets/js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>