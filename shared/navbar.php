<?php
if (!isset($_SESSION)) {
    session_start();
}

$role = $_SESSION['role'] ?? '';

// halaman aktif
$page = $page ?? '';
?>

<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js"></script>

<div class="navbar">
    <!-- KIRI: LOGO + NAMA SISTEM -->
    <div class="nav-left">
        <img src="../assets/img/logo_rusunawa_untan.jpg">
        <span>Sistem Pengaduan Kendala Rusunawa UNTAN</span>
    </div>

    <!-- TENGAH: MENU -->
    <div id="navMenu" class="nav-menu">
        <?php if ($role == 'penghuni'): ?>
            <a href="../penghuni/beranda.php" class="<?= ($page == 'beranda') ? 'active' : '' ?>">Beranda</a>

            <a href="../penghuni/buat_pengaduan.php" class="<?= ($page == 'pengaduan') ? 'active' : '' ?>">
                Adukan</a>

            <a href="../penghuni/daftar_pengaduan.php" class="<?= ($page == 'daftar') ? 'active' : '' ?>">Daftar Pengaduan</a>

            <a href="../penghuni/riwayat.php" class="<?= ($page == 'riwayat') ? 'active' : '' ?>">Riwayat</a>

            <a href="../penghuni/profil.php" class="<?= ($page == 'profil') ? 'active' : '' ?>">Profil</a>

        <?php elseif ($role == 'teknisi'): ?>
            <a href="../teknisi/beranda.php" class="<?= ($page == 'beranda') ? 'active' : '' ?>">Beranda</a>

            <a href="../teknisi/pengaduan_baru.php" class="<?= ($page == 'baru') ? 'active' : '' ?>">Baru</a>

            <a href="../teknisi/diproses.php" class="<?= ($page == 'diproses') ? 'active' : '' ?>">Diproses</a>

            <a href="../teknisi/selesai.php" class="<?= ($page == 'selesai') ? 'active' : '' ?>">Selesai</a>

            <a href="../teknisi/profil.php" class="<?= ($page == 'profil') ? 'active' : '' ?>">Profil</a>

        <?php elseif ($role == 'manajer'): ?>
            <a href="../manajer/beranda.php" class="<?= ($page == 'beranda') ? 'active' : '' ?>">Beranda</a>

            <a href="../manajer/laporan.php" class="<?= ($page == 'laporan') ? 'active' : '' ?>">Laporan</a>

            <a href="../manajer/profil.php" class="<?= ($page == 'profil') ? 'active' : '' ?>">Profil</a>

        <?php endif; ?>

        <!-- <a href="../auth/logout.php" class="logout-mobile" onclick="return confirmLogout(event)">Logout</a> -->
        <a href="../auth/logout.php" class="logout-mobile btn-logout">Logout</a>
    </div>

    <!-- KANAN: LOGOUT -->
    <div class="nav-right">
        <!-- <a href="../auth/logout.php" id="logout" class="logout-desktop" onclick="return confirmLogout(event)"> -->
        <a href="../auth/logout.php" id="logout" class="logout-desktop btn-logout">
            <span class="logout-text">Logout</span>
            <span class="logout-icon">🚪</span>
        </a>
    </div>

    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<script>
    // toggle menu
    const hamburger = document.getElementById('hamburger');
    const menu1 = document.getElementById('navMenu');

    hamburger.addEventListener('click', function (e) {
        e.stopPropagation();
        menu1.classList.toggle('show');
        hamburger.classList.toggle('active');
    });

    // Otomatis tutup ketika klik area luar
    document.addEventListener('click', function (e) {
        if (!menu1.contains(e.target) && !hamburger.contains(e.target)) {
            menu1.classList.remove('show');
            hamburger.classList.remove('active');
        }
    });

    // Klik ikon "X" untuk menutup
    document.querySelectorAll('#navMenu a').forEach(link => {
        link.addEventListener('click', () => {
            menu1.classList.remove('show');
            hamburger.classList.remove('active');
        });
    });

</script>