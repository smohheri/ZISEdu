-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 18, 2026 at 02:00 PM
-- Server version: 9.1.0
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zakat`
--

-- --------------------------------------------------------

--
-- Table structure for table `infaq_shodaqoh`
--

DROP TABLE IF EXISTS `infaq_shodaqoh`;
CREATE TABLE IF NOT EXISTS `infaq_shodaqoh` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_transaksi` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `no_kwitansi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL,
  `jenis_dana` enum('infaq','shodaqoh','infaq_shodaqoh') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `muzakki_kode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_donatur` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nominal_uang` decimal(18,2) NOT NULL DEFAULT '0.00',
  `metode_bayar` enum('tunai','transfer','qris','lainnya') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tunai',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` enum('draft','diterima','batal') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'diterima',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `batch_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_transaksi` (`nomor_transaksi`),
  UNIQUE KEY `no_kwitansi` (`no_kwitansi`),
  KEY `fk_infaq_shodaqoh_user` (`created_by`),
  KEY `idx_infaq_shodaqoh_tanggal` (`tanggal_transaksi`),
  KEY `idx_infaq_shodaqoh_jenis` (`jenis_dana`),
  KEY `idx_infaq_shodaqoh_nama` (`nama_donatur`),
  KEY `idx_infaq_muzakki_kode` (`muzakki_kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `infaq_shodaqoh`
--

INSERT INTO `infaq_shodaqoh` (`id`, `nomor_transaksi`, `no_kwitansi`, `tanggal_transaksi`, `jenis_dana`, `muzakki_kode`, `nama_donatur`, `no_hp`, `nominal_uang`, `metode_bayar`, `keterangan`, `status`, `created_by`, `created_at`, `updated_at`, `batch_id`) VALUES
(1, 'IS-2026-0001', 'KW/IS/2026/0001', '2026-03-18', 'infaq', NULL, 'H', NULL, 150000.00, 'tunai', NULL, 'diterima', 1, '2026-03-18 16:47:36', '2026-03-18 16:47:36', NULL),
(2, 'IS-2026-0002', 'KW/IS/2026/0002', '2026-03-18', 'infaq_shodaqoh', NULL, 'dasd', 'asd', 14567890.00, 'tunai', NULL, 'diterima', 1, '2026-03-18 17:01:26', '2026-03-18 17:23:38', NULL),
(3, 'IS-2026-0003', 'KW-2026-0001', '0000-00-00', 'infaq_shodaqoh', 'MZK-2026-0001', 'heri', '', 147000.00, 'tunai', '', 'diterima', 1, '2026-03-18 21:49:03', '2026-03-18 21:49:03', 'BATCH-20260318204903-3193'),
(4, 'IS-2026-0004', 'KW-2026-0002', '0000-00-00', 'infaq_shodaqoh', 'MZK-2026-0001', 'heri', '', 124000.00, 'tunai', '', 'diterima', 1, '2026-03-18 21:55:51', '2026-03-18 21:55:51', 'BATCH-20260318205551-9620');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_harta_mal`
--

DROP TABLE IF EXISTS `jenis_harta_mal`;
CREATE TABLE IF NOT EXISTS `jenis_harta_mal` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_jenis` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_jenis` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `tarif_persen` decimal(5,2) NOT NULL DEFAULT '2.50',
  `butuh_haul` tinyint(1) NOT NULL DEFAULT '1',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_jenis` (`kode_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_harta_mal`
--

INSERT INTO `jenis_harta_mal` (`id`, `kode_jenis`, `nama_jenis`, `tarif_persen`, `butuh_haul`, `keterangan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'EMAS', 'Emas / Perak', 2.50, 1, 'Nishab setara 85 gram emas', 1, '2026-03-18 16:46:26', NULL),
(2, 'PERDAGANGAN', 'Harta Perniagaan', 2.50, 1, 'Aset dagang + kas - kewajiban', 1, '2026-03-18 16:46:26', NULL),
(3, 'PENDAPATAN', 'Penghasilan / Profesi', 2.50, 0, 'Bisa dibayar bulanan', 1, '2026-03-18 16:46:26', NULL),
(4, 'PERTANIAN', 'Hasil Pertanian', 5.00, 0, 'Tarif menyesuaikan pengairan', 1, '2026-03-18 16:46:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mustahik`
--

DROP TABLE IF EXISTS `mustahik`;
CREATE TABLE IF NOT EXISTS `mustahik` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_mustahik` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kategori_asnaf` enum('fakir','miskin','amil','muallaf','riqab','gharimin','fisabilillah','ibnu_sabil') COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_mustahik` (`kode_mustahik`),
  KEY `idx_mustahik_nama` (`nama`),
  KEY `idx_mustahik_asnaf` (`kategori_asnaf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `muzakki`
--

DROP TABLE IF EXISTS `muzakki`;
CREATE TABLE IF NOT EXISTS `muzakki` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_muzakki` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_muzakki` enum('individu','lembaga','kepala_keluarga') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'individu',
  `nama` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `npwp` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `kelurahan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota_kabupaten` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_muzakki` (`kode_muzakki`),
  KEY `idx_muzakki_nama` (`nama`),
  KEY `idx_muzakki_nik` (`nik`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `muzakki`
--

INSERT INTO `muzakki` (`id`, `kode_muzakki`, `jenis_muzakki`, `nama`, `nik`, `npwp`, `no_hp`, `email`, `pekerjaan`, `alamat`, `kelurahan`, `kecamatan`, `kota_kabupaten`, `provinsi`, `kode_pos`, `created_at`, `updated_at`) VALUES
(1, 'MZK-2026-0001', 'kepala_keluarga', 'heri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-18 17:53:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_aplikasi`
--

DROP TABLE IF EXISTS `pengaturan_aplikasi`;
CREATE TABLE IF NOT EXISTS `pengaturan_aplikasi` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_lembaga` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lembaga` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_pimpinan` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_bendahara` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `kelurahan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota_kabupaten` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `npwp` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kop_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stempel_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rekening_atas_nama` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rekening_nomor` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rekening_bank` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan_kuitansi` text COLLATE utf8mb4_general_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_lembaga` (`kode_lembaga`),
  KEY `idx_pengaturan_aplikasi_nama` (`nama_lembaga`),
  KEY `idx_pengaturan_aplikasi_aktif` (`aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_aplikasi`
--

INSERT INTO `pengaturan_aplikasi` (`id`, `kode_lembaga`, `nama_lembaga`, `nama_pimpinan`, `nama_bendahara`, `alamat`, `kelurahan`, `kecamatan`, `kota_kabupaten`, `provinsi`, `kode_pos`, `no_telp`, `no_hp`, `email`, `website`, `npwp`, `logo_path`, `kop_path`, `stempel_path`, `rekening_atas_nama`, `rekening_nomor`, `rekening_bank`, `catatan_kuitansi`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'LMBG-001', 'Lembaga Zakat', NULL, NULL, 'Alamat lembaga zakat', NULL, NULL, NULL, NULL, NULL, '-', NULL, 'admin@zakat.local', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-18 16:46:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_zakat`
--

DROP TABLE IF EXISTS `pengaturan_zakat`;
CREATE TABLE IF NOT EXISTS `pengaturan_zakat` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` year NOT NULL,
  `fitrah_per_jiwa_kg` decimal(8,2) NOT NULL DEFAULT '2.50',
  `fitrah_per_jiwa_rupiah` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_beras_per_kg` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_emas_per_gram` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun` (`tahun`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_zakat`
--

INSERT INTO `pengaturan_zakat` (`id`, `tahun`, `fitrah_per_jiwa_kg`, `fitrah_per_jiwa_rupiah`, `harga_beras_per_kg`, `nilai_emas_per_gram`, `created_at`, `updated_at`) VALUES
(1, '2026', 2.50, 45000.00, 18000.00, 1200000.00, '2026-03-18 16:46:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penyaluran`
--

DROP TABLE IF EXISTS `penyaluran`;
CREATE TABLE IF NOT EXISTS `penyaluran` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_penyaluran` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_penyaluran` date NOT NULL,
  `jenis_sumber` enum('fitrah','mal','gabungan') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'gabungan',
  `total_uang` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_beras_kg` decimal(12,2) NOT NULL DEFAULT '0.00',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` enum('draft','disalurkan','batal') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'disalurkan',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_penyaluran` (`nomor_penyaluran`),
  KEY `fk_penyaluran_user` (`created_by`),
  KEY `idx_penyaluran_tanggal` (`tanggal_penyaluran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyaluran_detail`
--

DROP TABLE IF EXISTS `penyaluran_detail`;
CREATE TABLE IF NOT EXISTS `penyaluran_detail` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `penyaluran_id` bigint UNSIGNED NOT NULL,
  `mustahik_id` bigint UNSIGNED NOT NULL,
  `bentuk_bantuan` enum('uang','beras','paket') COLLATE utf8mb4_general_ci NOT NULL,
  `nominal_uang` decimal(18,2) NOT NULL DEFAULT '0.00',
  `beras_kg` decimal(12,2) NOT NULL DEFAULT '0.00',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_penyaluran_detail_header` (`penyaluran_id`),
  KEY `idx_penyaluran_detail_mustahik` (`mustahik_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tanggungan_fitrah`
--

DROP TABLE IF EXISTS `tanggungan_fitrah`;
CREATE TABLE IF NOT EXISTS `tanggungan_fitrah` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `muzakki_id` bigint UNSIGNED NOT NULL,
  `nama_anggota` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `hubungan_keluarga` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aktif_dihitung` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggungan_muzakki` (`muzakki_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tanggungan_fitrah`
--

INSERT INTO `tanggungan_fitrah` (`id`, `muzakki_id`, `nama_anggota`, `hubungan_keluarga`, `aktif_dihitung`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', NULL, 1, '2026-03-18 17:53:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('super_admin','amil','operator') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'operator',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `password_hash`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', 'admin@zakat.local', '$2y$10$qTGB4Q2QurefC.PmHfcO9ulVZfEMpmhT/Z/FgJ.VTQ.81.uHOv1ni', 'super_admin', 1, '2026-03-18 15:47:25', '2026-03-18 16:46:26', '2026-03-18 16:47:25');

-- --------------------------------------------------------

--
-- Table structure for table `zakat_fitrah`
--

DROP TABLE IF EXISTS `zakat_fitrah`;
CREATE TABLE IF NOT EXISTS `zakat_fitrah` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_transaksi` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `no_kwitansi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `muzakki_id` bigint UNSIGNED NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `tahun_hijriah` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_masehi` year NOT NULL,
  `jumlah_jiwa` int UNSIGNED NOT NULL,
  `metode_tunaikan` enum('beras','uang') COLLATE utf8mb4_general_ci NOT NULL,
  `beras_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `nominal_uang` decimal(15,2) NOT NULL DEFAULT '0.00',
  `metode_bayar` enum('tunai','transfer','qris','lainnya') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tunai',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` enum('draft','lunas','batal') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'lunas',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `batch_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_transaksi` (`nomor_transaksi`),
  UNIQUE KEY `no_kwitansi` (`no_kwitansi`),
  KEY `fk_fitrah_user` (`created_by`),
  KEY `idx_fitrah_tanggal` (`tanggal_bayar`),
  KEY `idx_fitrah_tahun` (`tahun_masehi`),
  KEY `idx_fitrah_muzakki` (`muzakki_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zakat_fitrah`
--

INSERT INTO `zakat_fitrah` (`id`, `nomor_transaksi`, `no_kwitansi`, `muzakki_id`, `tanggal_bayar`, `tahun_hijriah`, `tahun_masehi`, `jumlah_jiwa`, `metode_tunaikan`, `beras_kg`, `nominal_uang`, `metode_bayar`, `keterangan`, `status`, `created_by`, `created_at`, `updated_at`, `batch_id`) VALUES
(1, 'ZF-2026-0001', 'KW-2026-0001', 1, '2026-03-18', NULL, '2026', 2, 'beras', 0.00, 150000.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:44:23', '2026-03-18 21:44:23', 'BATCH-20260318204423-8600'),
(2, 'ZF-2026-0002', 'KW-2026-0002', 1, '2026-03-18', NULL, '2026', 2, 'beras', 15.00, 0.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:45:00', '2026-03-18 21:45:00', 'BATCH-20260318204500-4447'),
(3, 'ZF-2026-0003', 'KW-2026-0003', 1, '2026-03-18', NULL, '2026', 2, 'uang', 0.00, 200000.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:47:18', '2026-03-18 21:47:18', 'BATCH-20260318204718-4482'),
(4, 'ZF-2026-0004', 'KW-2026-0004', 1, '2026-03-18', NULL, '2026', 2, 'uang', 0.00, 15000000.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:49:03', '2026-03-18 21:49:03', 'BATCH-20260318204903-3193'),
(5, 'ZF-2026-0005', 'KW-2026-0005', 1, '2026-03-18', NULL, '2026', 2, 'beras', 23.00, 0.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:55:17', '2026-03-18 21:55:17', 'BATCH-20260318205517-8148'),
(6, 'ZF-2026-0006', 'KW-2026-0006', 1, '2026-03-18', NULL, '2026', 2, 'beras', 23.00, 0.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:55:51', '2026-03-18 21:55:51', 'BATCH-20260318205551-9620');

-- --------------------------------------------------------

--
-- Table structure for table `zakat_mal`
--

DROP TABLE IF EXISTS `zakat_mal`;
CREATE TABLE IF NOT EXISTS `zakat_mal` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_transaksi` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `no_kwitansi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `muzakki_id` bigint UNSIGNED NOT NULL,
  `tanggal_hitung` date NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `tahun_masehi` year NOT NULL,
  `total_harta` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_hutang_jatuh_tempo` decimal(18,2) NOT NULL DEFAULT '0.00',
  `harta_bersih` decimal(18,2) NOT NULL DEFAULT '0.00',
  `nilai_nishab` decimal(18,2) NOT NULL DEFAULT '0.00',
  `persentase_zakat` decimal(5,2) NOT NULL DEFAULT '2.50',
  `total_zakat` decimal(18,2) NOT NULL DEFAULT '0.00',
  `metode_bayar` enum('tunai','transfer','qris','lainnya') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tunai',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` enum('draft','lunas','batal') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'lunas',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `batch_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_transaksi` (`nomor_transaksi`),
  UNIQUE KEY `no_kwitansi` (`no_kwitansi`),
  KEY `fk_mal_user` (`created_by`),
  KEY `idx_mal_tanggal` (`tanggal_hitung`),
  KEY `idx_mal_tahun` (`tahun_masehi`),
  KEY `idx_mal_muzakki` (`muzakki_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zakat_mal`
--

INSERT INTO `zakat_mal` (`id`, `nomor_transaksi`, `no_kwitansi`, `muzakki_id`, `tanggal_hitung`, `tanggal_bayar`, `tahun_masehi`, `total_harta`, `total_hutang_jatuh_tempo`, `harta_bersih`, `nilai_nishab`, `persentase_zakat`, `total_zakat`, `metode_bayar`, `keterangan`, `status`, `created_by`, `created_at`, `updated_at`, `batch_id`) VALUES
(1, 'ZM-2026-0001', 'KW-2026-0001', 1, '2026-03-18', '2026-03-18', '2026', 0.00, 0.00, 0.00, 0.00, 0.00, 150000.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:55:17', '2026-03-18 21:55:17', 'BATCH-20260318205517-8148'),
(2, 'ZM-2026-0002', 'KW-2026-0002', 1, '2026-03-18', '2026-03-18', '2026', 0.00, 0.00, 0.00, 0.00, 0.00, 15000.00, 'tunai', '', 'lunas', 1, '2026-03-18 21:55:51', '2026-03-18 21:55:51', 'BATCH-20260318205551-9620');

-- --------------------------------------------------------

--
-- Table structure for table `zakat_mal_detail`
--

DROP TABLE IF EXISTS `zakat_mal_detail`;
CREATE TABLE IF NOT EXISTS `zakat_mal_detail` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `zakat_mal_id` bigint UNSIGNED NOT NULL,
  `jenis_harta_id` bigint UNSIGNED NOT NULL,
  `nilai_harta` decimal(18,2) NOT NULL DEFAULT '0.00',
  `nilai_haul_bulan` int UNSIGNED DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_mal_detail_header` (`zakat_mal_id`),
  KEY `idx_mal_detail_jenis` (`jenis_harta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `infaq_shodaqoh`
--
ALTER TABLE `infaq_shodaqoh`
  ADD CONSTRAINT `fk_infaq_muzakki_kode` FOREIGN KEY (`muzakki_kode`) REFERENCES `muzakki` (`kode_muzakki`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_infaq_shodaqoh_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `penyaluran`
--
ALTER TABLE `penyaluran`
  ADD CONSTRAINT `fk_penyaluran_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `penyaluran_detail`
--
ALTER TABLE `penyaluran_detail`
  ADD CONSTRAINT `fk_penyaluran_detail_header` FOREIGN KEY (`penyaluran_id`) REFERENCES `penyaluran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penyaluran_detail_mustahik` FOREIGN KEY (`mustahik_id`) REFERENCES `mustahik` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `tanggungan_fitrah`
--
ALTER TABLE `tanggungan_fitrah`
  ADD CONSTRAINT `fk_tanggungan_muzakki` FOREIGN KEY (`muzakki_id`) REFERENCES `muzakki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `zakat_fitrah`
--
ALTER TABLE `zakat_fitrah`
  ADD CONSTRAINT `fk_fitrah_muzakki` FOREIGN KEY (`muzakki_id`) REFERENCES `muzakki` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fitrah_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `zakat_mal`
--
ALTER TABLE `zakat_mal`
  ADD CONSTRAINT `fk_mal_muzakki` FOREIGN KEY (`muzakki_id`) REFERENCES `muzakki` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mal_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `zakat_mal_detail`
--
ALTER TABLE `zakat_mal_detail`
  ADD CONSTRAINT `fk_mal_detail_header` FOREIGN KEY (`zakat_mal_id`) REFERENCES `zakat_mal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mal_detail_jenis` FOREIGN KEY (`jenis_harta_id`) REFERENCES `jenis_harta_mal` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
