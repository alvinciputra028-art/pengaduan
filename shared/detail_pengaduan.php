<?php
$page = $_GET['page'];
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

// validasi id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid";
    exit;
}

$id = $_GET['id'];
$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

// ======================
// AMBIL DATA PENGADUAN
// ======================
$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, ph.nama_penghuni, ph.kamar, ph.jenis_hunian
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    WHERE p.id_pengaduan = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "Data tidak ditemukan";
    exit;
}

$data = mysqli_fetch_assoc($result);

// ======================
// VALIDASI AKSES
// ======================
if ($role == 'penghuni') {
    // ambil gedung user login
    $user_gedung = $_SESSION['jenis_hunian'];

    // hanya boleh lihat:
    // 1. milik sendiri
    // 2. atau dalam gedung yang sama (forum)
    if (
        $data['id_penghuni'] != $user_id &&
        $data['jenis_hunian'] != $user_gedung
    ) {
        echo "Akses ditolak";
        exit;
    }
}

if ($role == 'teknisi') {
    // hanya boleh lihat jika:
    // 1. sesuai gedung
    // 2. atau dia yang menangani
    if (
        $data['jenis_hunian'] != $_SESSION['departemen_gedung'] &&
        $data['id_teknisi'] != $user_id
    ) {
        echo "Akses ditolak";
        exit;
    }
}

// manager bebas akses

// ======================
// STATUS CLASS
// ======================
$status_class = '';
if ($data['status'] == 'Menunggu') $status_class = 'menunggu';
elseif ($data['status'] == 'Diproses') $status_class = 'diproses';
else $status_class = 'selesai';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pengaduan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>
</head>

<body>

<div id="imageModal" class="modal">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="modalImg">
</div>

<div class="konten">
    <h2 id="judul-h2">Detail Pengaduan</h2>

    <div class="card-forum">

        <!-- HEADER -->
        <div class="card-header">
            <div class="ticket"><?= $data['nomor_tiket'] ?></div>

            <div>
                <?= $data['nama_penghuni'] ?> - Kamar <?= $data['kamar'] ?>
            </div>

            <div class="meta">
                <?= $data['created_at'] ?>
            </div>
        </div>

        <!-- STATUS -->
        <span class="status <?= $status_class ?>">
            <?= $data['status'] ?>
        </span>

        <br><br>

        <!-- KATEGORI -->
        <div>
            <strong>Kategori:</strong> <?= $data['kategori'] ?>
        </div>

        <br>

        <!-- DESKRIPSI -->
        <div class="deskripsi">
            <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
        </div>

        <br>

        <!-- BUKTI PENGADUAN -->
        <?php if ($data['bukti_foto']): ?>
            <div>
                <a href="#" class="lihat-gambar"
                   data-src="../uploads/bukti_pengaduan/<?= $data['bukti_foto'] ?>">
                    Lihat Bukti Pengaduan
                </a>
            </div>
        <?php endif; ?><br>

        <!-- BUKTI PENYELESAIAN -->
        <?php if ($data['bukti_penyelesaian']): ?>
            <div>
                <a href="#" class="lihat-gambar"
                   data-src="../uploads/bukti_penyelesaian/<?= $data['bukti_penyelesaian'] ?>">
                    Lihat Bukti Penyelesaian
                </a>
            </div>
        <?php endif; ?>

        <!-- KOMENTAR PENYELESAIAN -->
        <?php if (!empty($data['komentar_penyelesaian'])): ?>
            <br><hr><br>
            <div class="komentar-box">
                <strong>Catatan Teknisi:</strong><br><br>
                <?= nl2br(htmlspecialchars($data['komentar_penyelesaian'])) ?>
            </div>
        <?php endif; ?>

        <!-- AKSI TEKNISI -->
        <?php if ($role == 'teknisi' && $data['status'] == 'Menunggu'): ?>
            <a href="../teknisi/update_status.php?id=<?= $data['id_pengaduan'] ?>&aksi=ambil">
                Tangani Pengaduan
            </a><br>
        <?php endif; ?>

        <?php if ($role == 'teknisi' && $data['status'] == 'Diproses' && $data['id_teknisi'] == $user_id): ?>
            <hr><br>
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