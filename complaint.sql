-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jun 2026 pada 06.04
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `complaint`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `manajer`
--

CREATE TABLE `manajer` (
  `id_manajer` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `manajer`
--

INSERT INTO `manajer` (`id_manajer`, `nama`, `username`, `password`) VALUES
(1, 'Renopati', 'renopati', '$2y$10$3i/o5w30LKZ/e8581NwE/.pD/LcjNSRczdEVkhwBxZmDmiq1ciNnq');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id_pengaduan` int(11) NOT NULL,
  `nomor_tiket` varchar(30) NOT NULL,
  `id_penghuni` int(11) NOT NULL,
  `id_teknisi` int(11) DEFAULT NULL,
  `kategori` enum('Internet','Listrik','Air','Bangunan','Perabotan','Elektronik','Lainnya') DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `status` enum('Menunggu','Diproses','Selesai') DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL,
  `bukti_penyelesaian` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `penanganan_dini` text DEFAULT NULL,
  `komentar_penyelesaian` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan`
--

INSERT INTO `pengaduan` (`id_pengaduan`, `nomor_tiket`, `id_penghuni`, `id_teknisi`, `kategori`, `deskripsi`, `status`, `bukti_foto`, `bukti_penyelesaian`, `created_at`, `updated_at`, `penanganan_dini`, `komentar_penyelesaian`) VALUES
(1, 'INN-20260403-0001', 1, 1, 'Perabotan', 'Tiang kipas kamar saya lepas dari kaki kipasnya', 'Selesai', '1775207056_69cf82902ae7c.png', '1775208234_69cf872a764bf.png', '2026-04-03 16:04:16', '2026-04-27 11:52:37', 'Cari barang seperti kardus yang dapat menyangga kiaps untuk sementara waktu', 'Sudah saya ganti dengan kipas baru ya'),
(2, 'INN-20260403-0002', 2, 1, 'Listrik', 'Salah satu lampu kamar saya mati, tidak bisa dinyalakan', 'Selesai', '1775207520_69cf8460c0683.jpg', '1775208454_69cf88067d9e1.jpeg', '2026-04-03 16:12:00', '2026-04-27 11:50:19', 'Jangan hidupkan lampunya untuk sementara waktu', 'Sudah saya ganti dengan lampu baru'),
(3, 'TRA-20260403-0003', 4, 3, 'Perabotan', 'Gagang pintu kamar saya lepas dan bautnya hilang', 'Selesai', '1775209086_69cf8a7e035b0.jpeg', '1775209448_69cf8be81dee6.png', '2026-04-03 16:38:06', '2026-04-27 11:51:20', 'Simpan gagang pintunya ya', 'Sudah saya ganti dengan gagang pintu baru'),
(4, 'INN-20260404-0001', 1, 1, 'Perabotan', 'Kipas kamar saya rusak, baling-balingnya tidak berputar. Padahal kipasnya sedang tersambung dengan listrik. Sepertinya ada bagian yang tidak kuat dan usang.', 'Selesai', '1775303476_69d0fb3434778.jpeg', '1775308243_69d10dd34eb43.jpg', '2026-04-04 18:51:16', '2026-04-27 11:51:54', 'Kipasnya jangan digunakan dulu ya, takut overheating', 'Sudah saya kencangkan baling-balingnya'),
(5, 'INN-20260404-0002', 2, 1, 'Air', 'Keran wastafel kamar 310 tidak dapat mengalir, padahal bidet wc aman-aman saja. Untuk tombol flush di toilet masih berfungsi', 'Selesai', '1776403007_69e1c23f01b81.png', '1776404014_69e1c62e021ca.jpg', '2026-04-04 20:03:45', '2026-04-27 11:53:29', 'Untuk sementara, wastafelnya jangan digunakan dulu ya biar airnya tidak makin banyak', 'Sudah saya bersihkan pipa wastafelnya'),
(6, 'INN-20260417-0001', 1, 1, 'Internet', 'Wifi di lantai 2 depan kamar 210 tidak ada Internet, padahal terhubung ke internet.', 'Selesai', '1776406157_69e1ce8d7e103.jpg', '1776585744_69e48c100bca4.jpg', '2026-04-17 12:51:55', '2026-04-27 11:54:59', 'Coba disconnect dan connect ulang ke wifinya. Sambil tunggu saya, boleh reset modemnya jika masih belum bisa', 'Sudah kami perbaiki modemnya'),
(7, 'INN-20260417-0002', 2, 1, 'Bangunan', 'Ubin lantai kamar saya retak', 'Selesai', '1776407096_69e1d2381eb7c.jpeg', '1776590577_69e49ef1d43f0.jpg', '2026-04-17 13:24:56', '2026-04-27 11:55:38', 'Kalau bisa simpan serpihan ubinnya ya akan saya rekatkan nanti', 'Sudah saya rekatkan pecahan ubinnya'),
(8, 'INN-20260419-0001', 1, 1, 'Bangunan', 'Plafon kamar saya bocor', 'Diproses', '1776578860_69e4712c46492.png', NULL, '2026-04-19 13:07:40', '2026-04-27 11:58:46', 'Jauhkan barang berharga dari bawah tempat plafon bocornya, takutnya ada debu, kotoran atau air yang jatuh ke bawah.', NULL),
(9, 'INN-20260427-0001', 3, 1, 'Air', 'Air keran di kamar mandi saya tidak berfungsi, padahal aliran air di gagang shower dan wastafel berjalan lancar.', 'Diproses', '1777267529_69eef34994118.png', NULL, '2026-04-27 12:25:29', '2026-04-27 13:25:59', 'Untuk sekarang, air kerannya jangan dihidupkan dulu ya', NULL),
(10, 'INN-20260427-0002', 2, 1, 'Elektronik', 'AC kamar saya ndak berfungsi, padahal listrik tidak mati', 'Diproses', '1777271508_69ef02d4960c1.jpg', NULL, '2026-04-27 13:31:48', '2026-05-08 21:32:52', 'Untuk sementara, AC nya jangan dihidupkan ya', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penghuni`
--

CREATE TABLE `penghuni` (
  `id_penghuni` int(11) NOT NULL,
  `nama_penghuni` varchar(100) NOT NULL,
  `email_student` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `kamar` varchar(10) NOT NULL,
  `jenis_hunian` enum('Rusunawa Putri','Rusunawa Putra','Rusun INN') NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penghuni`
--

INSERT INTO `penghuni` (`id_penghuni`, `nama_penghuni`, `email_student`, `password`, `kamar`, `jenis_hunian`, `nomor_hp`, `created_at`) VALUES
(1, 'Alvin Andrianto Ciputra', 'd1041231074@student.untan.ac.id', '$2y$10$A497VEjXZuK3..fh2itgiuuzp07RtkVQI3JEfFWNeLJSHjL86kE3q', '309', 'Rusun INN', '089638782429', '2026-04-03 12:29:05'),
(2, 'Nelson Davey', 'd1041231058@student.untan.ac.id', '$2y$10$RnoBAonstICgY8Jq0b4Jmea/ikCBlIB0gzrm2U5X6n2vIj5cGtDba', '310', 'Rusun INN', '0895327088030', '2026-04-03 12:29:05'),
(3, 'Djin Jin Sihombing', 'b1011221047@student.untan.ac.id', '$2y$10$YY38JbEEdls3i0oWQPAOWuCQl58P.Ags7M8y29sEWxvg1Z.M2EPPe', '311', 'Rusun INN', '085762279821', '2026-04-03 12:29:05'),
(4, 'Anugerah Az-Zuhri Rahman', 'd1041231049@student.untan.ac.id', '$2y$10$FEfgjxrUhgJ49DKfN8AnLexW6VIpCY7VAf9hIq1ybsplWzVCKVmgK', '205', 'Rusunawa Putra', '082252482129', '2026-04-03 12:29:05'),
(5, 'Kapeng', 'f1131231049@student.untan.ac.id', '$2y$10$pz2VkZ02fO7B5R928dP3sOgcJSrHJ8fluJl/vntYjxlgJ5lwru8gq', '201', 'Rusunawa Putra', '081234567894', '2026-04-03 12:29:05'),
(6, 'Siti', 'f1131231057@student.untan.ac.id', '$2y$10$Pfd5nSWSqgi6CjiBK/Gd7.PWL9dpRgnATJ4E3cBRQkIPmNi/ZYoI6', '102', 'Rusunawa Putri', '089689264821', '2026-04-03 12:29:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teknisi`
--

CREATE TABLE `teknisi` (
  `id_teknisi` int(11) NOT NULL,
  `nama_teknisi` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `departemen_gedung` enum('Rusunawa Putri','Rusunawa Putra','Rusun INN') NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `teknisi`
--

INSERT INTO `teknisi` (`id_teknisi`, `nama_teknisi`, `username`, `password`, `departemen_gedung`, `nomor_hp`, `created_at`) VALUES
(1, 'Usep Gunawan', 'usepgunawan', '$2y$10$/RLNmRzWz2eA9HvWmtv./OGlfSuiVFa4PMvWFjIua/No/xtZANJxi', 'Rusun INN', '085248834334', '2026-04-03 12:07:56'),
(2, 'Mansur', 'surmann', '$2y$10$/yM421hmtE/S7NI8hXY5Luvy6YYWdvlfBc6el1lnYV1RefaB6YjL2', 'Rusunawa Putri', '081292344817', '2026-04-03 12:07:56'),
(3, 'Edi', 'edi123', '$2y$10$IBasCmrGFLL.WDCIyr34OeeekAlRoGWTvPb54XoGIxf4DzXiMUC2C', 'Rusunawa Putra', '089692847235', '2026-04-03 12:07:56');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `manajer`
--
ALTER TABLE `manajer`
  ADD PRIMARY KEY (`id_manajer`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD UNIQUE KEY `nomor_tiket` (`nomor_tiket`),
  ADD KEY `id_penghuni` (`id_penghuni`),
  ADD KEY `id_teknisi` (`id_teknisi`);

--
-- Indeks untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id_penghuni`),
  ADD UNIQUE KEY `email_student` (`email_student`);

--
-- Indeks untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id_teknisi`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `manajer`
--
ALTER TABLE `manajer`
  MODIFY `id_manajer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id_penghuni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  MODIFY `id_teknisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD CONSTRAINT `pengaduan_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`),
  ADD CONSTRAINT `pengaduan_ibfk_2` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id_teknisi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
