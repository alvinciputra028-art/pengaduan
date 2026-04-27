<?php
session_start();
include '../config/koneksi.php';

// ambil input
$email_username = $_POST['email_username'];
$password = $_POST['password'];

// 1. CEK KE TABEL PENGHUNI
$stmt = mysqli_prepare($koneksi, "SELECT * FROM penghuni WHERE email_student = ?");
mysqli_stmt_bind_param($stmt, "s", $email_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_penghuni = mysqli_fetch_assoc($result);

if ($data_penghuni) {
    if (password_verify($password, $data_penghuni['password'])) {

        $_SESSION['id'] = $data_penghuni['id_penghuni'];
        $_SESSION['role'] = 'penghuni';
        $_SESSION['nama'] = $data_penghuni['nama_penghuni'];
        $_SESSION['jenis_hunian'] = $data_penghuni['jenis_hunian'];

        header("Location: ../penghuni/beranda.php");
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
}

mysqli_stmt_close($stmt);

// 2. CEK KE TABEL TEKNISI
$stmt = mysqli_prepare($koneksi, "SELECT * FROM teknisi WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $email_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_teknisi = mysqli_fetch_assoc($result);

if ($data_teknisi) {
    if (password_verify($password, $data_teknisi['password'])) {

        $_SESSION['id'] = $data_teknisi['id_teknisi'];
        $_SESSION['role'] = 'teknisi';
        $_SESSION['nama'] = $data_teknisi['nama_teknisi'];
        $_SESSION['departemen_gedung'] = $data_teknisi['departemen_gedung'];

        header("Location: ../teknisi/beranda.php");
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
}

mysqli_stmt_close($stmt);

// 3. CEK KE TABEL MANAGER
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM manajer WHERE username = ?
");
mysqli_stmt_bind_param($stmt, "s", $email_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_manager = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($data_manager) {
    if (password_verify($password, $data_manager['password'])) {

        $_SESSION['id'] = $data_manager['id_manajer'];
        $_SESSION['role'] = 'manajer';
        $_SESSION['nama'] = $data_manager['nama'];

        header("Location: ../manajer/beranda.php");
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
}

// JIKA TIDAK DITEMUKAN
header("Location: login.php?error=2");
exit;
?>