# Aplikasi Manajemen Zakat (CodeIgniter 3)

Aplikasi ini digunakan untuk pendataan dan pengelolaan **zakat fitrah** serta **zakat mal** di tingkat lembaga/amil. Sistem dibangun dengan **CodeIgniter 3** + **AdminLTE**, mendukung proses dari data master, transaksi, penyaluran, hingga laporan dan ekspor PDF.

## Informasi Rilis

- Versi rilis: **v1.0.0**
- Tanggal rilis: **2026-03-03**
- Status: **First Release (rilis pertama)**
- Release notes: **RELEASE_NOTES_v1.0.0.md**

## Ringkasan Fitur

### 1. Autentikasi & Akses Pengguna
- Login/logout berbasis session.
- Verifikasi password menggunakan `password_hash` / `password_verify`.
- Manajemen user (role: `super_admin`, `amil`, `operator`).
- Halaman Users sudah didesain ulang dengan:
  - statistik user,
  - DataTables server-side,
  - pencarian cepat,
  - badge role/status,
  - modal konfirmasi hapus.

### 2. Dashboard
- Ringkasan jumlah muzakki dan mustahik.
- Ringkasan total pemasukan zakat dan penyaluran.
- Grafik bulanan pemasukan/penyaluran.
- Daftar transaksi fitrah dan penyaluran terbaru.

### 3. Master Data
- **Muzakki** (pembayar zakat).
- **Tanggungan Fitrah** (anggota keluarga untuk perhitungan jiwa).
- **Mustahik** (penerima zakat, kategori asnaf).
- **Jenis Harta Mal** (tarif, haul, status aktif).

### 4. Pengaturan
- **Pengaturan Zakat Tahunan**
  - nilai fitrah per jiwa (kg/uang),
  - harga beras,
  - nilai emas (dasar nishab).
- **Pengaturan Aplikasi/Lembaga**
  - identitas lembaga,
  - kontak,
  - rekening,
  - upload logo/kop/stempel.

### 5. Transaksi
- **Zakat Fitrah**
  - nomor transaksi otomatis,
  - metode tunaikan beras/uang,
  - penyesuaian jumlah jiwa otomatis berdasarkan jenis muzakki,
  - cetak kwitansi.
- **Zakat Mal**
  - nomor transaksi otomatis,
  - input detail aset (`zakat_mal_detail`),
  - kalkulasi otomatis harta bersih & total zakat,
  - cetak kwitansi.
- **Penyaluran**
  - nomor penyaluran otomatis,
  - detail distribusi per mustahik,
  - dukungan bentuk bantuan uang/beras/paket.

### 6. Laporan
- Filter laporan per rentang tanggal.
- Ringkasan pemasukan & penyaluran (uang dan beras).
- Detail laporan zakat fitrah, zakat mal, dan penyaluran.
- Ekspor **PDF** menggunakan `mpdf/mpdf`.

---

## Teknologi yang Digunakan
- PHP (CodeIgniter 3)
- MySQL/MariaDB
- AdminLTE + Bootstrap + jQuery
- DataTables
- mPDF

---

## Struktur Modul Utama

- `application/controllers` → logika request per modul
- `application/models` → query dan akses data
- `application/views` → tampilan halaman
- `application/config` → routing & konfigurasi aplikasi
- `database/zakat_schema.sql` → skema database utama
- `uploads/lembaga` → file logo/kop/stempel

---

## Cara Instalasi (Lokal WAMP/XAMPP)

1. Letakkan project pada web root (contoh: `C:/wamp/www/zakat`).
2. Buat database MySQL.
3. Import skema dari `database/zakat_schema.sql`.
4. Atur koneksi database di `application/config/database.php`.
5. Pastikan base URL sesuai di `application/config/config.php`.
6. Install dependency Composer (jika belum):
   - paket penting: `mpdf/mpdf`.
7. Pastikan folder upload dapat ditulis:
   - `uploads/lembaga`.

### Catatan penting database
- `database/zakat_schema.sql` membuat database bernama `zakat_db`.
- konfigurasi aktif di `application/config/database.php` saat ini menggunakan `zakat`.
- Samakan salah satunya agar koneksi berhasil.

---

## Akun Login Awal

Seed data menyediakan user:
- Username: `admin`
- Email: `admin@zakat.local`

Password default tidak didokumentasikan dalam plain text (menggunakan hash). Jika perlu, ubah password langsung dari fitur Users atau reset melalui database dengan hash baru.

---

## Alur Penggunaan Singkat

1. Login sebagai admin/amil.
2. Lengkapi **Pengaturan Aplikasi** dan **Pengaturan Zakat**.
3. Isi data master: Muzakki, Mustahik, Jenis Harta Mal.
4. Input transaksi zakat fitrah/mal.
5. Input data penyaluran.
6. Lihat dashboard dan cetak laporan PDF.

---

## Status Pengembangan Saat Ini

Fitur inti aplikasi telah tersedia dan berjalan pada rilis **v1.0.0**:
- manajemen data master,
- transaksi fitrah/mal,
- penyaluran,
- kwitansi,
- laporan + ekspor PDF,
- redesign halaman users (server-side DataTables + statistik).

Detail perubahan terbaru tersedia pada file `CHANGELOG.md`.
Ringkasan publik rilis tersedia pada `RELEASE_NOTES_v1.0.0.md`.
