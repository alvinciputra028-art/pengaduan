<?php
$required_role = 'penghuni';
$page = 'riwayat';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

$id = $_SESSION['id'];

// ambil data pengaduan milik penghuni
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM pengaduan 
    WHERE id_penghuni = ?
    ORDER BY created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Riwayat Pengaduan</title>
</head>

<body>
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeImage()">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <div class="konten">
        <h2 id="judul-h2">Riwayat Pengaduan Saya</h2>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <div class="card-forum">
                    <!-- HEADER -->
                    <div class="card-header">
                        <div class="ticket">
                            <?= $row['nomor_tiket'] ?>
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
                    </div><br>

                    <!-- Kategori -->
                    <span>
                        <strong>Kategori:</strong> <?= $row['kategori'] ?>
                    </span><br><br>

                    <a href="../shared/detail_pengaduan.php?id=<?= $row['id_pengaduan'] ?>&page=<?= $page ?>">
                        Lihat Detail
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Anda belum pernah melakukan pengaduan.</p>
        <?php endif; ?>
    </div>

</body>

</html>