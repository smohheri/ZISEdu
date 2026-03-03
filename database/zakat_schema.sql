-- =============================================
-- Database Schema Aplikasi Zakat
-- Untuk: Pendataan Zakat Fitrah dan Zakat Mal
-- DBMS : MySQL / MariaDB (WAMP)
-- =============================================

CREATE DATABASE IF NOT EXISTS zakat_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE zakat_db;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS penyaluran_detail;
DROP TABLE IF EXISTS penyaluran;
DROP TABLE IF EXISTS zakat_mal_detail;
DROP TABLE IF EXISTS zakat_mal;
DROP TABLE IF EXISTS jenis_harta_mal;
DROP TABLE IF EXISTS zakat_fitrah;
DROP TABLE IF EXISTS tanggungan_fitrah;
DROP TABLE IF EXISTS mustahik;
DROP TABLE IF EXISTS muzakki;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS pengaturan_aplikasi;
DROP TABLE IF EXISTS pengaturan_zakat;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- 1) Users / Admin Amil
-- =============================================
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap VARCHAR(150) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(120) NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('super_admin','amil','operator') NOT NULL DEFAULT 'operator',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 2) Pengaturan Tahunan Zakat
-- =============================================
CREATE TABLE pengaturan_zakat (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tahun YEAR NOT NULL UNIQUE,
  fitrah_per_jiwa_kg DECIMAL(8,2) NOT NULL DEFAULT 2.50,
  fitrah_per_jiwa_rupiah DECIMAL(15,2) NOT NULL DEFAULT 0,
  harga_beras_per_kg DECIMAL(15,2) NOT NULL DEFAULT 0,
  nilai_emas_per_gram DECIMAL(15,2) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 2b) Pengaturan Aplikasi / Profil Lembaga
-- =============================================
CREATE TABLE pengaturan_aplikasi (
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

-- =============================================
-- 3) Master Muzakki (pembayar zakat)
-- =============================================
CREATE TABLE muzakki (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_muzakki VARCHAR(30) NOT NULL UNIQUE,
  jenis_muzakki ENUM('individu','lembaga','kepala_keluarga') NOT NULL DEFAULT 'individu',
  nama VARCHAR(150) NOT NULL,
  nik VARCHAR(30) NULL,
  npwp VARCHAR(30) NULL,
  no_hp VARCHAR(25) NULL,
  email VARCHAR(120) NULL,
  pekerjaan VARCHAR(100) NULL,
  alamat TEXT NULL,
  kelurahan VARCHAR(100) NULL,
  kecamatan VARCHAR(100) NULL,
  kota_kabupaten VARCHAR(100) NULL,
  provinsi VARCHAR(100) NULL,
  kode_pos VARCHAR(10) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_muzakki_nama (nama),
  INDEX idx_muzakki_nik (nik)
) ENGINE=InnoDB;

-- =============================================
-- 4) Tanggungan untuk hitung Zakat Fitrah
-- =============================================
CREATE TABLE tanggungan_fitrah (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  muzakki_id BIGINT UNSIGNED NOT NULL,
  nama_anggota VARCHAR(150) NOT NULL,
  hubungan_keluarga VARCHAR(50) NULL,
  aktif_dihitung TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tanggungan_muzakki
    FOREIGN KEY (muzakki_id) REFERENCES muzakki(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_tanggungan_muzakki (muzakki_id)
) ENGINE=InnoDB;

-- =============================================
-- 5) Master Mustahik (penerima zakat)
-- =============================================
CREATE TABLE mustahik (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_mustahik VARCHAR(30) NOT NULL UNIQUE,
  nama VARCHAR(150) NOT NULL,
  nik VARCHAR(30) NULL,
  kategori_asnaf ENUM('fakir','miskin','amil','muallaf','riqab','gharimin','fisabilillah','ibnu_sabil') NOT NULL,
  no_hp VARCHAR(25) NULL,
  alamat TEXT NULL,
  aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_mustahik_nama (nama),
  INDEX idx_mustahik_asnaf (kategori_asnaf)
) ENGINE=InnoDB;

-- =============================================
-- 6) Transaksi Zakat Fitrah
-- =============================================
CREATE TABLE zakat_fitrah (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_transaksi VARCHAR(40) NOT NULL UNIQUE,
  muzakki_id BIGINT UNSIGNED NOT NULL,
  tanggal_bayar DATE NOT NULL,
  tahun_hijriah VARCHAR(10) NULL,
  tahun_masehi YEAR NOT NULL,
  jumlah_jiwa INT UNSIGNED NOT NULL,
  metode_tunaikan ENUM('beras','uang') NOT NULL,
  beras_kg DECIMAL(10,2) NOT NULL DEFAULT 0,
  nominal_uang DECIMAL(15,2) NOT NULL DEFAULT 0,
  metode_bayar ENUM('tunai','transfer','qris','lainnya') NOT NULL DEFAULT 'tunai',
  keterangan TEXT NULL,
  status ENUM('draft','lunas','batal') NOT NULL DEFAULT 'lunas',
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_fitrah_muzakki
    FOREIGN KEY (muzakki_id) REFERENCES muzakki(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_fitrah_user
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_fitrah_tanggal (tanggal_bayar),
  INDEX idx_fitrah_tahun (tahun_masehi),
  INDEX idx_fitrah_muzakki (muzakki_id)
) ENGINE=InnoDB;

-- =============================================
-- 7) Master Jenis Harta Zakat Mal
-- =============================================
CREATE TABLE jenis_harta_mal (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_jenis VARCHAR(30) NOT NULL UNIQUE,
  nama_jenis VARCHAR(120) NOT NULL,
  tarif_persen DECIMAL(5,2) NOT NULL DEFAULT 2.50,
  butuh_haul TINYINT(1) NOT NULL DEFAULT 1,
  keterangan TEXT NULL,
  aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 8) Transaksi Zakat Mal (header)
-- =============================================
CREATE TABLE zakat_mal (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_transaksi VARCHAR(40) NOT NULL UNIQUE,
  muzakki_id BIGINT UNSIGNED NOT NULL,
  tanggal_hitung DATE NOT NULL,
  tanggal_bayar DATE NULL,
  tahun_masehi YEAR NOT NULL,
  total_harta DECIMAL(18,2) NOT NULL DEFAULT 0,
  total_hutang_jatuh_tempo DECIMAL(18,2) NOT NULL DEFAULT 0,
  harta_bersih DECIMAL(18,2) NOT NULL DEFAULT 0,
  nilai_nishab DECIMAL(18,2) NOT NULL DEFAULT 0,
  persentase_zakat DECIMAL(5,2) NOT NULL DEFAULT 2.50,
  total_zakat DECIMAL(18,2) NOT NULL DEFAULT 0,
  metode_bayar ENUM('tunai','transfer','qris','lainnya') NOT NULL DEFAULT 'tunai',
  keterangan TEXT NULL,
  status ENUM('draft','lunas','batal') NOT NULL DEFAULT 'lunas',
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_mal_muzakki
    FOREIGN KEY (muzakki_id) REFERENCES muzakki(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_mal_user
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_mal_tanggal (tanggal_hitung),
  INDEX idx_mal_tahun (tahun_masehi),
  INDEX idx_mal_muzakki (muzakki_id)
) ENGINE=InnoDB;

-- =============================================
-- 9) Transaksi Zakat Mal (detail aset)
-- =============================================
CREATE TABLE zakat_mal_detail (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  zakat_mal_id BIGINT UNSIGNED NOT NULL,
  jenis_harta_id BIGINT UNSIGNED NOT NULL,
  nilai_harta DECIMAL(18,2) NOT NULL DEFAULT 0,
  nilai_haul_bulan INT UNSIGNED NULL,
  keterangan TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_mal_detail_header
    FOREIGN KEY (zakat_mal_id) REFERENCES zakat_mal(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_mal_detail_jenis
    FOREIGN KEY (jenis_harta_id) REFERENCES jenis_harta_mal(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_mal_detail_header (zakat_mal_id),
  INDEX idx_mal_detail_jenis (jenis_harta_id)
) ENGINE=InnoDB;

-- =============================================
-- 10) Penyaluran Zakat (header)
-- =============================================
CREATE TABLE penyaluran (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_penyaluran VARCHAR(40) NOT NULL UNIQUE,
  tanggal_penyaluran DATE NOT NULL,
  jenis_sumber ENUM('fitrah','mal','gabungan') NOT NULL DEFAULT 'gabungan',
  total_uang DECIMAL(18,2) NOT NULL DEFAULT 0,
  total_beras_kg DECIMAL(12,2) NOT NULL DEFAULT 0,
  keterangan TEXT NULL,
  status ENUM('draft','disalurkan','batal') NOT NULL DEFAULT 'disalurkan',
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_penyaluran_user
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_penyaluran_tanggal (tanggal_penyaluran)
) ENGINE=InnoDB;

-- =============================================
-- 11) Penyaluran Zakat (detail penerima)
-- =============================================
CREATE TABLE penyaluran_detail (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  penyaluran_id BIGINT UNSIGNED NOT NULL,
  mustahik_id BIGINT UNSIGNED NOT NULL,
  bentuk_bantuan ENUM('uang','beras','paket') NOT NULL,
  nominal_uang DECIMAL(18,2) NOT NULL DEFAULT 0,
  beras_kg DECIMAL(12,2) NOT NULL DEFAULT 0,
  keterangan TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_penyaluran_detail_header
    FOREIGN KEY (penyaluran_id) REFERENCES penyaluran(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_penyaluran_detail_mustahik
    FOREIGN KEY (mustahik_id) REFERENCES mustahik(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_penyaluran_detail_header (penyaluran_id),
  INDEX idx_penyaluran_detail_mustahik (mustahik_id)
) ENGINE=InnoDB;

-- =============================================
-- Seed data minimal
-- =============================================
INSERT INTO users (nama_lengkap, username, email, password_hash, role)
VALUES
('Super Admin', 'admin', 'admin@zakat.local', '$2y$10$z9zmE7OPD9oCOzS/4j7Y.eWgr0x4J84x31Hh6kA0r9kY/0Y6A0ZxC', 'super_admin');

INSERT INTO pengaturan_zakat (tahun, fitrah_per_jiwa_kg, fitrah_per_jiwa_rupiah, harga_beras_per_kg, nilai_emas_per_gram)
VALUES
(YEAR(CURDATE()), 2.50, 45000, 18000, 1200000)
ON DUPLICATE KEY UPDATE
  fitrah_per_jiwa_kg = VALUES(fitrah_per_jiwa_kg),
  fitrah_per_jiwa_rupiah = VALUES(fitrah_per_jiwa_rupiah),
  harga_beras_per_kg = VALUES(harga_beras_per_kg),
  nilai_emas_per_gram = VALUES(nilai_emas_per_gram);

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

INSERT INTO jenis_harta_mal (kode_jenis, nama_jenis, tarif_persen, butuh_haul, keterangan)
VALUES
('EMAS', 'Emas / Perak', 2.50, 1, 'Nishab setara 85 gram emas'),
('PERDAGANGAN', 'Harta Perniagaan', 2.50, 1, 'Aset dagang + kas - kewajiban'),
('PENDAPATAN', 'Penghasilan / Profesi', 2.50, 0, 'Bisa dibayar bulanan'),
('PERTANIAN', 'Hasil Pertanian', 5.00, 0, 'Tarif menyesuaikan pengairan')
ON DUPLICATE KEY UPDATE
  nama_jenis = VALUES(nama_jenis),
  tarif_persen = VALUES(tarif_persen),
  butuh_haul = VALUES(butuh_haul),
  keterangan = VALUES(keterangan);
