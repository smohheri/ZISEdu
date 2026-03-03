<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'tanggungan_fitrah/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Tanggungan Fitrah</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label>Muzakki</label>
            <?php $selectedMuzakki = (string) set_value('muzakki_id', isset($row->muzakki_id) ? $row->muzakki_id : ''); ?>
            <select name="muzakki_id" class="form-control" required>
                <option value="">-- Pilih Muzakki --</option>
                <?php if (!empty($muzakki_options)):
                    foreach ($muzakki_options as $id => $nama): ?>
                        <option value="<?php echo $id; ?>" <?php echo ((string) $id === $selectedMuzakki) ? 'selected' : ''; ?>>
                            <?php echo html_escape($nama); ?>
                        </option>
                    <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="form-group"><label>Nama Anggota</label><input type="text" name="nama_anggota" class="form-control"
                value="<?php echo set_value('nama_anggota', isset($row->nama_anggota) ? $row->nama_anggota : ''); ?>"
                required></div>
        <div class="form-group"><label>Hubungan Keluarga</label><input type="text" name="hubungan_keluarga"
                class="form-control"
                value="<?php echo set_value('hubungan_keluarga', isset($row->hubungan_keluarga) ? $row->hubungan_keluarga : ''); ?>">
        </div>
        <div class="form-group">
            <label>Aktif Dihitung</label>
            <?php $aktif = (int) set_value('aktif_dihitung', isset($row->aktif_dihitung) ? $row->aktif_dihitung : 1); ?>
            <select name="aktif_dihitung" class="form-control">
                <option value="1" <?php echo ($aktif === 1) ? 'selected' : ''; ?>>Ya</option>
                <option value="0" <?php echo ($aktif === 0) ? 'selected' : ''; ?>>Tidak</option>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('tanggungan_fitrah'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
