<?php
$koneksi = mysqli_connect("localhost", "root", "", "complaint");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>