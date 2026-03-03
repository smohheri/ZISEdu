<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'jenis_harta_mal/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Jenis Harta Mal</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 form-group"><label>Kode Jenis</label><input type="text" name="kode_jenis"
                    class="form-control"
                    value="<?php echo set_value('kode_jenis', isset($row->kode_jenis) ? $row->kode_jenis : ''); ?>"
                    required></div>
            <div class="col-md-8 form-group"><label>Nama Jenis</label><input type="text" name="nama_jenis"
                    class="form-control"
                    value="<?php echo set_value('nama_jenis', isset($row->nama_jenis) ? $row->nama_jenis : ''); ?>"
                    required></div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group"><label>Tarif (%)</label><input type="number" step="0.01"
                    name="tarif_persen" class="form-control"
                    value="<?php echo set_value('tarif_persen', isset($row->tarif_persen) ? $row->tarif_persen : 2.5); ?>"
                    required></div>
            <div class="col-md-4 form-group">
                <label>Butuh Haul</label>
                <?php $butuh = (int) set_value('butuh_haul', isset($row->butuh_haul) ? $row->butuh_haul : 1); ?>
                <select class="form-control" name="butuh_haul">
                    <option value="1" <?php echo ($butuh === 1) ? 'selected' : ''; ?>>Ya</option>
                    <option value="0" <?php echo ($butuh === 0) ? 'selected' : ''; ?>>Tidak</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>Status</label>
                <?php $aktif = (int) set_value('aktif', isset($row->aktif) ? $row->aktif : 1); ?>
                <select class="form-control" name="aktif">
                    <option value="1" <?php echo ($aktif === 1) ? 'selected' : ''; ?>>Aktif</option>
                    <option value="0" <?php echo ($aktif === 0) ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="form-group"><label>Keterangan</label><textarea name="keterangan" rows="2"
                class="form-control"><?php echo set_value('keterangan', isset($row->keterangan) ? $row->keterangan : ''); ?></textarea>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('jenis_harta_mal'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
