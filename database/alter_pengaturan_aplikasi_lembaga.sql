-- ======================================================
-- Alter DB: Tambah tabel pengaturan aplikasi/profil lembaga
-- Jalankan di database zakat_db yang sudah ada
-- ======================================================

USE zakat_db;

CREATE TABLE IF NOT EXISTS pengaturan_aplikasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_lembaga VARCHAR(30) NOT NULL UNIQUE,
  nama_lembaga VARCHAR(200) NOT NULL,
  nama_pimpinan VARCHAR(150) NULL,
  nama_bendahara VARCHAR(150) NULL,
  alamat TEXT NULL,
  kelurahan VARCHAR(100) NULL,
  kecamatan VARCHAR(100) NULL,
  kota_kabupaten VARCHAR(100) NULL,
  provinsi VARCHAR(100) NULL,
  kode_pos VARCHAR(10) NULL,
  no_telp VARCHAR(30) NULL,
  no_hp VARCHAR(30) NULL,
  email VARCHAR(120) NULL,
  website VARCHAR(120) NULL,
  npwp VARCHAR(50) NULL,
  logo_path VARCHAR(255) NULL,
  kop_path VARCHAR(255) NULL,
  stempel_path VARCHAR(255) NULL,
  rekening_atas_nama VARCHAR(150) NULL,
  rekening_nomor VARCHAR(50) NULL,
  rekening_bank VARCHAR(80) NULL,
  catatan_kuitansi TEXT NULL,
  aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_pengaturan_aplikasi_nama (nama_lembaga),
  INDEX idx_pengaturan_aplikasi_aktif (aktif)
) ENGINE=InnoDB;

ALTER TABLE pengaturan_aplikasi
  ADD COLUMN IF NOT EXISTS kop_path VARCHAR(255) NULL AFTER logo_path;

INSERT INTO pengaturan_aplikasi (
  kode_lembaga,
  nama_lembaga,
  alamat,
  no_telp,
  email,
  website,
  aktif
)
VALUES (
  'LMBG-001',
  'Lembaga Zakat',
  'Alamat lembaga zakat',
  '-',
  'admin@zakat.local',
  '-',
  1
)
ON DUPLICATE KEY UPDATE
  nama_lembaga = VALUES(nama_lembaga),
  alamat = VALUES(alamat),
  no_telp = VALUES(no_telp),
  email = VALUES(email),
  website = VALUES(website),
  aktif = VALUES(aktif);
