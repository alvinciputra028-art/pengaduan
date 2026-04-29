<?php
$required_role = 'teknisi';
$page = 'profil';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$id = $_SESSION['id'];

// ambil data teknisi
$stmt = mysqli_prepare($koneksi, "SELECT * FROM teknisi WHERE id_teknisi = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['update_hp'])) {
    $nomor_hp = $_POST['nomor_hp'];

    $stmt = mysqli_prepare($koneksi, "UPDATE teknisi SET nomor_hp = ? WHERE id_teknisi = ?");
    mysqli_stmt_bind_param($stmt, "si", $nomor_hp, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // refresh halaman biar data update
    header("Location: profil.php?success=1");
    exit;
}
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
            <p><?= $data['nama_teknisi'] ?></p>
        </div>
        <br>

        <p><strong>Username:</strong></p>
        <div class="info">
            <p><?= $data['username'] ?></p>
        </div>
        <br>

        <p><strong>Departemen Gedung:</strong></p>
        <div class="info">
            <p><?= $data['departemen_gedung'] ?></p>
        </div>
        <br>

        <p><strong>Nomor Handphone:</strong></p>
        <div class="info">
            <p><?= $data['nomor_hp'] ?></p>
        </div>
        <br><br>
        <hr><br>

        <!-- FORM EDIT NOMOR HP -->
        <p><strong>Edit Nomor HP</strong></p>
        <?php
        if (isset($_GET['success'])) {
            echo "<p style='color:green;'>Nomor HP berhasil diperbarui!</p>";
        }
        ?>

        <form method="POST">
            <input type="text" name="nomor_hp" value="<?= $data['nomor_hp'] ?>" required>
            <br><br>
            <button type="submit" name="update_hp">Simpan</button>
        </form>
        <br>

        <!-- UBAH PASSWORD -->
        <a href="ubah_password.php">
            <button class="password">Ubah Password</button>
        </a>
    </div>
</body>

</html>