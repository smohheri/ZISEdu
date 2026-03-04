# Aplikasi Manajemen Zakat (CodeIgniter 3)

Aplikasi ini digunakan untuk pendataan dan pengelolaan **zakat fitrah** serta **zakat mal** di tingkat lembaga/amil. Sistem dibangun dengan **CodeIgniter 3** + **AdminLTE**, mendukung proses dari data master, transaksi, penyaluran, hingga laporan dan ekspor PDF.

## Informasi Rilis

- Versi rilis: **v1.1.3**
- Tanggal rilis: **2026-03-04**
- Status: **Stable Update**
- Riwayat perubahan: **CHANGELOG.md**
- Release notes rilis pertama: **RELEASE_NOTES_v1.0.0.md**

### Highlight v1.1.3
- Restrukturisasi aset frontend: paket AdminLTE dipindahkan ke root aset baru `asset/adminlte/`.
- Referensi CSS/JS pada halaman utama (layout, login, dashboard, kwitansi) diperbarui ke path aset baru.
- Layout form `zakat_mal` (section Perhitungan Zakat) dirapikan dengan komposisi grid yang lebih konsisten.
- UI panel kanan halaman login dipoles agar lebih elegan/clean, dengan branding nama lembaga dari database.

### Highlight v1.1.2
- Form transaksi `zakat_mal` mendukung `Mode Perhitungan` (otomatis/manual).
- Pada mode manual, nominal `total_zakat` bisa diinput langsung dan tetap tervalidasi di backend.
- Perhitungan `harta_bersih` tetap otomatis, sementara `total_zakat` menyesuaikan mode perhitungan.

### Highlight v1.1.1
- Detail data pada list `muzakki`, `zakat_mal`, dan `penyaluran` menggunakan modal.
- Kwitansi fitrah/mal mendukung `Cetak` dan `Export PDF` dengan auto isi tanda tangan (muzakki & penerima login).
- Laporan ditingkatkan dengan bagian `Infaq & Shodaqoh` serta `List Mustahik Penerima` pada export PDF.
- Format nomor transaksi/penyaluran dan nomor kwitansi disederhanakan ke token tahun (`YYYY`).
- Default tanggal input transaksi diseragamkan ke tanggal hari ini saat nilai kosong.
- Nomor kwitansi (`no_kwitansi`) disimpan ke database agar konsisten saat cetak ulang/export.

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
  - cetak kwitansi + export PDF.
- **Zakat Mal**
  - nomor transaksi otomatis,
  - input detail aset (`zakat_mal_detail`),
  - kalkulasi otomatis harta bersih & total zakat,
  - cetak kwitansi + export PDF.
- **Penyaluran**
  - nomor penyaluran otomatis,
  - detail distribusi per mustahik,
  - dukungan bentuk bantuan uang/beras/paket,
  - validasi alokasi detail penerima agar sesuai total uang/beras.

### 6. Laporan
- Filter laporan per rentang tanggal.
- Ringkasan pemasukan & penyaluran (uang dan beras).
- Detail laporan zakat fitrah, zakat mal, penyaluran, dan infaq/shodaqoh.
- Rekap list mustahik penerima pada export PDF laporan.
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
- Untuk database lama, jalankan skrip tambahan `database/alter_no_kwitansi.sql` agar kolom `no_kwitansi` tersedia.

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

Fitur inti aplikasi telah tersedia dan berjalan hingga rilis **v1.1.3**:
- manajemen data master,
- transaksi fitrah/mal,
- penyaluran,
- kwitansi (cetak + export PDF),
- laporan + ekspor PDF,
- redesign halaman users (server-side DataTables + statistik),
- modul infaq/shodaqoh,
- penyempurnaan UI/UX halaman muzakki,
- modal detail pada zakat mal dan penyaluran,
- mode perhitungan zakat mal otomatis/manual,
- standarisasi format nomor transaksi/kwitansi berbasis tahun (`YYYY`).
- penyimpanan permanen nomor kwitansi di database (`no_kwitansi`).
- restrukturisasi aset frontend ke `asset/adminlte`.
- penyempurnaan UI form zakat mal dan halaman login.

Detail perubahan terbaru tersedia pada file `CHANGELOG.md`.
Ringkasan publik rilis tersedia pada `RELEASE_NOTES_v1.0.0.md`.
