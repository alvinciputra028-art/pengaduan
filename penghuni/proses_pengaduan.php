<?php
$required_role = 'penghuni';
include '../auth/check_session.php';
include '../config/koneksi.php';

// ======================
// AMBIL & VALIDASI INPUT
// ======================
$id_penghuni = $_SESSION['id'];
$deskripsi = trim($_POST['deskripsi']);
$kategori = $_POST['kategori'] ?? '';

if (empty($deskripsi)) {
    echo "Deskripsi tidak boleh kosong!";
    exit;
}

// whitelist kategori (HARUS sesuai enum DB)
$allowed_kategori = ['Internet','Air','Elektronik','Bangunan','Listrik','Perabotan','Lainnya'];

if (!in_array($kategori, $allowed_kategori)) {
    echo "Kategori tidak valid!";
    exit;
}

/* ======================
   VALIDASI FILE
====================== */
if (!isset($_FILES['bukti_foto']) || $_FILES['bukti_foto']['error'] !== 0) {
    echo "File wajib diupload!";
    exit;
}

$allowed_ext = ['jpg', 'jpeg', 'png'];
$file_name = $_FILES['bukti_foto']['name'];
$file_tmp  = $_FILES['bukti_foto']['tmp_name'];
$file_size = $_FILES['bukti_foto']['size'];

$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed_ext)) {
    echo "Format file tidak valid!";
    exit;
}

if ($file_size > 2 * 1024 * 1024) {
    echo "Ukuran file terlalu besar!";
    exit;
}

/* ======================
   RENAME FILE
====================== */
$new_name = time() . '_' . uniqid() . '.' . $ext;
$upload_path = '../uploads/bukti_pengaduan/' . $new_name;

if (!move_uploaded_file($file_tmp, $upload_path)) {
    echo "Upload gagal!";
    exit;
}

/* ======================
   PREFIX TIKET
====================== */
$jenis_hunian = $_SESSION['jenis_hunian'];

if ($jenis_hunian == 'Rusun INN') {
    $prefix = 'INN';
} elseif ($jenis_hunian == 'Rusunawa Putra') {
    $prefix = 'TRA';
} elseif ($jenis_hunian == 'Rusunawa Putri') {
    $prefix = 'TRI';
} else {
    $prefix = 'UNK';
}

/* ======================
   GENERATE NOMOR TIKET
====================== */
$tanggal = date('Ymd');

// hitung jumlah tiket hari ini
$stmt = mysqli_prepare($koneksi, "
    SELECT COUNT(*) as total 
    FROM pengaduan 
    WHERE DATE(created_at) = CURDATE()
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_count = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$urutan = $data_count['total'] + 1;
$urutan_format = str_pad($urutan, 4, '0', STR_PAD_LEFT);

$nomor_tiket = $prefix . '-' . $tanggal . '-' . $urutan_format;

/* ======================
   INSERT DATA
====================== */
$status = 'Menunggu';

$stmt = mysqli_prepare($koneksi, "
    INSERT INTO pengaduan 
    (nomor_tiket, id_penghuni, id_teknisi, kategori, deskripsi, status, bukti_foto)
    VALUES (?, ?, NULL, ?, ?, ?, ?)
");

mysqli_stmt_bind_param($stmt, "sissss",
    $nomor_tiket,
    $id_penghuni,
    $kategori,
    $deskripsi,
    $status,
    $new_name
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/* ======================
   REDIRECT
====================== */
header("Location: riwayat.php");
exit;
?>