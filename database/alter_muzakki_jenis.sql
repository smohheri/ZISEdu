ALTER TABLE muzakki
MODIFY COLUMN jenis_muzakki ENUM('individu','lembaga','kepala_keluarga') NOT NULL DEFAULT 'individu';
