<?php
$required_role = 'teknisi';
$page = 'beranda';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$id_teknisi = $_SESSION['id'];
$nama = $_SESSION['nama'];
$departemen = $_SESSION['departemen_gedung'];

// total pengaduan di gedung teknisi
$stmt = mysqli_prepare($koneksi, "
    SELECT COUNT(*) as total 
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    WHERE ph.jenis_hunian = ?
");
mysqli_stmt_bind_param($stmt, "s", $departemen);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = mysqli_fetch_assoc($result)['total'];
mysqli_stmt_close($stmt);

// pengaduan baru
$status_menunggu = 'Menunggu';

$stmt = mysqli_prepare($koneksi, "
    SELECT COUNT(*) as total 
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    WHERE p.status = ?
    AND ph.jenis_hunian = ?
");
mysqli_stmt_bind_param($stmt, "ss", $status_menunggu, $departemen);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$baru = mysqli_fetch_assoc($result)['total'];
mysqli_stmt_close($stmt);

// sedang diproses oleh teknisi ini
$status_diproses = 'Diproses';

$stmt = mysqli_prepare($koneksi, "
    SELECT COUNT(*) as total 
    FROM pengaduan
    WHERE status = ?
    AND id_teknisi = ?
");
mysqli_stmt_bind_param($stmt, "si", $status_diproses, $id_teknisi);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$diproses = mysqli_fetch_assoc($result)['total'];
mysqli_stmt_close($stmt);

// selesai oleh teknisi ini
$status_selesai = 'Selesai';

$stmt = mysqli_prepare($koneksi, "
    SELECT COUNT(*) as total 
    FROM pengaduan
    WHERE status = ?
    AND id_teknisi = ?
");
mysqli_stmt_bind_param($stmt, "si", $status_selesai, $id_teknisi);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$selesai = mysqli_fetch_assoc($result)['total'];
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Beranda Teknisi</title>
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
                Gunakan menu <strong>Baru</strong> untuk menindaklanjuti pengaduan baru.
            </div>

            <div class="card">
                Jangan lupa untuk menyelesaikan pengaduan di menu <strong>Diproses</strong>.
            </div>

            <div class="card">
                Anda dapat melihat pengaduan yang sudah diselesaikan di menu <strong>Selesai</strong>.
            </div>

            <div class="card">
                Ubah nomor HP dan password di menu <strong>Profil</strong>.
            </div>
        </div>
        <br><hr><br>

        <h3>Ringkasan Pengaduan</h3>

        <div class="cards">

            <div class="card">
                <h4>Total Pengaduan</h4>
                <p><strong>
                        <?= $total ?>
                    </strong></p>
            </div>

            <div class="card">
                <h4>Pengaduan Baru</h4>
                <p style="color: orange;"><strong>
                        <?= $baru ?>
                    </strong></p>
            </div>

            <div class="card">
                <h4>Sedang Diproses</h4>
                <p style="color: blue;"><strong>
                        <?= $diproses ?>
                    </strong></p>
            </div>

            <div class="card">
                <h4>Selesai</h4>
                <p style="color: green;"><strong>
                        <?= $selesai ?>
                    </strong></p>
            </div>
        </div>
        <br><hr>
        <p>Selamat menggunakan sistem ini! Semoga penyelesaian kendala teknis di lapangan berjalan dengan lancar ya 🙌</p>
</body>

</html>