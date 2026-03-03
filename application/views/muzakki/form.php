<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'muzakki/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Muzakki</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-3 form-group">
                <label>Kode Muzakki</label>
                <?php
                $kodeMuzakki = isset($row->kode_muzakki)
                    ? $row->kode_muzakki
                    : (isset($auto_kode) ? $auto_kode : '');
                ?>
                <input type="text" name="kode_muzakki" class="form-control"
                    value="<?php echo set_value('kode_muzakki', $kodeMuzakki); ?>" readonly required>
            </div>
            <div class="col-md-3 form-group">
                <label>Jenis Muzakki</label>
                <?php $jenis = set_value('jenis_muzakki', isset($row->jenis_muzakki) ? $row->jenis_muzakki : 'individu'); ?>
                <select name="jenis_muzakki" class="form-control" required>
                    <option value="individu" <?php echo ($jenis === 'individu') ? 'selected' : ''; ?>>Individu</option>
                    <option value="lembaga" <?php echo ($jenis === 'lembaga') ? 'selected' : ''; ?>>Lembaga</option>
                    <option value="kepala_keluarga" <?php echo ($jenis === 'kepala_keluarga') ? 'selected' : ''; ?>>Kepala
                        Keluarga</option>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control"
                    value="<?php echo set_value('nama', isset($row->nama) ? $row->nama : ''); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group"><label>NIK</label><input type="text" name="nik" class="form-control"
                    value="<?php echo set_value('nik', isset($row->nik) ? $row->nik : ''); ?>"></div>
            <div class="col-md-6 form-group"><label>NPWP</label><input type="text" name="npwp" class="form-control"
                    value="<?php echo set_value('npwp', isset($row->npwp) ? $row->npwp : ''); ?>"></div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group"><label>Pekerjaan</label><input type="text" name="pekerjaan"
                    class="form-control"
                    value="<?php echo set_value('pekerjaan', isset($row->pekerjaan) ? $row->pekerjaan : ''); ?>"></div>
            <div class="col-md-4 form-group"><label>No HP</label><input type="text" name="no_hp" class="form-control"
                    value="<?php echo set_value('no_hp', isset($row->no_hp) ? $row->no_hp : ''); ?>"></div>
            <div class="col-md-4 form-group"><label>Email</label><input type="email" name="email" class="form-control"
                    value="<?php echo set_value('email', isset($row->email) ? $row->email : ''); ?>"></div>
        </div>

        <div class="form-group mb-3"><label>Alamat</label><textarea name="alamat" class="form-control"
                rows="2"><?php echo set_value('alamat', isset($row->alamat) ? $row->alamat : ''); ?></textarea></div>
        <div class="row">
            <div class="col-md-4 form-group"><label>Kelurahan</label><input type="text" name="kelurahan"
                    class="form-control"
                    value="<?php echo set_value('kelurahan', isset($row->kelurahan) ? $row->kelurahan : ''); ?>"></div>
            <div class="col-md-4 form-group"><label>Kecamatan</label><input type="text" name="kecamatan"
                    class="form-control"
                    value="<?php echo set_value('kecamatan', isset($row->kecamatan) ? $row->kecamatan : ''); ?>"></div>
            <div class="col-md-4 form-group"><label>Kota/Kabupaten</label><input type="text" name="kota_kabupaten"
                    class="form-control"
                    value="<?php echo set_value('kota_kabupaten', isset($row->kota_kabupaten) ? $row->kota_kabupaten : ''); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 form-group"><label>Provinsi</label><input type="text" name="provinsi"
                    class="form-control"
                    value="<?php echo set_value('provinsi', isset($row->provinsi) ? $row->provinsi : ''); ?>"></div>
            <div class="col-md-4 form-group"><label>Kode Pos</label><input type="text" name="kode_pos"
                    class="form-control"
                    value="<?php echo set_value('kode_pos', isset($row->kode_pos) ? $row->kode_pos : ''); ?>"></div>
        </div>

        <?php
        $anggotaRows = isset($anggota_rows) && is_array($anggota_rows) ? $anggota_rows : array();
        if (empty($anggotaRows)) {
            $anggotaRows = array((object) array('nama_anggota' => '', 'hubungan_keluarga' => '', 'aktif_dihitung' => 1));
        }
        ?>
        <div id="keluarga-section" class="border rounded p-3 mt-2" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Anggota Keluarga (Tanggungan Fitrah)</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-anggota">Tambah Baris</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="anggota-table">
                    <thead>
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Hubungan Keluarga</th>
                            <th>Aktif Dihitung</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anggotaRows as $a): ?>
                            <tr>
                                <td><input type="text" name="anggota_nama[]" class="form-control"
                                        value="<?php echo html_escape(isset($a->nama_anggota) ? $a->nama_anggota : ''); ?>">
                                </td>
                                <td><input type="text" name="anggota_hubungan[]" class="form-control"
                                        value="<?php echo html_escape(isset($a->hubungan_keluarga) ? $a->hubungan_keluarga : ''); ?>">
                                </td>
                                <td>
                                    <?php $aktifA = (int) (isset($a->aktif_dihitung) ? $a->aktif_dihitung : 1); ?>
                                    <select name="anggota_aktif[]" class="form-control">
                                        <option value="1" <?php echo ($aktifA === 1) ? 'selected' : ''; ?>>Ya</option>
                                        <option value="0" <?php echo ($aktifA === 0) ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-danger btn-remove-anggota">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('muzakki'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    (function () {
        const jenisEl = document.querySelector('select[name="jenis_muzakki"]');
        const keluargaSection = document.getElementById('keluarga-section');
        const tableBody = document.querySelector('#anggota-table tbody');
        const addBtn = document.getElementById('btn-add-anggota');

        function toggleKeluarga() {
            if (!jenisEl || !keluargaSection) return;
            keluargaSection.style.display = (jenisEl.value === 'kepala_keluarga') ? 'block' : 'none';
        }

        function addRow() {
            const tr = document.createElement('tr');
            tr.innerHTML = '' +
                '<td><input type="text" name="anggota_nama[]" class="form-control"></td>' +
                '<td><input type="text" name="anggota_hubungan[]" class="form-control"></td>' +
                '<td><select name="anggota_aktif[]" class="form-control"><option value="1" selected>Ya</option><option value="0">Tidak</option></select></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger btn-remove-anggota">Hapus</button></td>';
            tableBody.appendChild(tr);
        }

        if (jenisEl) {
            jenisEl.addEventListener('change', toggleKeluarga);
            toggleKeluarga();
        }

        if (addBtn) {
            addBtn.addEventListener('click', addRow);
        }

        if (tableBody) {
            tableBody.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-anggota')) {
                    const rows = tableBody.querySelectorAll('tr');
                    if (rows.length <= 1) {
                        const inputs = rows[0].querySelectorAll('input');
                        inputs.forEach(function (input) { input.value = ''; });
                        const select = rows[0].querySelector('select[name="anggota_aktif[]"]');
                        if (select) select.value = '1';
                        return;
                    }
                    e.target.closest('tr').remove();
                }
            });
        }
    })();
</script>