<?php
$required_role = 'manajer';
$page = 'laporan';
include '../auth/check_session.php';
include '../config/koneksi.php';
include '../shared/navbar.php';

// FILTER
$filter_kategori = $_GET['kategori'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_gedung = $_GET['gedung'] ?? '';
$filter_waktu = $_GET['waktu'] ?? '';
$filter_urutan = $_GET['urutan'] ?? 'terbaru';

// dynamic where
$where = [];
$params = [];
$types = "";

if ($filter_waktu == "7hari") {
    $where[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filter_waktu == "30hari") {
    $where[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
} elseif ($filter_waktu == "1tahun") {
    $where[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
}

if ($filter_kategori != "") {
    $where[] = "p.kategori = ?";
    $params[] = $filter_kategori;
    $types .= "s";
}

if ($filter_status != "") {
    $where[] = "p.status = ?";
    $params[] = $filter_status;
    $types .= "s";
}

if ($filter_gedung != "") {
    $where[] = "ph.jenis_hunian = ?";
    $params[] = $filter_gedung;
    $types .= "s";
}

$order_sql = "DESC"; // default

if ($filter_urutan == 'terlama') {
    $order_sql = "ASC";
}

$where_sql = "";
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

// QUERY DATA
$query = "
SELECT p.*, ph.nama_penghuni, ph.kamar
FROM pengaduan p
JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
$where_sql
ORDER BY p.created_at $order_sql
";

$stmt = mysqli_prepare($koneksi, $query);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pengaduan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <div class="konten">
        <h2 id="judul-h2">Laporan Pengaduan</h2>
        <p>
            Filter aktif:
            <?= $filter_kategori ?: 'Semua Kategori' ?> |
            <?= $filter_status ?: 'Semua Status' ?> |
            <?= $filter_gedung ?: 'Semua Gedung' ?> |
            <?= $filter_waktu ?: 'Semua Waktu' ?> |
            <?= ($filter_urutan == 'terlama') ? 'Terlama' : 'Terbaru' ?>
        </p>


        <!-- FILTER -->
        <form method="GET" style="margin-bottom:20px;">
            <select name="kategori">
                <option value="">Semua Kategori</option>
                <?php
                $kategori_list = ['Internet', 'Air', 'Elektronik', 'Bangunan', 'Listrik', 'Perabotan', 'Lainnya'];
                foreach ($kategori_list as $k) {
                    $selected = ($filter_kategori == $k) ? 'selected' : '';
                    echo "<option value='$k' $selected>$k</option>";
                }
                ?>
            </select>

            <select name="status">
                <option value="">Semua Status</option>
                <option value="Menunggu" <?= ($filter_status == 'Menunggu') ? 'selected' : '' ?>>Menunggu</option>
                <option value="Diproses" <?= ($filter_status == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                <option value="Selesai" <?= ($filter_status == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>

            <select name="gedung">
                <option value="">Semua Gedung</option>
                <option value="Rusun INN" <?= ($filter_gedung == 'Rusun INN') ? 'selected' : '' ?>>Rusun INN</option>
                <option value="Rusunawa Putra" <?= ($filter_gedung == 'Rusunawa Putra') ? 'selected' : '' ?>>Putra</option>
                <option value="Rusunawa Putri" <?= ($filter_gedung == 'Rusunawa Putri') ? 'selected' : '' ?>>Putri</option>
            </select>

            <select name="waktu">
                <option value="">Semua Waktu</option>
                <option value="7hari" <?= ($filter_waktu == '7hari') ? 'selected' : '' ?>>7 Hari Terakhir</option>
                <option value="30hari" <?= ($filter_waktu == '30hari') ? 'selected' : '' ?>>30 Hari Terakhir</option>
                <option value="1tahun" <?= ($filter_waktu == '1tahun') ? 'selected' : '' ?>>1 Tahun Terakhir</option>
            </select>

            <select name="urutan">
                <option value="terbaru" <?= ($filter_urutan == 'terbaru') ? 'selected' : '' ?>>
                    Terbaru
                </option>
                <option value="terlama" <?= ($filter_urutan == 'terlama') ? 'selected' : '' ?>>
                    Terlama
                </option>
            </select><br><br>

            <button type="submit">Filter</button>
        </form>

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
                            <strong>Kategori: </strong> <?= $row['kategori'] ?>
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
    <script src="../assets/js/script.js"></script>

</body>

</html>