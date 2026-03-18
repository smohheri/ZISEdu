# ZIS Edu - Transaksi Unified Infaq Shodaqoh: Use Muzakki Name

## Overview
Update transaksi_unified so infaq shodaqoh card takes donor name from selected muzakki. Add DB column muzakki_kode varchar(30).

## Steps

### Step 1: [READY] Create DB ALTER script\n- File: database/alter_infaq_add_muzakki_kode.sql\n- ✅ Created\n- ⏳ User: Run SQL on DB\n- Status: Run `mysql -u root -p zakat < database/alter_infaq_add_muzakki_kode.sql`
- File: database/alter_infaq_add_muzakki_kode.sql
- Content: ALTER TABLE `infaq_shodaqoh` ADD COLUMN `muzakki_kode` varchar(30) NULL AFTER `jenis_dana`, ADD KEY `idx_muzakki_kode` (`muzakki_kode`), ADD CONSTRAINT `fk_infaq_muzakki_kode` FOREIGN KEY (`muzakki_kode`) REFERENCES `muzakki` (`kode_muzakki`) ON DELETE SET NULL ON UPDATE CASCADE;

### Step 2: [DONE ✅] Update Controller Transaksi_unified.php\n- Loaded Muzakki_model\n- Updated _build_infaq_payload() to set muzakki_kode and nama_donatur from selected muzakki
- Load Muzakki_model
- Update _build_infaq_payload(): extract kode from muzakki_id or use post, set 'muzakki_kode' and 'nama_donatur' from muzakki->nama

### Step 3: [DONE ✅] Update Infaq_shodaqoh_model.php\n- Added _base_query() with LEFT JOIN muzakki on muzakki_kode, select nama_muzakki\n- Updated _apply_search, count_filtered, get_paginated, get_by_id, get_by_batch to use _base_query and prefixed tables

### Step 4: [DONE ✅] Update form.php (transaksi_unified)\n- Made nama_donatur_infaq readonly, label \"Nama Muzakki (Donatur)\"

### Step 5: [DONE ✅] Update kwitansi.php (transaksi_unified)\n- Updated Donatur label and display to nama_muzakki fallback

### Step 6: [DONE ✅] Update infaq_shodaqoh views for consistency\n- index.php: nama_donatur -> nama_muzakki fallback\n- kwitansi.php: Nama Donatur label/display -> Donatur/Muzakki with fallback, signature updated

### Step 7: [READY] Test & Complete ✅
- ✅ DB ALTER script ready: Run `mysql -u root -p zakat < database/alter_infaq_add_muzakki_kode.sql`
- Test: 
  1. Go http://localhost/zisedu/transaksi_unified
  2. Select muzakki (e.g. heri)
  3. Check infaq section: nama_donatur auto \"heri\" readonly
  4. Check infaq checkbox, fill nominal etc, submit
  5. Kwitansi shows \"Donatur/Muzakki: heri\"
  6. DB infaq_shodaqoh has muzakki_kode='MZK-2026-0001', nama_donatur='heri'
- All code changes complete.

**TASK COMPLETE** - Run DB ALTER then test!

