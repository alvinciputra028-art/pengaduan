<?php
session_start();

$error = "";

// jika password berhasil diubah
if (isset($_GET['msg']) && $_GET['msg'] == 1) {
    $error = "Password berhasil diubah. Silakan login kembali.";
}

// jika sudah login, arahkan ke index
if (isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}

// jika berhasil logout
if (isset($_GET['logout'])) {
    $error = "Anda berhasil logout!";
}

// ambil pesan error dari URL
if (isset($_GET['error'])) {
    if ($_GET['error'] == "1") {
        $error = "Password salah!";
    } elseif ($_GET['error'] == "2") {
        $error = "Akun tidak ditemukan!";
    } elseif ($_GET['error'] == "3") {
        $error = "Silakan login terlebih dahulu!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Sistem Pengaduan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            /* Gradient background */
            background: linear-gradient(135deg, #E2E2E2 0%, #0EA2BD 100%);
            height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- KIRI (LOGO) -->
        <div class="left">
            <h3>Sistem Pengaduan Kendala Rusunawa UNTAN</h3>
            <div class="logo-container">
                <img src="../assets/img/logo_rusunawa_untan.jpg" alt="Logo">
            </div>
        </div>

        <!-- KANAN (FORM) -->
        <div class="right">
            <div class="mobile-logo">
                <img src="../assets/img/logo_rusunawa_untan.jpg" alt="Logo">
            </div>

            <h3>Silakan Login!</h3>

            <?php if ($error != ""): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <br><br>

            <form method="POST" action="proses_login.php">
                <label>Email Student / Username</label>
                <div class="note">
                    Gunakan email student (penghuni) atau username (teknisi & manajer)
                </div>
                <input type="text" name="email_username" placeholder="Masukkan email/username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>