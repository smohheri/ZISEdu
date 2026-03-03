<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'zakat_fitrah/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Transaksi Zakat Fitrah</h3>
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
            <div class="col-md-4 form-group"><label>No Transaksi</label><input type="text" name="nomor_transaksi"
                    class="form-control" value="<?php echo set_value('nomor_transaksi', $nomorTransaksi); ?>" readonly
                    required></div>
            <div class="col-md-4 form-group"><label>Tanggal Bayar</label><input type="date" name="tanggal_bayar"
                    class="form-control"
                    value="<?php echo set_value('tanggal_bayar', isset($row->tanggal_bayar) ? $row->tanggal_bayar : date('Y-m-d')); ?>"
                    required></div>
            <div class="col-md-4 form-group"><label>Tahun Masehi</label><input type="number" name="tahun_masehi"
                    class="form-control"
                    value="<?php echo set_value('tahun_masehi', isset($row->tahun_masehi) ? $row->tahun_masehi : date('Y')); ?>"
                    required></div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label>Muzakki</label>
                <?php $selectedMuzakki = (string) set_value('muzakki_id', isset($row->muzakki_id) ? $row->muzakki_id : ''); ?>
                <select name="muzakki_id" id="muzakki_id" class="form-control" required>
                    <option value="">-- Pilih Muzakki --</option>
                    <?php if (!empty($muzakki_options)):
                        foreach ($muzakki_options as $id => $nama): ?>
                            <option value="<?php echo $id; ?>" <?php echo ((string) $id === $selectedMuzakki) ? 'selected' : ''; ?>><?php echo html_escape($nama); ?></option>
                        <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-3 form-group"><label>Jumlah Jiwa</label><input type="number" min="1" name="jumlah_jiwa"
                    id="jumlah_jiwa" class="form-control"
                    value="<?php echo set_value('jumlah_jiwa', isset($row->jumlah_jiwa) ? $row->jumlah_jiwa : 1); ?>"
                    required></div>
            <div class="col-md-3 form-group"><label>Tahun Hijriah</label><input type="text" name="tahun_hijriah"
                    class="form-control"
                    value="<?php echo set_value('tahun_hijriah', isset($row->tahun_hijriah) ? $row->tahun_hijriah : ''); ?>">
            </div>
        </div>

        <div class="row">
            <?php $metode = set_value('metode_tunaikan', isset($row->metode_tunaikan) ? $row->metode_tunaikan : 'uang'); ?>
            <div class="col-md-4 form-group"><label>Metode Tunaikan</label><select class="form-control"
                    name="metode_tunaikan">
                    <option value="uang" <?php echo ($metode === 'uang') ? 'selected' : ''; ?>>Uang</option>
                    <option value="beras" <?php echo ($metode === 'beras') ? 'selected' : ''; ?>>Beras</option>
                </select></div>
            <div class="col-md-4 form-group"><label>Beras (Kg)</label><input type="number" step="0.01" name="beras_kg"
                    class="form-control"
                    value="<?php echo set_value('beras_kg', isset($row->beras_kg) ? $row->beras_kg : 0); ?>"></div>
            <div class="col-md-4 form-group"><label>Nominal Uang</label><input type="number" step="0.01"
                    name="nominal_uang" class="form-control"
                    value="<?php echo set_value('nominal_uang', isset($row->nominal_uang) ? $row->nominal_uang : 0); ?>">
            </div>
        </div>

        <div class="row">
            <?php $metodeBayar = set_value('metode_bayar', isset($row->metode_bayar) ? $row->metode_bayar : 'tunai'); ?>
            <div class="col-md-6 form-group"><label>Metode Bayar</label><select name="metode_bayar"
                    class="form-control">
                    <option value="tunai" <?php echo ($metodeBayar === 'tunai') ? 'selected' : ''; ?>>Tunai</option>
                    <option value="transfer" <?php echo ($metodeBayar === 'transfer') ? 'selected' : ''; ?>>Transfer
                    </option>
                    <option value="qris" <?php echo ($metodeBayar === 'qris') ? 'selected' : ''; ?>>QRIS</option>
                    <option value="lainnya" <?php echo ($metodeBayar === 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                </select></div>
            <?php $status = set_value('status', isset($row->status) ? $row->status : 'lunas'); ?>
            <div class="col-md-6 form-group"><label>Status</label><select name="status" class="form-control">
                    <option value="draft" <?php echo ($status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="lunas" <?php echo ($status === 'lunas') ? 'selected' : ''; ?>>Lunas</option>
                    <option value="batal" <?php echo ($status === 'batal') ? 'selected' : ''; ?>>Batal</option>
                </select></div>
        </div>

        <div class="form-group"><label>Keterangan</label><textarea name="keterangan" rows="2"
                class="form-control"><?php echo set_value('keterangan', isset($row->keterangan) ? $row->keterangan : ''); ?></textarea>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('zakat_fitrah'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    (function () {
        const muzakkiEl = document.getElementById('muzakki_id');
        const jumlahJiwaEl = document.getElementById('jumlah_jiwa');

        if (!muzakkiEl || !jumlahJiwaEl) {
            return;
        }

        const baseInfoUrl = '<?php echo site_url('zakat_fitrah/muzakki_info'); ?>';

        function setManualMode() {
            jumlahJiwaEl.readOnly = false;
            if (!jumlahJiwaEl.value || Number(jumlahJiwaEl.value) < 1) {
                jumlahJiwaEl.value = 1;
            }
        }

        function loadMuzakkiInfo() {
            const muzakkiId = muzakkiEl.value;
            if (!muzakkiId) {
                setManualMode();
                return;
            }

            fetch(baseInfoUrl + '/' + encodeURIComponent(muzakkiId), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data muzakki');
                    }
                    return response.json();
                })
                .then(function (data) {
                    if (data.jenis_muzakki && data.jenis_muzakki !== 'individu') {
                        jumlahJiwaEl.value = parseInt(data.jumlah_jiwa_otomatis || 1, 10);
                        if (Number(jumlahJiwaEl.value) < 1) {
                            jumlahJiwaEl.value = 1;
                        }
                        jumlahJiwaEl.readOnly = true;
                    } else {
                        setManualMode();
                    }
                })
                .catch(function () {
                    setManualMode();
                });
        }

        muzakkiEl.addEventListener('change', loadMuzakkiInfo);
        loadMuzakkiInfo();
    })();
</script>
