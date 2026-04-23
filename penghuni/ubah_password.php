<?php
$required_role = 'penghuni';
$page = 'profil';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$id = $_SESSION['id'];
$error = "";

// ambil password lama dari DB
$stmt = mysqli_prepare($koneksi, "SELECT password FROM penghuni WHERE id_penghuni = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['ubah_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    // cek password lama
    if (!password_verify($password_lama, $data['password'])) {
        $error = "Password lama salah!";
    }
    // cek konfirmasi
    elseif ($password_baru !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    }
    // cek panjang minimal
    elseif (strlen($password_baru) < 8) {
        $error = "Password minimal 8 karakter!";
    } else {
        // hash password baru
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);

        // update ke DB
        $stmt = mysqli_prepare($koneksi, " UPDATE penghuni SET password = ? WHERE id_penghuni = ?");
        mysqli_stmt_bind_param($stmt, "si", $hash, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // LOGOUT OTOMATIS
        session_unset();
        session_destroy();

        // redirect ke login
        header("Location: ../auth/login.php?msg=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ubah Password</title>
</head>

<body>
    <div class="konten">
        <h2 id="judul-h2">Ubah Password</h2>

        <!-- PESAN ERROR -->
        <?php if ($error != ""): ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Password Lama:</label><br>
            <input type="password" name="password_lama" required><br><br>

            <label>Password Baru:</label><br>
            <input type="password" name="password_baru" required><br><br>

            <label>Konfirmasi Password Baru:</label><br>
            <input type="password" name="konfirmasi" required><br><br>

            <button type="submit" name="ubah_password">Simpan</button>
        </form>
        <br>
        <a href="profil.php">Kembali ke Profil</a>
    </div>
</body>

</html>