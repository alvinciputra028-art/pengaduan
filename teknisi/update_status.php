<?php
$required_role = 'teknisi';
include '../auth/check_session.php';
include '../config/koneksi.php';

$id_teknisi = $_SESSION['id'];

/* ======================
   AMBIL PENGADUAN
====================== */
if (isset($_GET['aksi']) && $_GET['aksi'] == 'ambil') {

    $id = $_GET['id'];
    $status = 'Diproses';
    $status_menunggu = 'Menunggu';

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pengaduan 
        SET status = ?, id_teknisi = ?
        WHERE id_pengaduan = ?
        AND status = ?
    ");

    mysqli_stmt_bind_param($stmt, "siis", $status, $id_teknisi, $id, $status_menunggu);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: pengaduan_baru.php");
    exit;
}

/* ======================
   SELESAIKAN + UPLOAD
====================== */
if (isset($_POST['aksi']) && $_POST['aksi'] == 'selesai') {

    $id = $_POST['id'];
    $komentar = $_POST['komentar'];

    // upload bukti
    $allowed = ['jpg','jpeg','png'];
    $file = $_FILES['bukti_selesai'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "Format tidak valid!";
        exit;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        echo "File terlalu besar!";
        exit;
    }

    $new_name = time() . '_' . uniqid() . '.' . $ext;
    $path = '../uploads/bukti_penyelesaian/' . $new_name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        echo "Upload gagal!";
        exit;
    }

    // update DB
    $status = 'Selesai';

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pengaduan 
        SET status = ?, bukti_penyelesaian = ?, komentar_penyelesaian = ?
        WHERE id_pengaduan = ?
    ");

    mysqli_stmt_bind_param($stmt, "sssi", $status, $new_name, $komentar, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: diproses.php");
    exit;
}
?>