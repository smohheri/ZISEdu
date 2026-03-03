<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'zakat_mal/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Transaksi Zakat Mal</h3>
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
            <div class="col-md-4 form-group"><label>Tanggal Hitung</label><input type="date" name="tanggal_hitung"
                    class="form-control" value="<?php
                    $tanggalHitung = set_value('tanggal_hitung', isset($row->tanggal_hitung) ? $row->tanggal_hitung : date('Y-m-d'));
                    echo html_escape(trim((string) $tanggalHitung) !== '' ? $tanggalHitung : date('Y-m-d'));
                    ?>" required></div>
            <div class="col-md-4 form-group"><label>Tanggal Bayar</label><input type="date" name="tanggal_bayar"
                    class="form-control" value="<?php
                    $tanggalBayar = set_value('tanggal_bayar', isset($row->tanggal_bayar) ? $row->tanggal_bayar : date('Y-m-d'));
                    echo html_escape(trim((string) $tanggalBayar) !== '' ? $tanggalBayar : date('Y-m-d'));
                    ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label>Muzakki</label>
                <?php $selectedMuzakki = (string) set_value('muzakki_id', isset($row->muzakki_id) ? $row->muzakki_id : ''); ?>
                <select name="muzakki_id" class="form-control" required>
                    <option value="">-- Pilih Muzakki --</option>
                    <?php if (!empty($muzakki_options)):
                        foreach ($muzakki_options as $id => $nama): ?>
                            <option value="<?php echo $id; ?>" <?php echo ((string) $id === $selectedMuzakki) ? 'selected' : ''; ?>><?php echo html_escape($nama); ?></option>
                        <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-3 form-group"><label>Tahun Masehi</label><input type="number" name="tahun_masehi"
                    class="form-control"
                    value="<?php echo set_value('tahun_masehi', isset($row->tahun_masehi) ? $row->tahun_masehi : date('Y')); ?>"
                    required></div>
            <?php $status = set_value('status', isset($row->status) ? $row->status : 'lunas'); ?>
            <div class="col-md-3 form-group"><label>Status</label><select name="status" class="form-control">
                    <option value="draft" <?php echo ($status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="lunas" <?php echo ($status === 'lunas') ? 'selected' : ''; ?>>Lunas</option>
                    <option value="batal" <?php echo ($status === 'batal') ? 'selected' : ''; ?>>Batal</option>
                </select></div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group"><label>Total Harta</label><input type="number" step="0.01"
                    name="total_harta" id="total_harta" class="form-control"
                    value="<?php echo set_value('total_harta', isset($row->total_harta) ? $row->total_harta : 0); ?>"
                    required></div>
            <div class="col-md-4 form-group"><label>Total Hutang Jatuh Tempo</label><input type="number" step="0.01"
                    name="total_hutang_jatuh_tempo" id="total_hutang_jatuh_tempo" class="form-control"
                    value="<?php echo set_value('total_hutang_jatuh_tempo', isset($row->total_hutang_jatuh_tempo) ? $row->total_hutang_jatuh_tempo : 0); ?>">
            </div>
            <div class="col-md-4 form-group"><label>Harta Bersih</label><input type="number" step="0.01"
                    name="harta_bersih" id="harta_bersih" class="form-control"
                    value="<?php echo set_value('harta_bersih', isset($row->harta_bersih) ? $row->harta_bersih : 0); ?>"
                    readonly required></div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group"><label>Nilai Nishab</label><input type="number" step="0.01"
                    name="nilai_nishab" id="nilai_nishab" class="form-control"
                    value="<?php echo set_value('nilai_nishab', isset($row->nilai_nishab) ? $row->nilai_nishab : 0); ?>"
                    required></div>
            <div class="col-md-4 form-group"><label>Persentase Zakat (%)</label><input type="number" step="0.01"
                    name="persentase_zakat" id="persentase_zakat" class="form-control"
                    value="<?php echo set_value('persentase_zakat', isset($row->persentase_zakat) ? $row->persentase_zakat : 2.5); ?>"
                    required></div>
            <div class="col-md-4 form-group"><label>Total Zakat</label><input type="number" step="0.01"
                    name="total_zakat" id="total_zakat" class="form-control"
                    value="<?php echo set_value('total_zakat', isset($row->total_zakat) ? $row->total_zakat : 0); ?>"
                    readonly required></div>
        </div>

        <?php $metodeBayar = set_value('metode_bayar', isset($row->metode_bayar) ? $row->metode_bayar : 'tunai'); ?>
        <div class="form-group"><label>Metode Bayar</label><select name="metode_bayar" class="form-control">
                <option value="tunai" <?php echo ($metodeBayar === 'tunai') ? 'selected' : ''; ?>>Tunai</option>
                <option value="transfer" <?php echo ($metodeBayar === 'transfer') ? 'selected' : ''; ?>>Transfer</option>
                <option value="qris" <?php echo ($metodeBayar === 'qris') ? 'selected' : ''; ?>>QRIS</option>
                <option value="lainnya" <?php echo ($metodeBayar === 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
            </select></div>

        <div class="form-group"><label>Keterangan</label><textarea name="keterangan" rows="2"
                class="form-control"><?php echo set_value('keterangan', isset($row->keterangan) ? $row->keterangan : ''); ?></textarea>
        </div>

        <hr>
        <h5>Detail Aset (zakat_mal_detail)</h5>
        <p class="text-muted mb-2">Bagian ini disiapkan untuk input detail aset per jenis harta.</p>
        <?php
        $detailRows = isset($detail_rows) && is_array($detail_rows) ? $detail_rows : array();
        if (empty($detailRows)) {
            $detailRows = array(
                (object) array(
                    'jenis_harta_id' => '',
                    'nilai_harta' => '',
                    'nilai_haul_bulan' => '',
                    'keterangan' => ''
                )
            );
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Jenis Harta</th>
                        <th>Nilai Harta</th>
                        <th>Haul (bulan)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detailRows as $idx => $d): ?>
                        <tr>
                            <td>
                                <?php $selectedJenis = set_value('detail[' . $idx . '][jenis_harta_id]', isset($d->jenis_harta_id) ? $d->jenis_harta_id : ''); ?>
                                <select class="form-control" name="detail[<?php echo $idx; ?>][jenis_harta_id]">
                                    <option value="">-- Pilih Jenis --</option>
                                    <?php if (!empty($jenis_harta_options)):
                                        foreach ($jenis_harta_options as $id => $nama): ?>
                                            <option value="<?php echo $id; ?>" <?php echo ((string) $id === (string) $selectedJenis) ? 'selected' : ''; ?>><?php echo html_escape($nama); ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="detail[<?php echo $idx; ?>][nilai_harta]"
                                    class="form-control"
                                    value="<?php echo set_value('detail[' . $idx . '][nilai_harta]', isset($d->nilai_harta) ? $d->nilai_harta : ''); ?>">
                            </td>
                            <td><input type="number" name="detail[<?php echo $idx; ?>][nilai_haul_bulan]"
                                    class="form-control"
                                    value="<?php echo set_value('detail[' . $idx . '][nilai_haul_bulan]', isset($d->nilai_haul_bulan) ? $d->nilai_haul_bulan : ''); ?>">
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
        <a href="<?php echo site_url('zakat_mal'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    (function () {
        const totalHartaEl = document.getElementById('total_harta');
        const hutangEl = document.getElementById('total_hutang_jatuh_tempo');
        const hartaBersihEl = document.getElementById('harta_bersih');
        const nishabEl = document.getElementById('nilai_nishab');
        const persentaseEl = document.getElementById('persentase_zakat');
        const totalZakatEl = document.getElementById('total_zakat');

        function toNum(el) {
            const v = parseFloat(el && el.value ? el.value : '0');
            return isNaN(v) ? 0 : v;
        }

        function recalc() {
            const totalHarta = Math.max(0, toNum(totalHartaEl));
            const hutang = Math.max(0, toNum(hutangEl));
            const nishab = Math.max(0, toNum(nishabEl));
            const persentase = Math.max(0, toNum(persentaseEl));

            const hartaBersih = Math.max(0, totalHarta - hutang);
            const totalZakat = hartaBersih >= nishab ? (hartaBersih * persentase / 100) : 0;

            if (hartaBersihEl) {
                hartaBersihEl.value = hartaBersih.toFixed(2);
                hartaBersihEl.dispatchEvent(new Event('input', { bubbles: true }));
            }
            if (totalZakatEl) {
                totalZakatEl.value = totalZakat.toFixed(2);
                totalZakatEl.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        [totalHartaEl, hutangEl, nishabEl, persentaseEl].forEach(function (el) {
            if (el) el.addEventListener('input', recalc);
        });

        recalc();
    })();
</script>