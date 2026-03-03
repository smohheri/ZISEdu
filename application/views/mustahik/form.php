<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'mustahik/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Mustahik</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <div class="row">
            <?php
            $kodeMustahik = isset($row->kode_mustahik)
                ? $row->kode_mustahik
                : (isset($auto_kode) ? $auto_kode : '');
            ?>
            <div class="col-md-4 form-group"><label>Kode Mustahik</label><input type="text" name="kode_mustahik"
                    class="form-control" value="<?php echo set_value('kode_mustahik', $kodeMustahik); ?>" readonly
                    required></div>
            <div class="col-md-4 form-group"><label>Nama</label><input type="text" name="nama" class="form-control"
                    value="<?php echo set_value('nama', isset($row->nama) ? $row->nama : ''); ?>" required></div>
            <div class="col-md-4 form-group"><label>NIK</label><input type="text" name="nik" class="form-control"
                    value="<?php echo set_value('nik', isset($row->nik) ? $row->nik : ''); ?>"></div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Kategori Asnaf</label>
                <?php $asnaf = set_value('kategori_asnaf', isset($row->kategori_asnaf) ? $row->kategori_asnaf : 'fakir'); ?>
                <select name="kategori_asnaf" class="form-control" required>
                    <?php $asnafList = array('fakir', 'miskin', 'amil', 'muallaf', 'riqab', 'gharimin', 'fisabilillah', 'ibnu_sabil'); ?>
                    <?php foreach ($asnafList as $item): ?>
                        <option value="<?php echo $item; ?>" <?php echo ($asnaf === $item) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 form-group"><label>No HP</label><input type="text" name="no_hp" class="form-control"
                    value="<?php echo set_value('no_hp', isset($row->no_hp) ? $row->no_hp : ''); ?>"></div>
        </div>
        <div class="form-group"><label>Alamat</label><textarea name="alamat" rows="2"
                class="form-control"><?php echo set_value('alamat', isset($row->alamat) ? $row->alamat : ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Status</label>
            <?php $aktif = (int) set_value('aktif', isset($row->aktif) ? $row->aktif : 1); ?>
            <select name="aktif" class="form-control">
                <option value="1" <?php echo ($aktif === 1) ? 'selected' : ''; ?>>Aktif</option>
                <option value="0" <?php echo ($aktif === 0) ? 'selected' : ''; ?>>Nonaktif</option>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('mustahik'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
