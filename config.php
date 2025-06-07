<?php
$conn = mysqli_connect("localhost", "root", "", "toko_kendaraan");
session_start();

function getKendaraan()
{
    global $conn;
    $result = [];
    $query = mysqli_query($conn, "SELECT * FROM kendaraan");
    while ($data = mysqli_fetch_assoc($query)) {
        $result[] = $data;
    }
    return $result;
}

function getKendaraanRandom($limit = 3)
{
    global $conn;
    $result = [];
    $query = mysqli_query($conn, "SELECT * FROM kendaraan ORDER BY RAND() LIMIT $limit");
    while ($data = mysqli_fetch_assoc($query)) {
        $result[] = $data;
    }
    return $result;
}

function getKategori()
{
    return ['Motor', 'Mobil', 'Truk'];
}

function simpanKendaraan($data, $file)
{
    global $conn;

    $nama = $data['nama'];
    $kategori = $data['kategori'];
    $merek = $data['merek'];
    $tipe = $data['tipe'];
    $tahun = $data['tahun'];
    $deskripsi = $data['deskripsi'];
    $harga = $data['harga'];
    $status = $data['status'];
    $user_id = $data['user_id'];
    $gambar = $file['gambar']['name'];
    $tmp = $file['gambar']['tmp_name'];
    $size = $file['gambar']['size'];
    $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024;

    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Format gambar tidak valid.'];
    }

    if ($size > $maxSize) {
        return ['success' => false, 'message' => 'Ukuran gambar maksimal 2MB.'];
    }

    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = uniqid() . '.' . $ext;
    $path = $uploadDir . $filename;

    if (!move_uploaded_file($tmp, $path)) {
        return ['success' => false, 'message' => 'Gagal upload gambar.'];
    }

    $query = "INSERT INTO kendaraan (nama, kategori, merek, tipe, tahun, deskripsi, gambar, harga, status, user_id) 
              VALUES ('$nama', '$kategori', '$merek', '$tipe', '$tahun', '$deskripsi', '$filename', '$harga', '$status', '$user_id')";

    if (mysqli_query($conn, $query)) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => mysqli_error($conn)];
    }
}

function getDetailKendaraan($id)
{
    global $conn;
    $data = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id = '$id'");
    $kendaraan = mysqli_fetch_assoc($data);

    if (!$kendaraan) {
        echo "<script>alert('Data tidak ditemukan'); window.location='kendaraan.php';</script>";
        exit;
    }
    return $kendaraan;
}

function updateKendaraan($id, $data, $file, $gambarLama)
{
    global $conn;

    $nama = $data['nama'];
    $kategori = $data['kategori'];
    $merek = $data['merek'];
    $tipe = $data['tipe'];
    $tahun = $data['tahun'];
    $deskripsi = $data['deskripsi'];
    $harga = $data['harga'];
    $status = $data['status'];

    $uploadDir = "uploads/";
    $gambarBaru = $gambarLama;

    if (!empty($file['gambar']['name'])) {
        $gambar = $file['gambar']['name'];
        $tmp = $file['gambar']['tmp_name'];
        $size = $file['gambar']['size'];
        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($ext, $allowed)) {
            return ['success' => false, 'message' => 'Format gambar tidak valid (jpg, jpeg, png).'];
        }

        if ($size > $maxSize) {
            return ['success' => false, 'message' => 'Ukuran gambar maksimal 2MB.'];
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . '.' . $ext;
        $path = $uploadDir . $filename;

        if (!move_uploaded_file($tmp, $path)) {
            return ['success' => false, 'message' => 'Gagal upload gambar.'];
        }

        if (file_exists($uploadDir . $gambarLama)) {
            unlink($uploadDir . $gambarLama);
        }

        $gambarBaru = $filename;
    }

    $query = "UPDATE kendaraan SET 
                nama = '$nama', 
                kategori = '$kategori',
                merek = '$merek',
                tipe = '$tipe',
                tahun = '$tahun',
                deskripsi = '$deskripsi',
                gambar = '$gambarBaru',
                harga = '$harga', 
                status = '$status'
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => mysqli_error($conn)];
    }
}

function hapusKendaraan($id)
{
    global $conn;
    $query = "DELETE FROM kendaraan WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus'); window.location='kendaraan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "'); window.location='kendaraan.php';</script>";
    }
}

function getKendaraanFiltered($kategori = '', $search = '', $limit = 10, $offset = 0, $user_id = 0,)
{
    global $conn;
    if ($user_id == 0) {
        $query = "SELECT * FROM kendaraan WHERE 1=1";
    } else {
        $query = "SELECT * FROM kendaraan WHERE 1=1 AND user_id=" . $user_id;
    }

    // Filter kategori jika dipilih
    if ($kategori) {
        $kategori = mysqli_real_escape_string($conn, $kategori);
        $query .= " AND kategori = '$kategori'";
    }

    // Pencarian nama atau merek
    if ($search) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " AND (nama LIKE '%$search%' OR merek LIKE '%$search%')";
    }

    // Tambahkan limit dan offset untuk paginasi
    $query .= " ORDER BY nama ASC LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

function countKendaraanFiltered($kategori = '', $search = '', $user_id = 0)
{
    global $conn;
    if ($user_id == 0) {
        $query = "SELECT COUNT(*) as total FROM kendaraan WHERE 1=1";
    } else {
        $query = "SELECT COUNT(*) as total FROM kendaraan WHERE 1=1 AND user_id=" . $user_id;
    }


    if ($kategori) {
        $query .= " AND kategori = '" . mysqli_real_escape_string($conn, $kategori) . "'";
    }

    if ($search) {
        $query .= " AND nama LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
    }

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}




function loginUser($email, $password)
{
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['user'] = $user;
        return ['success' => true];
    }

    return ['success' => false, 'message' => 'Email atau password salah!'];
}
function register($data)
{
    global $conn;

    $nama = $data['nama'];
    $email = $data['email'];
    $password = hash('SHA256', $data['password']);
    $no_tlp = $data['no_telepon'];

    $query = "INSERT INTO user (nama, email, password, no_telepon) 
              VALUES ('$nama', '$email', '$password', '$no_tlp')";

    if (mysqli_query($conn, $query)) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => mysqli_error($conn)];
    }
}
function getNoTeleponByKendaraanId($kendaraanId)
{
    global $conn;
    $sql = "SELECT u.no_telepon 
            FROM kendaraan k
            JOIN user u ON k.user_id = u.id
            WHERE k.id = ?";
    $noTelepon = 0;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kendaraanId);
    $stmt->execute();
    $stmt->bind_result($noTelepon);

    if ($stmt->fetch()) {
        return $noTelepon;
    } else {
        return null;
    }
}
