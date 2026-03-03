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
                    name="tanggal_penyaluran" class="form-control" value="<?php
                    $tanggalPenyaluran = set_value('tanggal_penyaluran', isset($row->tanggal_penyaluran) ? $row->tanggal_penyaluran : date('Y-m-d'));
                    echo html_escape(trim((string) $tanggalPenyaluran) !== '' ? $tanggalPenyaluran : date('Y-m-d'));
                    ?>" required></div>
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
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Detail Penerima (penyaluran_detail)</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-detail">Tambah Penerima</button>
        </div>
        <div class="mb-2">
            <span class="badge badge-info">Sisa Uang: <span id="sisa-uang">Rp 0</span></span>
            <span class="badge badge-success">Sisa Beras: <span id="sisa-beras">0,00 Kg</span></span>
        </div>
        <?php
        $detailRows = isset($detail_rows) && is_array($detail_rows) ? $detail_rows : array();
        $mustahikOptionsHtml = '';
        if (!empty($mustahik_options)) {
            foreach ($mustahik_options as $id => $nama) {
                $mustahikOptionsHtml .= '<option value="' . (int) $id . '">' . html_escape($nama) . '</option>';
            }
        }
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
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody id="detail-tbody">
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
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger btn-remove-detail">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
        <a href="<?php echo site_url('penyaluran'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    (function () {
        const totalUangEl = document.querySelector('input[name="total_uang"]');
        const totalBerasEl = document.querySelector('input[name="total_beras_kg"]');
        const detailTbody = document.getElementById('detail-tbody');
        const addBtn = document.getElementById('btn-add-detail');
        const sisaUangEl = document.getElementById('sisa-uang');
        const sisaBerasEl = document.getElementById('sisa-beras');
        const formEl = document.querySelector('form');
        const simpanBtn = document.getElementById('btn-simpan');
        let nextIndex = <?php echo (int) count($detailRows); ?>;

        function parseNum(value) {
            const num = parseFloat(value);
            return isNaN(num) ? 0 : num;
        }

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID', { maximumFractionDigits: 2 });
        }

        function formatKg(number) {
            return number.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Kg';
        }

        function getUsedTotals() {
            const uangInputs = detailTbody.querySelectorAll('input[name*="[nominal_uang]"]');
            const berasInputs = detailTbody.querySelectorAll('input[name*="[beras_kg]"]');
            let usedUang = 0;
            let usedBeras = 0;

            uangInputs.forEach(function (input) { usedUang += parseNum(input.value); });
            berasInputs.forEach(function (input) { usedBeras += parseNum(input.value); });

            return { uang: usedUang, beras: usedBeras };
        }

        function refreshSisa() {
            const totalUang = parseNum(totalUangEl ? totalUangEl.value : 0);
            const totalBeras = parseNum(totalBerasEl ? totalBerasEl.value : 0);
            const used = getUsedTotals();
            const sisaUang = Math.max(0, totalUang - used.uang);
            const sisaBeras = Math.max(0, totalBeras - used.beras);

            if (sisaUangEl) sisaUangEl.textContent = formatRupiah(sisaUang);
            if (sisaBerasEl) sisaBerasEl.textContent = formatKg(sisaBeras);

            if (addBtn) {
                addBtn.disabled = (sisaUang <= 0 && sisaBeras <= 0);
            }

            if (simpanBtn) {
                const hasRemaining = (sisaUang > 0 || sisaBeras > 0);
                simpanBtn.disabled = hasRemaining;
                simpanBtn.title = hasRemaining
                    ? 'Simpan dinonaktifkan: masih ada sisa alokasi uang/beras.'
                    : '';
            }
        }

        function addRow() {
            const totalUang = parseNum(totalUangEl ? totalUangEl.value : 0);
            const totalBeras = parseNum(totalBerasEl ? totalBerasEl.value : 0);
            const used = getUsedTotals();
            const sisaUang = totalUang - used.uang;
            const sisaBeras = totalBeras - used.beras;

            if (sisaUang <= 0 && sisaBeras <= 0) {
                alert('Tidak bisa tambah penerima. Total uang dan beras sudah habis dialokasikan.');
                return;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = '' +
                '<td><select class="form-control" name="detail[' + nextIndex + '][mustahik_id]"><option value="">-- Pilih Mustahik --</option><?php echo $mustahikOptionsHtml; ?></select></td>' +
                '<td><select class="form-control" name="detail[' + nextIndex + '][bentuk_bantuan]"><option value="uang">Uang</option><option value="beras">Beras</option><option value="paket">Paket</option></select></td>' +
                '<td><input type="number" step="0.01" name="detail[' + nextIndex + '][nominal_uang]" class="form-control" value=""></td>' +
                '<td><input type="number" step="0.01" name="detail[' + nextIndex + '][beras_kg]" class="form-control" value=""></td>' +
                '<td><input type="text" name="detail[' + nextIndex + '][keterangan]" class="form-control" value=""></td>' +
                '<td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-detail">Hapus</button></td>';

            detailTbody.appendChild(tr);
            nextIndex += 1;
            refreshSisa();
        }

        if (addBtn) {
            addBtn.addEventListener('click', addRow);
        }

        if (detailTbody) {
            detailTbody.addEventListener('input', function (e) {
                if (e.target.matches('input[name*="[nominal_uang]"], input[name*="[beras_kg]"]')) {
                    refreshSisa();
                }
            });

            detailTbody.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-detail')) {
                    const row = e.target.closest('tr');
                    const rows = detailTbody.querySelectorAll('tr');
                    if (rows.length <= 1) {
                        row.querySelectorAll('input').forEach(function (input) { input.value = ''; });
                        const selects = row.querySelectorAll('select');
                        if (selects[0]) selects[0].value = '';
                        if (selects[1]) selects[1].value = 'uang';
                        refreshSisa();
                        return;
                    }

                    row.remove();
                    refreshSisa();
                }
            });
        }

        if (totalUangEl) totalUangEl.addEventListener('input', refreshSisa);
        if (totalBerasEl) totalBerasEl.addEventListener('input', refreshSisa);

        if (formEl) {
            formEl.addEventListener('submit', function (e) {
                const totalUang = parseNum(totalUangEl ? totalUangEl.value : 0);
                const totalBeras = parseNum(totalBerasEl ? totalBerasEl.value : 0);
                const used = getUsedTotals();
                const sisaUang = totalUang - used.uang;
                const sisaBeras = totalBeras - used.beras;

                if (used.uang > totalUang || used.beras > totalBeras) {
                    e.preventDefault();
                    alert('Total detail penerima melebihi total uang/beras penyaluran. Silakan sesuaikan dulu.');
                    return;
                }

                if (sisaUang > 0 || sisaBeras > 0) {
                    e.preventDefault();
                    alert('Belum bisa simpan karena masih ada sisa total uang atau beras. Alokasikan sampai habis.');
                }
            });
        }

        refreshSisa();
    })();
</script>