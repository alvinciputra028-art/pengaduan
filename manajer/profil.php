<?php
$required_role = 'manajer';
$page = 'profil';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$id = $_SESSION['id'];

// ambil data teknisi
$stmt = mysqli_prepare($koneksi, "SELECT * FROM manajer WHERE id_manajer = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profil Saya</title>
</head>

<body>
    <div class="konten">
        <h2 id="judul-h2">Profil Saya</h2>
        <p><strong>Nama:</strong></p>
        <div class="info">
            <p><?= $data['nama'] ?></p>
        </div>
        <br>

        <p><strong>Username:</strong></p>
        <div class="info">
            <p><?= $data['username'] ?></p>
        </div>
        <br>

        <!-- UBAH PASSWORD -->
        <a href="ubah_password.php">
            <button class="password">Ubah Password</button>
        </a>
    </div>
</body>

</html>