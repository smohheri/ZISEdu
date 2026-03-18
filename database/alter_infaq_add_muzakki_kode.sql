-- ALTER TABLE to add muzakki_kode for infaq_shodaqoh (references muzakki.kode_muzakki varchar(30))

ALTER TABLE `infaq_shodaqoh` 
ADD COLUMN `muzakki_kode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `jenis_dana`,
ADD KEY `idx_infaq_muzakki_kode` (`muzakki_kode`),
ADD CONSTRAINT `fk_infaq_muzakki_kode` FOREIGN KEY (`muzakki_kode`) REFERENCES `muzakki` (`kode_muzakki`) ON DELETE SET NULL ON UPDATE CASCADE;

