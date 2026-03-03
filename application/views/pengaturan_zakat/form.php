<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'pengaturan_zakat/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Pengaturan Zakat</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label>Tahun</label>
            <input type="number" min="2000" max="2100" name="tahun" class="form-control"
                value="<?php echo set_value('tahun', isset($row->tahun) ? $row->tahun : date('Y')); ?>" required>
        </div>
        <div class="form-group">
            <label>Fitrah per Jiwa (Kg)</label>
            <input type="number" step="0.01" name="fitrah_per_jiwa_kg" class="form-control"
                value="<?php echo set_value('fitrah_per_jiwa_kg', isset($row->fitrah_per_jiwa_kg) ? $row->fitrah_per_jiwa_kg : 2.5); ?>"
                required>
        </div>
        <div class="form-group">
            <label>Fitrah per Jiwa (Rp)</label>
            <input type="number" step="0.01" name="fitrah_per_jiwa_rupiah" class="form-control"
                value="<?php echo set_value('fitrah_per_jiwa_rupiah', isset($row->fitrah_per_jiwa_rupiah) ? $row->fitrah_per_jiwa_rupiah : 0); ?>"
                required>
        </div>
        <div class="form-group">
            <label>Harga Beras per Kg (Rp)</label>
            <input type="number" step="0.01" name="harga_beras_per_kg" class="form-control"
                value="<?php echo set_value('harga_beras_per_kg', isset($row->harga_beras_per_kg) ? $row->harga_beras_per_kg : 0); ?>"
                required>
        </div>
        <div class="form-group">
            <label>Nilai Emas per Gram (Rp)</label>
            <input type="number" step="0.01" name="nilai_emas_per_gram" class="form-control"
                value="<?php echo set_value('nilai_emas_per_gram', isset($row->nilai_emas_per_gram) ? $row->nilai_emas_per_gram : 0); ?>"
                required>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('pengaturan_zakat'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
