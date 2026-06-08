# Sistem Pengaduan Kendala Rusunawa UNTAN

## Deskripsi

Sistem Pengaduan Kendala Rusunawa UNTAN merupakan aplikasi berbasis web yang dirancang untuk membantu proses pelaporan, penanganan, dan pemantauan kendala yang terjadi pada lingkungan Rusunawa Universitas Tanjungpura.

Sistem ini menerapkan mekanisme **Ticketing System**, sehingga setiap pengaduan yang dibuat oleh penghuni akan memperoleh nomor tiket unik yang dapat digunakan untuk memantau status penanganan kendala secara lebih terstruktur.

Proyek ini dikembangkan sebagai bagian dari kegiatan Kerja Praktik (KP) dengan judul:

**"Perancangan Aplikasi Pengaduan Kendala Berbasis Web dengan Mekanisme Ticketing pada Rusunawa UNTAN"**

---

## Fitur Utama

### Penghuni

* Login ke sistem
* Membuat pengaduan kendala
* Mengunggah bukti foto kendala
* Melihat daftar pengaduan penghuni lain dalam gedung yang sama
* Melihat status pengaduan
* Melihat detail pengaduan
* Melihat riwayat pengaduan pribadi
* Melihat penanganan dini dari teknisi
* Melihat komentar penyelesaian teknisi

### Teknisi

* Melihat pengaduan baru sesuai gedung yang menjadi tanggung jawabnya
* Melihat detail pengaduan
* Memberikan penanganan dini
* Mengambil dan menangani pengaduan
* Mengubah status pengaduan menjadi "Diproses"
* Mengunggah bukti penyelesaian
* Memberikan komentar penyelesaian
* Menyelesaikan pengaduan

### Manager

* Melihat dashboard pengaduan
* Melihat laporan pengaduan
* Melakukan filter laporan berdasarkan:

  * Kategori
  * Status
  * Gedung
  * Rentang waktu
  * Urutan data
* Melihat detail seluruh pengaduan

---

## Mekanisme Ticketing

Setiap pengaduan yang dibuat akan memperoleh nomor tiket unik.

Alur pengaduan:

1. Penghuni membuat pengaduan.
2. Sistem menghasilkan nomor tiket.
3. Pengaduan berstatus **Menunggu**.
4. Teknisi memberikan penanganan dini dan mengambil pengaduan.
5. Status berubah menjadi **Diproses**.
6. Teknisi menyelesaikan kendala dan mengunggah bukti penyelesaian.
7. Status berubah menjadi **Selesai**.
8. Penghuni dapat melihat hasil penyelesaian beserta bukti dan komentar teknisi.

---

## Teknologi yang Digunakan

### Backend

* PHP Native

### Database

* MySQL

### Frontend

* HTML
* CSS
* JavaScript

### Server Lokal

* XAMPP

---

## Struktur Direktori

```text
project/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
│
├── auth/
├── config/
│
├── penghuni/
├── teknisi/
├── manager/
├── shared/
│
├── uploads/
│   ├── bukti_pengaduan/
│   └── bukti_penyelesaian/
│
└── index.php
```

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/alvinciputra028-art/pengaduan.git
```

### 2. Pindahkan ke Folder Web Server

Contoh pada XAMPP:

```text
C:\xampp\htdocs\
```

### 3. Buat Database

Buat database baru pada MySQL.

Contoh:

```sql
create database sistem_pengaduan_rusunawa;
```

### 4. Import Database

Import file SQL yang tersedia pada repository.

### 5. Konfigurasi Koneksi Database

Sesuaikan file:

```text
config/koneksi.php
```

Contoh:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "sistem_pengaduan_rusunawa";
```

### 6. Jalankan Sistem

Buka browser:

```text
http://localhost/nama-folder-project
```

---

## Status Pengaduan

| Status   | Keterangan                                        |
| -------- | ------------------------------------------------- |
| Menunggu | Pengaduan baru dibuat dan belum ditangani teknisi |
| Diproses | Pengaduan sedang ditangani teknisi                |
| Selesai  | Pengaduan telah diselesaikan                      |

---

## Pengembang

Dikembangkan oleh Alvin Andrianto Ciputra (D1041231074) untuk memenuhi kebutuhan pengelolaan pengaduan kendala pada lingkungan Rusunawa Universitas Tanjungpura serta sebagai proyek Kerja Praktik Program Studi Informatika, Fakultas Teknik, Universitas Tanjungpura.
