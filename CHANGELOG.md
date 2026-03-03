# Changelog

Semua perubahan penting pada aplikasi ini dicatat di file ini.

Format mengikuti gaya sederhana: `Added`, `Changed`, `Fixed`.

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
