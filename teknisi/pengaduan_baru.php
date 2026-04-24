<?php
$required_role = 'teknisi';
$page = 'baru';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$departemen = $_SESSION['departemen_gedung'];
$id_teknisi = $_SESSION['id'];
$status = 'Menunggu';

// ambil pengaduan sesuai gedung & belum diambil
$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, ph.nama_penghuni, ph.kamar, ph.jenis_hunian
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    WHERE p.status = ?
    AND ph.jenis_hunian = ?
    ORDER BY p.created_at ASC
");
mysqli_stmt_bind_param($stmt, "ss", $status, $departemen);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengaduan Baru</title>
</head>

<body>
    <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <div class="konten">
        <h2 id="judul-h2">Pengaduan Baru</h2>

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
                    <span>
                        <strong>Kategori: </strong><?= $row['kategori'] ?>
                    </span><br><br>

                    <a href="../shared/detail_pengaduan.php?id=<?= $row['id_pengaduan']?>&page=<?= $page ?>">
                        Lihat Detail
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada pengaduan baru dari penghuni <?= $departemen ?></p>
        <?php endif; ?>
    </div>
</body>

</html>