<?php
$required_role = 'penghuni';
$page = 'daftar';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$id_penghuni = $_SESSION['id'];
$jenis_hunian = $_SESSION['jenis_hunian'];

// ambil semua pengaduan dalam gedung yang sama
$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, ph.nama_penghuni, ph.kamar 
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    WHERE ph.jenis_hunian = ?
    AND p.id_penghuni != ?
    ORDER BY p.created_at DESC
");
mysqli_stmt_bind_param($stmt, "si", $jenis_hunian, $id_penghuni);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forum Pengaduan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>
</head>

<body>
    <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <div class="konten">
        <h2 id="judul-h2">Forum Pengaduan (<?= $jenis_hunian ?>)
        </h2>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <div class="card-forum">
                    <!-- HEADER -->
                    <div class="card-header">
                        <div class="ticket">
                            <?= $row['nomor_tiket'] ?>
                        </div>

                        <div>
                            <?= $row['nama_penghuni'] ?> - Kamar
                            <?= $row['kamar'] ?>
                        </div>

                        <div class="meta">
                            <?= $row['created_at'] ?>
                        </div>
                    </div>

                    <!-- STATUS -->
                    <div>
                        <?php
                        $status_class = '';
                        if ($row['status'] == 'Menunggu')
                            $status_class = 'menunggu';
                        elseif ($row['status'] == 'Diproses')
                            $status_class = 'diproses';
                        else
                            $status_class = 'selesai';
                        ?>

                        <span class="status <?= $status_class ?>">
                            <?= $row['status'] ?>
                        </span>
                    </div>
                    <br>

                    <!-- Kategori -->
                    <div>
                        <span>
                            <strong>Kategori:</strong> <?= $row['kategori'] ?>
                        </span>
                    </div><br>

                    <a href="../shared/detail_pengaduan.php?id=<?= $row['id_pengaduan'] ?>&page=<?= $page ?>">
                        Lihat Detail
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada pengaduan dari penghuni lain di gedung ini.</p>
        <?php endif; ?>
    </div>
</body>

</html>