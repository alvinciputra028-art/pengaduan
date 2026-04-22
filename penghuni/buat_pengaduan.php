<?php
$required_role = 'penghuni';
$page = 'pengaduan';
include '../auth/check_session.php';
include '../shared/navbar.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Buat Pengaduan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="konten">
        <h2 id="judul-h2">Buat Pengaduan Kendala</h2>

        <form action="proses_pengaduan.php" method="POST" enctype="multipart/form-data">

            <label>Kategori:</label><br>
            <select name="kategori" required>
                <option value="" disabled selected>-- Pilih Kategori --</option>
                <option value="Internet">Internet</option>
                <option value="Air">Air</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Bangunan">Bangunan</option>
                <option value="Listrik">Listrik</option>
                <option value="Perabotan">Perabotan</option>
                <option value="Lainnya">Lainnya</option>
            </select><br><br><br>

            <label>Deskripsi Kendala:</label><br>
            <textarea name="deskripsi" rows="5" cols="40" placeholder="Jelaskan kendala yang dialami dengan sejelas mungkin lengkap dengan lokasinya" required></textarea><br><br>

            <label>Upload Bukti Gambar (JPG, JPEG, PNG Maks 2MB):</label><br>
            <input type="file" name="bukti_foto" accept="image/*" required><br><br>

            <button type="submit">Kirim Pengaduan</button>

        </form>

    </div>
</body>

</html>