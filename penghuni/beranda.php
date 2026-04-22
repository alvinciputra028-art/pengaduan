<?php
$required_role = 'penghuni';
$page = 'beranda';
include '../config/koneksi.php';
include '../auth/check_session.php';
include '../shared/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Beranda</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="banner">
        <img src="../assets/img/rusunputra.webp" width="33%">
        <img src="../assets/img/rusunputri.webp" width="33%">
        <img src="../assets/img/rusuninn.jpg" width="33%">
    </div>

    <div class="konten">
        <h2 id="sapa">Selamat datang,
            <?= htmlspecialchars($_SESSION['nama']); ?> 👋
        </h2>
        <hr><br>

        <h3>Panduan Singkat</h3>

        <div class="cards" id="panduan">

            <div class="card">
                Gunakan menu <strong>Pengaduan</strong> untuk melaporkan kendala.
            </div>

            <div class="card">
                Lihat pengaduan penghuni lain di menu <strong>Forum</strong>.
            </div>

            <div class="card">
                Pantau status pengaduan Anda di menu <strong>Riwayat</strong>.
            </div>

            <div class="card">
                Ubah nomor HP dan password di menu <strong>Profil</strong>.
            </div>

        </div>

        <br><hr>

        <p>Selamat menggunakan sistem ini! Semoga kendala Anda segera ditangani oleh teknisi kami 🙌</p>
    </div>
</body>
</html>