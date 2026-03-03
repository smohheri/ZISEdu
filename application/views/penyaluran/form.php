<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'penyaluran/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Penyaluran Zakat</h3>
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
            $nomorPenyaluran = isset($row->nomor_penyaluran)
                ? $row->nomor_penyaluran
                : (isset($auto_nomor) ? $auto_nomor : '');
            ?>
            <div class="col-md-4 form-group"><label>No Penyaluran</label><input type="text" name="nomor_penyaluran"
                    class="form-control" value="<?php echo set_value('nomor_penyaluran', $nomorPenyaluran); ?>" readonly
                    required></div>
            <div class="col-md-4 form-group"><label>Tanggal Penyaluran</label><input type="date"
                    name="tanggal_penyaluran" class="form-control"
                    value="<?php echo set_value('tanggal_penyaluran', isset($row->tanggal_penyaluran) ? $row->tanggal_penyaluran : date('Y-m-d')); ?>"
                    required></div>
            <?php $jenisSumber = set_value('jenis_sumber', isset($row->jenis_sumber) ? $row->jenis_sumber : 'gabungan'); ?>
            <div class="col-md-4 form-group"><label>Jenis Sumber</label><select name="jenis_sumber"
                    class="form-control">
                    <option value="fitrah" <?php echo ($jenisSumber === 'fitrah') ? 'selected' : ''; ?>>Fitrah</option>
                    <option value="mal" <?php echo ($jenisSumber === 'mal') ? 'selected' : ''; ?>>Mal</option>
                    <option value="gabungan" <?php echo ($jenisSumber === 'gabungan') ? 'selected' : ''; ?>>Gabungan
                    </option>
                </select></div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group"><label>Total Uang</label><input type="number" step="0.01" name="total_uang"
                    class="form-control"
                    value="<?php echo set_value('total_uang', isset($row->total_uang) ? $row->total_uang : 0); ?>"
                    required></div>
            <div class="col-md-6 form-group"><label>Total Beras (Kg)</label><input type="number" step="0.01"
                    name="total_beras_kg" class="form-control"
                    value="<?php echo set_value('total_beras_kg', isset($row->total_beras_kg) ? $row->total_beras_kg : 0); ?>"
                    required></div>
        </div>

        <?php $status = set_value('status', isset($row->status) ? $row->status : 'disalurkan'); ?>
        <div class="form-group"><label>Status</label><select name="status" class="form-control">
                <option value="draft" <?php echo ($status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                <option value="disalurkan" <?php echo ($status === 'disalurkan') ? 'selected' : ''; ?>>Disalurkan</option>
                <option value="batal" <?php echo ($status === 'batal') ? 'selected' : ''; ?>>Batal</option>
            </select></div>
        <div class="form-group"><label>Keterangan</label><textarea name="keterangan" rows="2"
                class="form-control"><?php echo set_value('keterangan', isset($row->keterangan) ? $row->keterangan : ''); ?></textarea>
        </div>

        <hr>
        <h5>Detail Penerima (penyaluran_detail)</h5>
        <?php
        $detailRows = isset($detail_rows) && is_array($detail_rows) ? $detail_rows : array();
        if (empty($detailRows)) {
            $detailRows = array(
                (object) array(
                    'mustahik_id' => '',
                    'bentuk_bantuan' => 'uang',
                    'nominal_uang' => '',
                    'beras_kg' => '',
                    'keterangan' => ''
                )
            );
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Mustahik</th>
                        <th>Bentuk Bantuan</th>
                        <th>Nominal Uang</th>
                        <th>Beras (Kg)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detailRows as $idx => $d): ?>
                        <tr>
                            <td>
                                <?php $selectedMustahik = set_value('detail[' . $idx . '][mustahik_id]', isset($d->mustahik_id) ? $d->mustahik_id : ''); ?>
                                <select class="form-control" name="detail[<?php echo $idx; ?>][mustahik_id]">
                                    <option value="">-- Pilih Mustahik --</option>
                                    <?php if (!empty($mustahik_options)):
                                        foreach ($mustahik_options as $id => $nama): ?>
                                            <option value="<?php echo $id; ?>" <?php echo ((string) $id === (string) $selectedMustahik) ? 'selected' : ''; ?>><?php echo html_escape($nama); ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                            </td>
                            <td>
                                <?php $bentuk = set_value('detail[' . $idx . '][bentuk_bantuan]', isset($d->bentuk_bantuan) ? $d->bentuk_bantuan : 'uang'); ?>
                                <select class="form-control" name="detail[<?php echo $idx; ?>][bentuk_bantuan]">
                                    <option value="uang" <?php echo ($bentuk === 'uang') ? 'selected' : ''; ?>>Uang</option>
                                    <option value="beras" <?php echo ($bentuk === 'beras') ? 'selected' : ''; ?>>Beras
                                    </option>
                                    <option value="paket" <?php echo ($bentuk === 'paket') ? 'selected' : ''; ?>>Paket
                                    </option>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="detail[<?php echo $idx; ?>][nominal_uang]"
                                    class="form-control"
                                    value="<?php echo set_value('detail[' . $idx . '][nominal_uang]', isset($d->nominal_uang) ? $d->nominal_uang : ''); ?>">
                            </td>
                            <td><input type="number" step="0.01" name="detail[<?php echo $idx; ?>][beras_kg]"
                                    class="form-control"
                                    value="<?php echo set_value('detail[' . $idx . '][beras_kg]', isset($d->beras_kg) ? $d->beras_kg : ''); ?>">
                            </td>
                            <td><input type="text" name="detail[<?php echo $idx; ?>][keterangan]" class="form-control"
                                    value="<?php echo set_value('detail[' . $idx . '][keterangan]', isset($d->keterangan) ? $d->keterangan : ''); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('penyaluran'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
