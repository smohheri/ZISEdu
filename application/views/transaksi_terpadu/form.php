<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
?>
<?php echo form_open(isset($form_action) ? $form_action : 'transaksi_terpadu/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Transaksi Terpadu</h3>
    </div>
    <div class="card-body">
        <?php if ($CI->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $CI->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if ($CI->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $CI->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <h5 class="mb-3">Informasi Utama</h5>
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label>Muzakki</label>
                <select name="muzakki_id" id="muzakki_id" class="form-control" required>
                    <option value="">-- Pilih Muzakki --</option>
                    <?php if (!empty($muzakki_options)): foreach ($muzakki_options as $id => $nama): ?>
                        <option value="<?php echo $id; ?>"><?php echo html_escape($nama); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>Tahun Masehi</label>
                <input type="number" name="tahun_masehi" id="tahun_masehi" class="form-control" value="<?php echo date('Y'); ?>" required>
            </div>
        </div>

        <hr>

        <!-- Zakat Fitrah Section -->
        <div class="custom-control custom-switch mb-3">
            <input type="checkbox" class="custom-control-input" id="enable_fitrah" name="enable_fitrah" value="1">
            <label class="custom-control-label font-weight-bold" for="enable_fitrah">Tambahkan Zakat Fitrah</label>
        </div>
        <div id="section_fitrah" style="display: none;" class="p-3 border rounded mb-4 bg-light">
            <!-- Fields for Zakat Fitrah -->
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>No Transaksi Fitrah</label>
                    <input type="text" name="nomor_transaksi_fitrah" class="form-control" value="<?php echo isset($auto_nomor_fitrah) ? $auto_nomor_fitrah : ''; ?>" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label>Tahun Hijriah</label>
                    <input type="text" name="tahun_hijriah" id="tahun_hijriah" class="form-control" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label>Jumlah Jiwa</label>
                    <input type="number" name="fitrah_jumlah_jiwa" id="fitrah_jumlah_jiwa" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-3 form-group">
                    <label>Metode Tunaikan</label>
                    <select name="fitrah_metode_tunaikan" class="form-control">
                        <option value="uang">Uang</option>
                        <option value="beras">Beras</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Metode Bayar Fitrah</label>
                    <select name="fitrah_metode_bayar" class="form-control">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Nominal Uang</label>
                    <input type="number" step="0.01" name="fitrah_nominal_uang" class="form-control" value="0">
                </div>
                <div class="col-md-4 form-group">
                    <label>Beras (Kg)</label>
                    <input type="number" step="0.01" name="fitrah_beras_kg" class="form-control" value="0">
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan Fitrah</label>
                <textarea name="fitrah_keterangan" rows="1" class="form-control"></textarea>
            </div>
        </div>

        <!-- Zakat Mal Section -->
        <div class="custom-control custom-switch mb-3">
            <input type="checkbox" class="custom-control-input" id="enable_mal" name="enable_mal" value="1">
            <label class="custom-control-label font-weight-bold" for="enable_mal">Tambahkan Zakat Mal</label>
        </div>
        <div id="section_mal" style="display: none;" class="p-3 border rounded mb-4 bg-light">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>No Transaksi Mal</label>
                    <input type="text" name="nomor_transaksi_mal" class="form-control" value="<?php echo isset($auto_nomor_mal) ? $auto_nomor_mal : ''; ?>" readonly>
                </div>
                <div class="col-md-4 form-group">
                    <label>Metode Bayar Mal</label>
                    <select name="mal_metode_bayar" class="form-control">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Persentase Zakat (%)</label>
                    <input type="number" step="0.01" name="mal_persentase_zakat" id="mal_persentase_zakat" class="form-control" value="2.5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group"><label>Total Harta</label><input type="number" step="0.01" name="mal_total_harta" id="mal_total_harta" class="form-control" value="0"></div>
                <div class="col-md-4 form-group"><label>Hutang Jatuh Tempo</label><input type="number" step="0.01" name="mal_total_hutang" id="mal_total_hutang" class="form-control" value="0"></div>
                <div class="col-md-4 form-group"><label>Harta Bersih</label><input type="number" step="0.01" name="mal_harta_bersih" id="mal_harta_bersih" class="form-control" value="0" readonly></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group"><label>Nilai Nishab</label><input type="number" step="0.01" name="mal_nilai_nishab" id="mal_nilai_nishab" class="form-control" value="0"></div>
                <div class="col-md-6 form-group"><label>Total Zakat Mal</label><input type="number" step="0.01" name="mal_total_zakat" id="mal_total_zakat" class="form-control" value="0"></div>
            </div>
            <div class="form-group">
                <label>Keterangan Mal</label>
                <textarea name="mal_keterangan" rows="1" class="form-control"></textarea>
            </div>
            <h6>Detail Harta Mal (Opsional)</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm bg-white">
                    <thead><tr><th>Jenis Harta</th><th>Nilai Harta</th><th>Haul (bln)</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <?php for($i=0; $i<1; $i++): ?>
                        <tr>
                            <td>
                                <select class="form-control" name="mal_detail[<?php echo $i; ?>][jenis_harta_id]">
                                    <option value="">-- Pilih --</option>
                                    <?php if (!empty($jenis_harta_options)): foreach ($jenis_harta_options as $id => $nama): ?>
                                        <option value="<?php echo $id; ?>"><?php echo html_escape($nama); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="mal_detail[<?php echo $i; ?>][nilai_harta]" class="form-control"></td>
                            <td><input type="number" name="mal_detail[<?php echo $i; ?>][nilai_haul_bulan]" class="form-control"></td>
                            <td><input type="text" name="mal_detail[<?php echo $i; ?>][keterangan]" class="form-control"></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Infaq Shodaqoh Section -->
        <div class="custom-control custom-switch mb-3">
            <input type="checkbox" class="custom-control-input" id="enable_infaq" name="enable_infaq" value="1">
            <label class="custom-control-label font-weight-bold" for="enable_infaq">Tambahkan Infaq / Shodaqoh</label>
        </div>
        <div id="section_infaq" style="display: none;" class="p-3 border rounded bg-light">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>No Transaksi Infaq</label>
                    <input type="text" name="nomor_transaksi_infaq" class="form-control" value="<?php echo isset($auto_nomor_infaq) ? $auto_nomor_infaq : ''; ?>" readonly>
                </div>
                <div class="col-md-4 form-group">
                    <label>Jenis Dana</label>
                    <select name="infaq_jenis_dana" class="form-control">
                        <option value="infaq">Infaq</option>
                        <option value="shodaqoh">Shodaqoh</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Metode Bayar Infaq</label>
                    <select name="infaq_metode_bayar" class="form-control">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Nama Donatur</label>
                    <input type="text" name="infaq_nama_donatur" id="infaq_nama_donatur" class="form-control" placeholder="Otomatis dari Muzakki">
                </div>
                <div class="col-md-4 form-group">
                    <label>No HP</label>
                    <input type="text" name="infaq_no_hp" id="infaq_no_hp" class="form-control">
                </div>
                <div class="col-md-4 form-group">
                    <label>Nominal Uang</label>
                    <input type="number" step="0.01" name="infaq_nominal_uang" class="form-control" value="0">
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan Infaq</label>
                <textarea name="infaq_keterangan" rows="1" class="form-control"></textarea>
            </div>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary" id="btn-submit">Simpan Transaksi Terpadu</button>
    </div>
</div>
<?php echo form_close(); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select2
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
        jQuery('#muzakki_id').select2({ theme: 'bootstrap4' }).on('change', function() {
            loadMuzakkiInfo();
        });
    } else {
        document.getElementById('muzakki_id').addEventListener('change', loadMuzakkiInfo);
    }

    const baseInfoUrl = '<?php echo site_url('zakat_fitrah/muzakki_info'); ?>';
    const fitrahJumlahJiwaEl = document.getElementById('fitrah_jumlah_jiwa');
    const infaqNamaDonaturEl = document.getElementById('infaq_nama_donatur');
    const infaqNoHpEl = document.getElementById('infaq_no_hp');
    const muzakkiSelect = document.getElementById('muzakki_id');

    function loadMuzakkiInfo() {
        const muzakkiId = muzakkiSelect.value;
        if (!muzakkiId) {
            if (fitrahJumlahJiwaEl) { fitrahJumlahJiwaEl.value = 1; fitrahJumlahJiwaEl.readOnly = false; }
            if (infaqNamaDonaturEl) infaqNamaDonaturEl.value = '';
            if (infaqNoHpEl) infaqNoHpEl.value = '';
            return;
        }

        fetch(baseInfoUrl + '/' + encodeURIComponent(muzakkiId), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (fitrahJumlahJiwaEl) {
                if (data.jenis_muzakki && data.jenis_muzakki !== 'individu') {
                    fitrahJumlahJiwaEl.value = parseInt(data.jumlah_jiwa_otomatis || 1, 10);
                    fitrahJumlahJiwaEl.readOnly = true;
                } else {
                    fitrahJumlahJiwaEl.value = 1;
                    fitrahJumlahJiwaEl.readOnly = false;
                }
            }
            if (infaqNamaDonaturEl && data.nama_muzakki) infaqNamaDonaturEl.value = data.nama_muzakki;
            if (infaqNoHpEl && data.no_hp) infaqNoHpEl.value = data.no_hp;
        }).catch(err => {
            if (fitrahJumlahJiwaEl) fitrahJumlahJiwaEl.readOnly = false;
        });
    }

    // Toggle Sections
    const swFitrah = document.getElementById('enable_fitrah');
    const swMal = document.getElementById('enable_mal');
    const swInfaq = document.getElementById('enable_infaq');

    if(swFitrah) swFitrah.addEventListener('change', e => document.getElementById('section_fitrah').style.display = e.target.checked ? 'block' : 'none');
    if(swMal) swMal.addEventListener('change', e => document.getElementById('section_mal').style.display = e.target.checked ? 'block' : 'none');
    if(swInfaq) swInfaq.addEventListener('change', e => document.getElementById('section_infaq').style.display = e.target.checked ? 'block' : 'none');

    // Tahun Hijriah
    const tahunMasehiEl = document.getElementById('tahun_masehi');
    const tahunHijriahEl = document.getElementById('tahun_hijriah');
    if (tahunMasehiEl && tahunHijriahEl) {
        function updateTahunHijriah() {
            const m = parseInt(tahunMasehiEl.value, 10);
            if (!isNaN(m)) {
                tahunHijriahEl.value = Math.floor((m - 622) * 33 / 32) + ' H';
            }
        }
        tahunMasehiEl.addEventListener('input', updateTahunHijriah);
        updateTahunHijriah();
    }

    // Kalkulasi Mal
    const malHartaEl = document.getElementById('mal_total_harta');
    const malHutangEl = document.getElementById('mal_total_hutang');
    const malBersihEl = document.getElementById('mal_harta_bersih');
    const malNishabEl = document.getElementById('mal_nilai_nishab');
    const malPersentaseEl = document.getElementById('mal_persentase_zakat');
    const malZakatEl = document.getElementById('mal_total_zakat');

    function calcMal() {
        if(!malHartaEl || !malHutangEl || !malBersihEl) return;
        const harta = Math.max(0, parseFloat(malHartaEl.value) || 0);
        const hutang = Math.max(0, parseFloat(malHutangEl.value) || 0);
        const bersih = Math.max(0, harta - hutang);
        malBersihEl.value = bersih.toFixed(2);

        const nishab = Math.max(0, parseFloat(malNishabEl.value) || 0);
        const persentase = Math.max(0, parseFloat(malPersentaseEl.value) || 0);
        
        const zakat = (bersih >= nishab) ? (bersih * persentase / 100) : 0;
        malZakatEl.value = zakat.toFixed(2);
    }

    [malHartaEl, malHutangEl, malNishabEl, malPersentaseEl].forEach(el => {
        if (el) el.addEventListener('input', calcMal);
    });
});
</script>
