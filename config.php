<?php
$conn = mysqli_connect("localhost", "root", "", "toko_kendaraan");
session_start();

 $statusLabels = [
      0 => ['label' => 'Menunggu Konfirmasi', 'badge' => 'warning'],
      1 => ['label' => 'Aktif', 'badge' => 'success'],
      2 => ['label' => 'Nonaktif', 'badge' => 'secondary'],
      3 => ['label' => 'Ditolak', 'badge' => 'danger'],
    ];


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
    $query = mysqli_query($conn, "SELECT * FROM kendaraan WHERE status = 1   ORDER BY RAND() LIMIT $limit");
    while ($data = mysqli_fetch_assoc($query)) {
        $result[] = $data;
    }
    return $result;
}

function getKategori()
{
    return ['Motor', 'Mobil', 'Truk'];
}
function getDetailKendaraan($id) {
    global $conn;

    $id = (int)$id; // Pastikan ID berupa angka untuk keamanan

    $query = "
        SELECT kendaraan.*, user.nama AS nama_user, user.no_telepon 
        FROM kendaraan 
        JOIN user ON kendaraan.user_id = user.id 
        WHERE kendaraan.id = $id
    ";

    $data = mysqli_query($conn, $query);

    $kendaraan = mysqli_fetch_assoc($data);

    if (!$kendaraan) {
        echo "<script>alert('Data tidak ditemukan'); window.location='kendaraan.php';</script>";
        exit;
    }

    return $kendaraan;
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
    $status = 0;
    $user_id = $data['user_id'];

    // Upload gambar kendaraan
    $gambar = $file['gambar']['name'];
    $tmp = $file['gambar']['tmp_name'];
    $size = $file['gambar']['size'];
    $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    // Upload bukti transfer
    $bukti = $file['bukti_transfer']['name'];
    $tmp_bukti = $file['bukti_transfer']['tmp_name'];
    $size_bukti = $file['bukti_transfer']['size'];
    $ext_bukti = strtolower(pathinfo($bukti, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024;
    $uploadDir = "uploads/";

    if (!in_array($ext, $allowed) || !in_array($ext_bukti, $allowed)) {
        return ['success' => false, 'message' => 'Format gambar atau bukti transfer tidak valid.'];
    }

    if ($size > $maxSize || $size_bukti > $maxSize) {
        return ['success' => false, 'message' => 'Ukuran gambar atau bukti transfer maksimal 2MB.'];
    }

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename_gambar = uniqid() . '.' . $ext;
    $filename_bukti = uniqid() . '_bukti.' . $ext_bukti;

    if (!move_uploaded_file($tmp, $uploadDir . $filename_gambar) || 
        !move_uploaded_file($tmp_bukti, $uploadDir . $filename_bukti)) {
        return ['success' => false, 'message' => 'Gagal upload gambar atau bukti transfer.'];
    }

    $query = "INSERT INTO kendaraan 
        (nama, kategori, merek, tipe, tahun, deskripsi, gambar, harga, status, user_id, bukti_transfer) 
        VALUES 
        ('$nama', '$kategori', '$merek', '$tipe', '$tahun', '$deskripsi', '$filename_gambar', '$harga', '$status', '$user_id', '$filename_bukti')";

    if (mysqli_query($conn, $query)) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => mysqli_error($conn)];
    }
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
    
    if($status = $data['status'] == null){
        $status = 0;
    }
    else{

        $status = $data['status'];
    }

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

function ubahStatusKendaraan($status, $id) {
    // Pastikan koneksi tersedia
    global $conn;

    // Escape input untuk keamanan
    $status = (int)$status;
    $id = (int)$id;

    // Query update status
    $query = "UPDATE kendaraan SET status = $status WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        return mysqli_affected_rows($conn) > 0;
    } else {
        // Log error jika gagal
        error_log("Gagal ubah status kendaraan: " . mysqli_error($conn));
        return false;
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

function getKendaraanFiltered($kategori = '', $search = '', $limit = 10, $offset = 0, $user_id = 0, $status = null)
{
    global $conn;

    $query = "SELECT kendaraan.*, user.nama AS nama_user, user.no_telepon 
              FROM kendaraan 
              JOIN user ON kendaraan.user_id = user.id 
              WHERE 1=1";

    // Filter berdasarkan user_id jika diberikan
    if ($user_id != 0) {
        $query .= " AND kendaraan.user_id = " . (int)$user_id;
    }

    // Filter status jika tidak null
    if (!is_null($status)) {
        $query .= " AND kendaraan.status = " . (int)$status;
    }

    // Filter kategori jika dipilih
    if ($kategori) {
        $kategori = mysqli_real_escape_string($conn, $kategori);
        $query .= " AND kendaraan.kategori = '$kategori'";
    }

    // Pencarian nama atau merek
    if ($search) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " AND (kendaraan.nama LIKE '%$search%' OR kendaraan.merek LIKE '%$search%')";
    }

    // Tambahkan limit dan offset untuk paginasi
    $query .= " ORDER BY kendaraan.nama ASC LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}




function countKendaraanFiltered($kategori = '', $search = '', $user_id = 0, $status = null)
{
    global $conn;

    // Query dasar
    if ($user_id == 0) {
        $query = "SELECT COUNT(*) as total FROM kendaraan WHERE 1=1";
    } else {
        $query = "SELECT COUNT(*) as total FROM kendaraan WHERE user_id = " . (int)$user_id;
    }

    // Filter status jika tidak null
    if (!is_null($status)) {
        $query .= " AND status = " . (int)$status;
    }

    // Filter kategori jika dipilih
    if (!empty($kategori)) {
        $kategori = mysqli_real_escape_string($conn, $kategori);
        $query .= " AND kategori = '$kategori'";
    }

    // Pencarian nama atau merek
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " AND (nama LIKE '%$search%' OR merek LIKE '%$search%')";
    }

    // Eksekusi query dan ambil hasil
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
    $role = 2;

    $query = "INSERT INTO user (nama, role, email, password, no_telepon) 
              VALUES ('$nama', '$role' ,'$email', '$password', '$no_tlp')";

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
