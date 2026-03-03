<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'infaq_shodaqoh/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Transaksi Infaq & Shodaqoh</h3>
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
            $nomorTransaksi = isset($row->nomor_transaksi)
                ? $row->nomor_transaksi
                : (isset($auto_nomor) ? $auto_nomor : '');
            ?>
            <div class="col-md-4 form-group">
                <label>No Transaksi</label>
                <input type="text" name="nomor_transaksi" class="form-control"
                    value="<?php echo set_value('nomor_transaksi', $nomorTransaksi); ?>" readonly required>
            </div>
            <div class="col-md-4 form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" class="form-control"
                    value="<?php echo set_value('tanggal_transaksi', isset($row->tanggal_transaksi) ? $row->tanggal_transaksi : date('Y-m-d')); ?>"
                    required>
            </div>
            <?php $jenisDana = set_value('jenis_dana', isset($row->jenis_dana) ? $row->jenis_dana : 'infaq'); ?>
            <div class="col-md-4 form-group">
                <label>Jenis Dana</label>
                <select name="jenis_dana" class="form-control" required>
                    <option value="infaq" <?php echo ($jenisDana === 'infaq') ? 'selected' : ''; ?>>Infaq</option>
                    <option value="shodaqoh" <?php echo ($jenisDana === 'shodaqoh') ? 'selected' : ''; ?>>Shodaqoh</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label>Nama Donatur</label>
                <input type="text" name="nama_donatur" class="form-control"
                    value="<?php echo set_value('nama_donatur', isset($row->nama_donatur) ? $row->nama_donatur : ''); ?>"
                    required>
            </div>
            <div class="col-md-6 form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control"
                    value="<?php echo set_value('no_hp', isset($row->no_hp) ? $row->no_hp : ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>Nominal Uang</label>
                <input type="number" step="0.01" min="1" name="nominal_uang" class="form-control"
                    value="<?php echo set_value('nominal_uang', isset($row->nominal_uang) ? $row->nominal_uang : 0); ?>"
                    required>
            </div>
            <?php $metodeBayar = set_value('metode_bayar', isset($row->metode_bayar) ? $row->metode_bayar : 'tunai'); ?>
            <div class="col-md-4 form-group">
                <label>Metode Bayar</label>
                <select name="metode_bayar" class="form-control" required>
                    <option value="tunai" <?php echo ($metodeBayar === 'tunai') ? 'selected' : ''; ?>>Tunai</option>
                    <option value="transfer" <?php echo ($metodeBayar === 'transfer') ? 'selected' : ''; ?>>Transfer</option>
                    <option value="qris" <?php echo ($metodeBayar === 'qris') ? 'selected' : ''; ?>>QRIS</option>
                    <option value="lainnya" <?php echo ($metodeBayar === 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>
            <?php $status = set_value('status', isset($row->status) ? $row->status : 'diterima'); ?>
            <div class="col-md-4 form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="draft" <?php echo ($status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="diterima" <?php echo ($status === 'diterima') ? 'selected' : ''; ?>>Diterima</option>
                    <option value="batal" <?php echo ($status === 'batal') ? 'selected' : ''; ?>>Batal</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3"
                class="form-control"><?php echo set_value('keterangan', isset($row->keterangan) ? $row->keterangan : ''); ?></textarea>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('infaq_shodaqoh'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
