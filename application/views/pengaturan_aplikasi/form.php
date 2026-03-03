<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open_multipart(isset($form_action) ? $form_action : 'pengaturan_aplikasi/update'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-edit mr-2"></i>Form Pengaturan Aplikasi / Profil Lembaga
        </h3>
        <a href="<?php echo site_url('pengaturan_aplikasi'); ?>" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" id="alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning alert-dismissible fade show" id="alert-validation">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo validation_errors(); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-primary h-100">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Profil Lembaga</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Lembaga</label>
                            <input type="text" name="kode_lembaga" class="form-control form-control-sm" maxlength="30"
                                value="<?php echo set_value('kode_lembaga', isset($row->kode_lembaga) ? $row->kode_lembaga : 'LMBG-001'); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Nama Lembaga</label>
                            <input type="text" name="nama_lembaga" class="form-control form-control-sm" maxlength="200"
                                value="<?php echo set_value('nama_lembaga', isset($row->nama_lembaga) ? $row->nama_lembaga : 'ZISEdu'); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Nama Pimpinan</label>
                            <input type="text" name="nama_pimpinan" class="form-control form-control-sm" maxlength="150"
                                value="<?php echo set_value('nama_pimpinan', isset($row->nama_pimpinan) ? $row->nama_pimpinan : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nama Bendahara</label>
                            <input type="text" name="nama_bendahara" class="form-control form-control-sm" maxlength="150"
                                value="<?php echo set_value('nama_bendahara', isset($row->nama_bendahara) ? $row->nama_bendahara : ''); ?>">
                        </div>
                        <div class="form-group mb-0">
                            <label>Alamat</label>
                            <textarea name="alamat" rows="3"
                                class="form-control form-control-sm"><?php echo set_value('alamat', isset($row->alamat) ? $row->alamat : ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline card-info h-100">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Kontak dan Rekening</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" maxlength="120"
                                value="<?php echo set_value('email', isset($row->email) ? $row->email : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control form-control-sm" maxlength="120"
                                value="<?php echo set_value('website', isset($row->website) ? $row->website : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>No Telp</label>
                            <input type="text" name="no_telp" class="form-control form-control-sm" maxlength="30"
                                value="<?php echo set_value('no_telp', isset($row->no_telp) ? $row->no_telp : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control form-control-sm" maxlength="30"
                                value="<?php echo set_value('no_hp', isset($row->no_hp) ? $row->no_hp : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>NPWP</label>
                            <input type="text" name="npwp" class="form-control form-control-sm" maxlength="50"
                                value="<?php echo set_value('npwp', isset($row->npwp) ? $row->npwp : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Rekening Bank</label>
                            <input type="text" name="rekening_bank" class="form-control form-control-sm" maxlength="80"
                                value="<?php echo set_value('rekening_bank', isset($row->rekening_bank) ? $row->rekening_bank : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input type="text" name="rekening_nomor" class="form-control form-control-sm" maxlength="50"
                                value="<?php echo set_value('rekening_nomor', isset($row->rekening_nomor) ? $row->rekening_nomor : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Atas Nama Rekening</label>
                            <input type="text" name="rekening_atas_nama" class="form-control form-control-sm" maxlength="150"
                                value="<?php echo set_value('rekening_atas_nama', isset($row->rekening_atas_nama) ? $row->rekening_atas_nama : ''); ?>">
                        </div>
                        <div class="form-group mb-0">
                            <label>Catatan Kwitansi</label>
                            <textarea name="catatan_kuitansi" rows="2"
                                class="form-control form-control-sm"><?php echo set_value('catatan_kuitansi', isset($row->catatan_kuitansi) ? $row->catatan_kuitansi : ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Upload Logo</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <input type="file" name="logo_path" class="form-control-file" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-info btn-sm" name="upload_target" value="logo_path">
                            <i class="fas fa-upload"></i> Upload Logo
                        </button>
                        <?php if (!empty($row->logo_path)): ?>
                            <small class="d-block text-muted mt-2">Saat ini:</small>
                            <img src="<?php echo base_url($row->logo_path); ?>" alt="Logo" class="img-fluid img-thumbnail mt-1 media-preview">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Upload Kop Surat</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <input type="file" name="kop_path" class="form-control-file" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-info btn-sm" name="upload_target" value="kop_path">
                            <i class="fas fa-upload"></i> Upload Kop
                        </button>
                        <?php if (!empty($row->kop_path)): ?>
                            <small class="d-block text-muted mt-2">Saat ini:</small>
                            <img src="<?php echo base_url($row->kop_path); ?>" alt="Kop" class="img-fluid img-thumbnail mt-1 media-preview">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-secondary">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Upload Stempel</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <input type="file" name="stempel_path" class="form-control-file" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-info btn-sm" name="upload_target" value="stempel_path">
                            <i class="fas fa-upload"></i> Upload Stempel
                        </button>
                        <?php if (!empty($row->stempel_path)): ?>
                            <small class="d-block text-muted mt-2">Saat ini:</small>
                            <img src="<?php echo base_url($row->stempel_path); ?>" alt="Stempel" class="img-fluid img-thumbnail mt-1 media-preview">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-warning mb-0">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">Status Aplikasi</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <label>Status Aktif</label>
                    <select name="aktif" class="form-control form-control-sm" required>
                        <?php $aktif = set_value('aktif', isset($row->aktif) ? (int) $row->aktif : 1); ?>
                        <option value="1" <?php echo ((int) $aktif === 1) ? 'selected' : ''; ?>>Aktif</option>
                        <option value="0" <?php echo ((int) $aktif === 0) ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="<?php echo site_url('pengaturan_aplikasi'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#alert-error, #alert-validation').fadeOut('slow');
        }, 5000);
    });
</script>

<style>
    .media-preview {
        max-height: 150px;
        width: auto;
    }
</style>

