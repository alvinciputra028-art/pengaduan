<?php
$required_role = 'teknisi';
include '../auth/check_session.php';
include '../config/koneksi.php';

$id_teknisi = $_SESSION['id'];

/* Update pengaduan ketika peknisi ambil pengaduan*/
if (isset($_POST['aksi']) && $_POST['aksi'] == 'ambil') {
    $id = $_POST['id'];
    $status = 'Diproses';
    $status_menunggu = 'Menunggu';
    $dini = $_POST['dini'];

    if (empty($dini)) {
        $_SESSION['error'] = "Penanganan dini tidak boleh kosong";
        header("Location: ../shared/detail_pengaduan.php?id=$id&page=baru");
        exit;
    }

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pengaduan 
        SET status = ?, id_teknisi = ?, penanganan_dini = ?
        WHERE id_pengaduan = ?
        AND status = ?
    ");

    mysqli_stmt_bind_param($stmt, "sisis", $status, $id_teknisi, $dini, $id, $status_menunggu);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: pengaduan_baru.php");
    exit;
}

/* Menyelesaikan pengaduan dan upload file */
if (isset($_POST['aksi']) && $_POST['aksi'] == 'selesai') {
    $id = $_POST['id'];
    $komentar = $_POST['komentar'];

    $allowed = ['jpg', 'jpeg', 'png'];
    $file = $_FILES['bukti_selesai'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = "Format file harus JPG, JPEG, atau PNG";
        header("Location: ../shared/detail_pengaduan.php?id=$id&page=diproses");
        exit;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = "Ukuran file maksimal 2MB";
        header("Location: ../shared/detail_pengaduan.php?id=$id&page=diproses");
        exit;
    }

    $new_name = time() . '_' . uniqid() . '.' . $ext;
    $path = '../uploads/bukti_penyelesaian/' . $new_name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        $_SESSION['error'] = "Upload bukti penyelesaian gagal";
        header("Location: ../shared/detail_pengaduan.php?id=$id&page=diproses");
        exit;
    }

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