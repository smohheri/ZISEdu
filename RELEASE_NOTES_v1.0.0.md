# Release Notes v1.0.0

Tanggal rilis: **2026-03-03**  
Status: **First Release**

## Ringkasan

Rilis pertama aplikasi **Manajemen Zakat** berbasis CodeIgniter 3.  
Versi ini mencakup alur inti pengelolaan zakat dari data master, transaksi, penyaluran, hingga laporan dan cetak dokumen.

## Highlights

- Manajemen pengguna dengan autentikasi login berbasis session.
- Dashboard ringkasan operasional zakat.
- Master data lengkap: muzakki, mustahik, tanggungan fitrah, jenis harta mal.
- Transaksi zakat fitrah dan zakat mal (termasuk auto nomor transaksi).
- Penyaluran zakat ke mustahik dengan detail distribusi.
- Cetak kwitansi penerimaan zakat fitrah dan zakat mal.
- Laporan periode + ekspor PDF menggunakan mPDF.
- Redesign halaman Users (DataTables server-side + statistik).

## Fitur yang Termasuk

### 1) Authentication & User Management
- Login/logout.
- Validasi user aktif.
- Role pengguna: `super_admin`, `amil`, `operator`.
- CRUD users + statistik status/role.

### 2) Dashboard
- Total muzakki & mustahik.
- Rekap pemasukan dan penyaluran.
- Grafik bulanan.
- Daftar transaksi terbaru.

### 3) Master Data
- Muzakki.
- Mustahik (kategori asnaf).
- Tanggungan fitrah.
- Jenis harta mal.

### 4) Konfigurasi
- Pengaturan zakat tahunan.
- Pengaturan lembaga (profil, rekening, logo/kop/stempel).

### 5) Transaksi
- Zakat fitrah.
- Zakat mal + detail aset.
- Penyaluran zakat.

### 6) Laporan
- Filter tanggal.
- Ringkasan uang/beras.
- Ekspor PDF.

## Kompatibilitas

- PHP: mengikuti kebutuhan proyek CodeIgniter 3 (disarankan PHP modern yang kompatibel).
- Database: MySQL/MariaDB.
- Environment lokal yang dipakai: WAMP (Windows).

## Cara Upgrade / Instalasi Rilis

1. Import skema database dari `database/zakat_schema.sql`.
2. Samakan nama database di `application/config/database.php`.
3. Set `base_url` di `application/config/config.php`.
4. Pastikan dependency terpasang (`mpdf/mpdf`).
5. Pastikan folder `uploads/lembaga` writable.

## Catatan Penting

- Default seed user tersedia dengan username `admin`.
- Karena belum terhubung repository Git, rilis ini didokumentasikan manual pada file changelog.

## Referensi

- Dokumentasi umum: `README.md`
- Riwayat perubahan: `CHANGELOG.md`
