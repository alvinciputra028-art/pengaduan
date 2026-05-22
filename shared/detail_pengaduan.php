<?php
$page = $_GET['page'] ?? 'beranda';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

// VALIDASI ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID pengaduan tidak valid!";
    header("Location: ../$role/beranda.php");
    exit;
}

$id = $_GET['id'];

// AMBIL DATA PENGADUAN
$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, ph.nama_penghuni, ph.kamar, ph.nomor_hp AS hp_penghuni, ph.jenis_hunian, t.nama_teknisi, t.nomor_hp AS hp_teknisi
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    LEFT JOIN teknisi t ON p.id_teknisi = t.id_teknisi
    WHERE p.id_pengaduan = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// DATA TIDAK DITEMUKAN
if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Data pengaduan tidak ditemukan!";
    header("Location: ../$role/$page.php");
    exit;
}

$data = mysqli_fetch_assoc($result);

// VALIDASI AKSES PENGHUNI
if ($role == 'penghuni') {
    $user_gedung = $_SESSION['jenis_hunian'];
    // hanya boleh lihat milik sendiri atau forum gedung yang sama
    if (
        $data['id_penghuni'] != $user_id &&
        $data['jenis_hunian'] != $user_gedung
    ) {
        $_SESSION['error'] = "Akses ditolak!";
        header("Location: ../penghuni/forum.php");
        exit;
    }
}

// VALIDASI AKSES TEKNISI
if ($role == 'teknisi') {
    // hanya boleh lihat jika sesuai gedung atau dia yang menangani
    if (
        $data['jenis_hunian'] != $_SESSION['departemen_gedung'] &&
        $data['id_teknisi'] != $user_id
    ) {
        $_SESSION['error'] = "Akses ditolak!";
        header("Location: ../teknisi/beranda.php");
        exit;
    }
}

// STATUS CLASS
$status_class = '';

if ($data['status'] == 'Menunggu') {
    $status_class = 'menunggu';
} elseif ($data['status'] == 'Diproses') {
    $status_class = 'diproses';
} else {
    $status_class = 'selesai';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pengaduan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>
</head>

<body>
    <!-- MODAL GAMBAR -->
    <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <div class="konten">
        <h2 id="judul-h2">Detail Pengaduan</h2>
        
        <!-- ERROR MESSAGE -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card-forum">
            <!-- HEADER -->
            <div class="card-header">
                <div class="ticket">
                    <?= htmlspecialchars($data['nomor_tiket']) ?>
                </div>

                <?php if ($role == 'teknisi' || $role == 'manager'): ?>
                    <div>
                        <?= htmlspecialchars($data['nama_penghuni']) ?> - 
                        Kamar <?= htmlspecialchars($data['kamar']) ?> - 
                        No.HP: <?= htmlspecialchars($data['hp_penghuni']) ?>
                    </div>
                <?php endif; ?>

                <?php if ($role == 'penghuni' || $role == 'manajer'): ?>
                    <div>
                        <?php if (!empty($data['nama_teknisi'])): ?>
                            <strong>Ditangani oleh: </strong>
                            <?= htmlspecialchars($data['nama_teknisi']) ?> -
                            <?= htmlspecialchars($data['hp_teknisi']) ?><br>
                            <strong>Selesai pada: </strong>
                            <?= htmlspecialchars($data['updated_at']) ?>
                        <?php else: ?>
                            <i>Belum ditangani</i>
                        <?php endif; ?>
                    </div><br>
                <?php endif; ?>

                <div class="meta">
                    <strong>Diadukan pada: </strong>
                    <?= htmlspecialchars($data['created_at']) ?>
                </div>
            </div>

            <!-- STATUS -->
            <span class="status <?= $status_class ?>">
                <?= htmlspecialchars($data['status']) ?>
            </span><br><br>

            <!-- KATEGORI -->
            <div>
                <strong>Kategori:</strong>
                <?= htmlspecialchars($data['kategori']) ?>
            </div><br>

            <!-- DESKRIPSI -->
            <div class="deskripsi">
                <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
            </div><br>

            <!-- BUKTI PENGADUAN -->
            <?php if ($data['bukti_foto']): ?>
                <div>
                    <a href="#" class="lihat-gambar" data-src="../uploads/bukti_pengaduan/<?= $data['bukti_foto'] ?>">
                        Lihat Bukti Pengaduan
                    </a>
                </div>
            <?php endif; ?>

            <!-- BUKTI PENYELESAIAN -->
            <?php if ($data['bukti_penyelesaian']): ?>
                <br>
                <div>
                    <a href="#" class="lihat-gambar"
                        data-src="../uploads/bukti_penyelesaian/<?= $data['bukti_penyelesaian'] ?>">
                        Lihat Bukti Penyelesaian
                    </a>
                </div>
            <?php endif; ?>

            <!-- PENANGANAN DINI -->
            <?php if (!empty($data['penanganan_dini'])): ?>
                <br><hr><br>
                <div class="komentar-box">
                    <strong>Penanganan Dini:</strong>
                    <br><br>
                    <?= nl2br(htmlspecialchars($data['penanganan_dini'])) ?>
                </div>
            <?php endif; ?>

            <!-- KOMENTAR PENYELESAIAN -->
            <?php if (!empty($data['komentar_penyelesaian'])): ?>
                <br><br>
                <div class="komentar-box">
                    <strong>Komentar Penyelesaian:</strong>
                    <br><br>
                    <?= nl2br(htmlspecialchars($data['komentar_penyelesaian'])) ?>
                </div>
            <?php endif; ?>

            <!-- FORM AMBIL PENGADUAN -->
            <?php if ($role == 'teknisi' && $data['status'] == 'Menunggu'): ?>
                <br><hr><br>
                <form action="../teknisi/update_status.php" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id_pengaduan'] ?>">

                    <label>Penanganan Dini:</label>
                    <textarea name="dini" placeholder="Berikan penanganan dini agar penghuni tidak panik dan memperburuk keadaan" required></textarea><br><br>

                    <button type="submit" name="aksi" value="ambil">
                        Tangani Pengaduan
                    </button>
                </form>
            <?php endif; ?>

            <!-- FORM SELESAIKAN PENGADUAN -->
            <?php if ($role == 'teknisi' && $data['status'] == 'Diproses' && $data['id_teknisi'] == $user_id): ?>
                <br><br>
                <form action="../teknisi/update_status.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $data['id_pengaduan'] ?>">

                    <label>Catatan/Komentar:</label>
                    <textarea name="komentar" placeholder="Jelaskan penyelesaian..." required></textarea><br><br>

                    <label>Upload Bukti Penyelesaian (JPG, JPEG, PNG Maks 2MB):</label>
                    <input type="file" name="bukti_selesai" required><br><br>

                    <button type="submit" name="aksi" value="selesai">
                        Selesaikan Pengaduan
                    </button>
                </form>
            <?php endif; ?><br>

            <!-- BACK BUTTON -->
            <button onclick="history.back()">Kembali</button>
        </div>
    </div>
</body>
</html>