-- Tambahan kolom nomor kwitansi agar konsisten tersimpan di database
-- Jalankan sekali pada database yang sudah existing.

ALTER TABLE zakat_fitrah
    ADD COLUMN no_kwitansi VARCHAR(50) NULL UNIQUE AFTER nomor_transaksi;

ALTER TABLE zakat_mal
    ADD COLUMN no_kwitansi VARCHAR(50) NULL UNIQUE AFTER nomor_transaksi;

ALTER TABLE infaq_shodaqoh
    ADD COLUMN no_kwitansi VARCHAR(50) NULL UNIQUE AFTER nomor_transaksi;
