<?php
$required_role = 'manajer';
$page = 'beranda';
include '../config/koneksi.php';
include '../auth/check_session.php';
include '../shared/navbar.php';

// FILTER
$filter_kategori = $_GET['kategori'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_gedung = $_GET['gedung'] ?? '';
$filter_waktu = $_GET['waktu'] ?? '';

// base query
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

$where_sql = "";
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

// DATA GRAFIK KATEGORI
$query_kategori = "
    SELECT p.kategori, COUNT(*) as total
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    $where_sql
    GROUP BY p.kategori
";

$stmt = mysqli_prepare($koneksi, $query_kategori);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$kategori_labels = [];
$kategori_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $kategori_labels[] = $row['kategori'];
    $kategori_data[] = $row['total'];
}
mysqli_stmt_close($stmt);

// DATA GRAFIK STATUS
$query_status = "
    SELECT p.status, COUNT(*) as total
    FROM pengaduan p
    JOIN penghuni ph ON p.id_penghuni = ph.id_penghuni
    $where_sql
    GROUP BY p.status
";

$stmt = mysqli_prepare($koneksi, $query_status);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$status_labels = [];
$status_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $status_labels[] = $row['status'];
    $status_data[] = $row['total'];
}
mysqli_stmt_close($stmt);

if (empty($kategori_labels)) {
    $kategori_labels = ['Tidak ada data'];
    $kategori_data = [0];
}

if (empty($status_labels)) {
    $status_labels = ['Tidak ada data'];
    $status_data = [0];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Beranda Manajer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="konten">
        <h2 id="judul-h2">Dashboard Manajer</h2>
        <p>
            Filter aktif:
            <?= $filter_kategori ?: 'Semua Kategori' ?> |
            <?= $filter_status ?: 'Semua Status' ?> |
            <?= $filter_gedung ?: 'Semua Gedung' ?> |
            <?= $filter_waktu ?: 'Semua Waktu' ?>
        </p>

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
            </select><br><br>

            <button type="submit">Filter</button>
        </form>

        <div class="chart-container">
            <div class="chart-box-bar">
                <h3>Grafik Pengaduan per Kategori</h3>
                <canvas id="chartKategori"></canvas>
            </div>

            <div class="chart-box-pie">
                <h3 style="text-align: center;">Grafik Pengaduan per Status</h3>
                <canvas id="chartStatus"></canvas>
            </div>
        </div>

        <button onclick="window.print()">Print / Export PDF</button>
    </div>

    <script>
        const kategoriChart = new Chart(document.getElementById('chartKategori'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($kategori_labels) ?>,
                datasets: [{
                    label: 'Jumlah Pengaduan',
                    data: <?= json_encode($kategori_data) ?>,
                    // backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    // borderColor: 'rgba(54, 162, 235, 1)',
                    // borderWidth: 1
                }]
            }
        });

        const statusChart = new Chart(document.getElementById('chartStatus'), {
            type: 'pie',
            data: {
                labels: <?= json_encode($status_labels) ?>,
                datasets: [{
                    data: <?= json_encode($status_data) ?>,
                    // backgroundColor: [ 
                    //     'rgba(255, 99, 132, 0.6)',
                    //     'rgba(255, 206, 86, 0.6)',
                    //     'rgba(75, 192, 192, 0.6)'
                    // ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
    </script>

</body>

</html>