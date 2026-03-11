# Changelog

Semua perubahan penting pada aplikasi ini dicatat di file ini.

Format mengikuti gaya sederhana: `Added`, `Changed`, `Fixed`.

## [v1.1.4] - 2026-03-11

Peningkatan kompatibilitas dengan PHP 8.2, perbaikan responsivitas mobile pada panel admin, penyempurnaan UI transaksi Zakat Fitrah, serta peningkatan tata letak kwitansi 2-kolom.

### Added
- Fitur auto-kalkulasi perkiraan Tahun Hijriah berdasarkan input Tahun Masehi secara *real-time* pada form transaksi Zakat Fitrah.
- Implementasi kotak pencarian data Muzakki menggunakan plugin **Select2** pada form transaksi Zakat Fitrah.

### Changed
- Input Tahun Hijriah pada form transaksi Zakat Fitrah diubah menjadi *read-only*.
- Penyesuaian skrip inisialisasi Select2 menggunakan event `DOMContentLoaded` agar plugin berhasil termuat.
- Tabel data pada seluruh halaman index dan laporan dibungkus dengan wrapper `.table-responsive` agar layout tabel bisa di-scroll horizontal tanpa merusak layout AdminLTE saat diakses melalui perangkat mobile.
- Layout form filter pada halaman Laporan dirombak menggunakan sistem Grid Bootstrap agar tampil rapi dan tertata dengan baik di layar mobile maupun desktop.
- Tampilan sidebar menu dibuat lebih *compact* (ringkas) dengan jarak antar item yang lebih rapat dan huruf yang sedikit lebih kecil (`nav-compact text-sm`), sehingga dapat menampilkan lebih banyak menu tanpa memakan banyak tempat vertikal.
- Layout cetak kwitansi (Browser Print) dan ekspor PDF kini menggunakan desain **2 kolom** untuk **Zakat Fitrah** dan **Zakat Mal** (kiri: info, kanan: rincian).
- Ukuran output kertas kwitansi dibuat spesifik secara _custom_: **Lebar 21 cm x Tinggi 13,9 cm**.
- Margins, paddings, dan ukuran font telah direkayasa pada `kwitansi_pdf.php` dan `kwitansi.php` sedemikian rupa sehingga output terjamin muat dalam 1 halaman tanpa _page break_.
- Menghapus tampilan "Kode Muzakki" pada kwitansi cetak, sehingga hanya nama lengkap yang tercetak.

### Fixed
- Mengatasi error peringatan *Creation of dynamic property is deprecated* di PHP 8.2 dengan menambahkan atribut `#[\AllowDynamicProperties]` pada class inti CodeIgniter 3 (`CI_Controller`, `CI_Loader`, `CI_Router`, `CI_URI`, `CI_Model`, `CI_DB_driver`).
- Menyelaraskan seluruh elemen tabel data utama dengan sistem kerangka `table-responsive` dan `text-nowrap`.
- Memperbaiki isu tampilan tabel _overflow_ (melebar merusak layar) pada perangkat genggam/_mobile_, dengan fitur panel geser (*horizontal scroll*) otomatis tanpa merusak layout.

## [v1.1.3] - 2026-03-04

Penyempurnaan struktur aset frontend dan polishing UI pada halaman form/login.

### Added
- Folder baru `asset/` sebagai root untuk aset statis aplikasi.

### Changed
- Seluruh paket AdminLTE dipindahkan dari root `adminlte/` ke `asset/adminlte/`.
- Referensi URL aset pada view diperbarui ke path baru `asset/adminlte/...`, mencakup:
  - partial layout (`head` dan `scripts`),
  - halaman login,
  - dashboard (Chart.js),
  - halaman kwitansi zakat fitrah dan zakat mal.
- Layout section **Perhitungan Zakat** pada form `zakat_mal/create` dirapikan:
  - komposisi grid field dibuat lebih seimbang,
  - baris `Mode Perhitungan` + `Metode Bayar` diposisikan di awal section,
  - field `Keterangan` dipisah menjadi baris penuh (`12` kolom).
- Tampilan panel kanan halaman login diperbarui agar lebih modern, elegan, dan clean:
  - style card, spacing, input, dan tombol login dipoles,
  - branding panel kanan menggunakan `nama_lembaga` dari database,
  - branding panel kiri tetap menggunakan nama aplikasi `ZISEdu`.

### Fixed
- Mencegah potensi 404 aset setelah restrukturisasi direktori dengan menyamakan seluruh path pemanggilan CSS/JS ke lokasi baru.

## [v1.1.2] - 2026-03-04

Penyempurnaan perhitungan transaksi **Zakat Mal** agar mendukung input nominal zakat manual.

### Added
- Opsi **Mode Perhitungan** pada form transaksi zakat mal:
  - `Hitung Otomatis` (menghitung `total_zakat` dari parameter perhitungan),
  - `Input Zakat Manual` (nominal `total_zakat` diisi langsung oleh amil/operator).

### Changed
- Alur simpan dan update transaksi `zakat_mal` diperbarui untuk membaca input `mode_perhitungan`.
- Validasi server-side ditambahkan untuk field `mode_perhitungan` dengan nilai yang diizinkan `otomatis` atau `manual`.
- Perilaku JavaScript pada form zakat mal diperbarui:
  - pada mode manual, field `total_zakat` dapat diedit,
  - pada mode otomatis, field `total_zakat` terkunci dan dihitung ulang otomatis,
  - `harta_bersih` tetap dihitung otomatis di kedua mode.
- Saat edit data lama, mode perhitungan default kini menyesuaikan nilai existing agar lebih konsisten dengan nominal tersimpan.

### Fixed
- Perhitungan `harta_bersih` dan `total_zakat` kini lebih konsisten antara form (frontend) dan proses simpan/update (backend) untuk skenario manual maupun otomatis.

## [v1.1.1] - 2026-03-04

Penyempurnaan UX modul master, transaksi, kwitansi, dan laporan.

### Added
- Tombol **Detail** pada list muzakki dengan tampilan **modal** untuk melihat informasi lengkap tanpa pindah halaman.
- Tombol **Export PDF** pada halaman kwitansi zakat fitrah dan zakat mal.
- Endpoint export PDF baru:
  - `zakat_fitrah/export_pdf/{id}`,
  - `zakat_mal/export_pdf/{id}`.
- Template PDF khusus kwitansi:
  - `application/views/zakat_fitrah/kwitansi_pdf.php`,
  - `application/views/zakat_mal/kwitansi_pdf.php`.
- Modal detail pada list transaksi:
  - `zakat_mal` (detail transaksi tanpa pindah halaman),
  - `penyaluran` (detail transaksi tanpa pindah halaman).
- Endpoint detail JSON penyaluran `penyaluran/detail_json/{id}` untuk memuat list mustahik penerima di modal.
- Bagian laporan baru:
  - **Laporan Infaq & Shodaqoh** (halaman laporan + export PDF),
  - **List Mustahik Penerima** pada export PDF laporan.
- Penyimpanan `no_kwitansi` di database untuk:
  - `zakat_fitrah`,
  - `zakat_mal`,
  - `infaq_shodaqoh`.
- Skrip migrasi database existing: `database/alter_no_kwitansi.sql`.

### Changed
- Layout form muzakki dirapikan agar lebih proporsional:
  - susunan kolom identitas diperbaiki,
  - `Pekerjaan`, `No HP`, dan `Email` ditampilkan dalam satu baris,
  - bagian alamat dibuat lebih nyaman diisi.
- Tabel list muzakki diperbarui:
  - kolom `Alamat` dihapus dari tabel utama,
  - `NIK` dipindahkan sebagai teks kecil di bawah nama.
- Format kwitansi zakat mal disamakan dengan kwitansi fitrah (tampilan, aksi, dan PDF).
- Tanda tangan pada kwitansi otomatis terisi:
  - pihak **Muzakki** dari nama muzakki transaksi,
  - pihak **Penerima** dari user login (`nama_lengkap`/`username`).
- Pengaturan cetak kwitansi disesuaikan default kertas dot matrix Epson LX-310 (`9.5 x 11 in`).
- Form penyaluran ditingkatkan:
  - detail penerima bisa tambah/hapus baris dinamis,
  - tampil indikator sisa alokasi uang/beras secara realtime,
  - tombol simpan dinonaktifkan jika masih ada sisa alokasi.
- Default tanggal form transaksi diseragamkan ke tanggal hari ini saat nilai kosong:
  - zakat fitrah,
  - zakat mal,
  - penyaluran,
  - infaq/shodaqoh.
- Format nomor transaksi/penyaluran diubah:
  - dari token tengah `YYYYMMDD` menjadi `YYYY`.
- Format nomor kwitansi diubah:
  - dari token tengah `YYYYMM` menjadi `YYYY`.
- Nomor kwitansi kini bersifat persisten (tersimpan di kolom `no_kwitansi`) sehingga konsisten saat cetak ulang/export PDF.

### Fixed
- JavaScript toggle form pada pilihan `jenis_muzakki` diperbaiki sehingga section tanggungan otomatis muncul saat memilih `kepala_keluarga`.
- Data `rows_mustahik` pada export PDF laporan diperbaiki agar list mustahik tidak kosong.
- Zona waktu aplikasi diset agar default tanggal mengikuti waktu lokal (`Asia/Jakarta`).

## [v1.1.0] - 2026-03-03

Pembaruan fitur transaksi non-zakat dan penyempurnaan tampilan aplikasi.

### Added
- Modul transaksi **Infaq & Shodaqoh**:
  - tabel database `infaq_shodaqoh`,
  - controller `Infaq_shodaqoh`,
  - model `Infaq_shodaqoh_model`,
  - halaman list + form CRUD,
  - routing dan menu sidebar.
- Konfigurasi versi aplikasi terpusat pada `application/config/config.php`:
  - `$config['app_version'] = 'v1.0.0';`

### Changed
- Dashboard didesain ulang agar lebih sesuai data riil database:
  - ringkasan pemasukan uang lintas sumber (fitrah, mal, infaq/shodaqoh),
  - saldo uang (masuk - penyaluran),
  - komposisi pemasukan per jenis transaksi,
  - arus dana bulanan,
  - tabel transaksi terbaru fitrah, infaq/shodaqoh, dan penyaluran.
- `Dashboard_model` diperluas:
  - dukungan agregasi data `infaq_shodaqoh`,
  - pengecekan keberadaan tabel `infaq_shodaqoh` agar dashboard tetap aman jika tabel belum di-migrate.
- `Welcome` controller diperbarui untuk mengirim data `recent_infaq_shodaqoh` ke view dashboard.

### Fixed
- Informasi versi aplikasi kini konsisten tampil:
  - di footer layout admin,
  - di form login.

## [v1.0.0] - 2026-03-03 (First Release)

Rilis resmi pertama aplikasi manajemen zakat.

Dokumen ringkasan rilis publik: `RELEASE_NOTES_v1.0.0.md`.

### Added
- Dokumentasi proyek utama pada `README.md`.
- Changelog proyek pada `CHANGELOG.md`.
- Modul laporan dengan ekspor PDF menggunakan `mpdf/mpdf`.
- Modul pengaturan aplikasi/lembaga (termasuk upload logo, kop, stempel).
- Modul pengaturan zakat tahunan (nilai fitrah, harga beras, nilai emas).
- Modul transaksi zakat fitrah (CRUD, auto nomor transaksi, kwitansi).
- Modul transaksi zakat mal (CRUD, auto nomor transaksi, detail aset, kwitansi).
- Modul penyaluran zakat (CRUD, auto nomor penyaluran, detail per mustahik).
- Dashboard ringkasan dan grafik bulanan.

### Changed
- Halaman manajemen users didesain ulang total:
  - statistik user per role/status,
  - server-side DataTables,
  - pencarian custom,
  - tampilan avatar & badge,
  - action dropdown,
  - konfirmasi hapus via modal.
- Alur autentikasi diperbarui dengan validasi username/email + password hash.
- Router diarahkan agar default masuk ke halaman login (`auth`).

### Fixed
- Validasi form di berbagai modul transaksi dan master data diperketat.
- Pencegahan duplikasi nomor transaksi/nomor penyaluran dengan fallback generate ulang.
- Konsistensi perhitungan otomatis zakat mal (`harta_bersih`, `total_zakat`).
- Validasi input metode tunaikan zakat fitrah (uang/beras) agar nominal tidak nol.

---

## Catatan

- Karena repository saat ini tidak terhubung Git, histori versi sebelumnya tidak tersedia otomatis.
- Format versi yang dipakai mulai rilis ini: semantic versioning (`v1.0.0`, `v1.1.0`, dst).
