<?php
session_start();

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'penghuni') {
        header("Location: penghuni/beranda.php");
        exit;
    } elseif ($_SESSION['role'] === 'teknisi') {
        header("Location: teknisi/beranda.php");
        exit;
    } elseif ($_SESSION["role"] === "manajer") {
        header("Location: manajer/beranda.php");
        exit;
    }
}

header("Location: auth/login.php");
exit;
?>