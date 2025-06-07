<?php
include 'config.php';

if (isset($_SESSION['user'])) {
    echo "<script>window.history.back();</script>";
}

$error = '';
if (isset($_POST['submit'])) {
    $hasil = register($_POST);

    if ($hasil['success']) {
        echo "<script>alert('Berhasil Daftar'); window.location='admin/login.php';</script>";
    } else {
        echo "<script>alert('Gagal: {$hasil['message']}'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Toko Kendaraan - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e0e0e0);
        }

        .card {
            border: none;
            border-radius: 1rem;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 0.5rem;
        }

        .btn-primary {
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center" style="height: 100vh;">

    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <i class="bi bi-car-front-fill fs-1 text-primary"></i>
            <h4 class="mt-2">Login ke Toko Kendaraan App</h4>
            <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" name="nama" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" class="form-control" name="email" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">No Telepon</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="number" class="form-control" name="no_telepon" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-success" name="submit" type="submit">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Daftar
                </button>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </form>
    </div>

</body>

</html>