<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$ringkasan = isset($ringkasan) && is_array($ringkasan) ? $ringkasan : array();
$fitrahUang = isset($ringkasan['fitrah_uang']) ? (float) $ringkasan['fitrah_uang'] : 0;
$fitrahBeras = isset($ringkasan['fitrah_beras']) ? (float) $ringkasan['fitrah_beras'] : 0;
$malUang = isset($ringkasan['mal_uang']) ? (float) $ringkasan['mal_uang'] : 0;
$penyaluranUang = isset($ringkasan['penyaluran_uang']) ? (float) $ringkasan['penyaluran_uang'] : 0;
$penyaluranBeras = isset($ringkasan['penyaluran_beras']) ? (float) $ringkasan['penyaluran_beras'] : 0;
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filter Laporan</h3>
    </div>
    <div class="card-body">
        <?php echo form_open('laporan', array('method' => 'get', 'class' => 'form-inline')); ?>
            <div class="form-group mr-3 mb-2">
                <label class="mr-2">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo html_escape($start_date); ?>">
            </div>
            <div class="form-group mr-3 mb-2">
                <label class="mr-2">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo html_escape($end_date); ?>">
            </div>
            <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-search"></i> Tampilkan</button>
            <a href="<?php echo site_url('laporan/export_pdf?start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date)); ?>" target="_blank" class="btn btn-danger mb-2 ml-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        <?php echo form_close(); ?>
        <small class="text-muted d-block mt-2">Periode: <?php echo html_escape(indo_date($start_date)); ?> s.d. <?php echo html_escape(indo_date($end_date)); ?></small>
    </div>
</div>

<div class="row">
    <div class="col-md-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h4>Rp <?php echo number_format($fitrahUang, 0, ',', '.'); ?></h4>
                <p>Total Fitrah (Uang)</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h4><?php echo number_format($fitrahBeras, 2, ',', '.'); ?> Kg</h4>
                <p>Total Fitrah (Beras)</p>
            </div>
            <div class="icon">
                <i class="fas fa-seedling"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h4>Rp <?php echo number_format($malUang, 0, ',', '.'); ?></h4>
                <p>Total Zakat Mal</p>
            </div>
            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h4>Rp <?php echo number_format($penyaluranUang, 0, ',', '.'); ?></h4>
                <p>Total Penyaluran Uang</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-hand-holding-usd mr-2"></i>Laporan Zakat Fitrah</h3></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-sm table-hover mb-0">
            <thead>
                <tr>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Muzakki</th>
                    <th>Jiwa</th>
                    <th>Metode</th>
                    <th>Nominal/Beras</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows_fitrah)): foreach ($rows_fitrah as $row): ?>
                    <tr>
                        <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                        <td><?php echo html_escape(indo_date($row->tanggal_bayar)); ?></td>
                        <td><?php echo html_escape($row->nama_muzakki); ?></td>
                        <td><?php echo (int) $row->jumlah_jiwa; ?></td>
                        <td><?php echo html_escape($row->metode_tunaikan); ?></td>
                        <td>
                            <?php if ($row->metode_tunaikan === 'beras'): ?>
                                <?php echo number_format((float) $row->beras_kg, 2, ',', '.'); ?> Kg
                            <?php else: ?>
                                Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo html_escape($row->status); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Tidak ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i>Laporan Zakat Mal</h3></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-sm table-hover mb-0">
            <thead>
                <tr>
                    <th>No Transaksi</th>
                    <th>Tanggal Hitung</th>
                    <th>Muzakki</th>
                    <th>Harta Bersih</th>
                    <th>Nishab</th>
                    <th>Total Zakat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows_mal)): foreach ($rows_mal as $row): ?>
                    <tr>
                        <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                        <td><?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
                        <td><?php echo html_escape($row->nama_muzakki); ?></td>
                        <td>Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></td>
                        <td><?php echo html_escape($row->status); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Tidak ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-donate mr-2"></i>Laporan Penyaluran</h3></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-sm table-hover mb-0">
            <thead>
                <tr>
                    <th>No Penyaluran</th>
                    <th>Tanggal</th>
                    <th>Sumber</th>
                    <th>Total Uang</th>
                    <th>Total Beras</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows_penyaluran)): foreach ($rows_penyaluran as $row): ?>
                    <tr>
                        <td><?php echo html_escape($row->nomor_penyaluran); ?></td>
                        <td><?php echo html_escape(indo_date($row->tanggal_penyaluran)); ?></td>
                        <td><?php echo html_escape($row->jenis_sumber); ?></td>
                        <td>Rp <?php echo number_format((float) $row->total_uang, 0, ',', '.'); ?></td>
                        <td><?php echo number_format((float) $row->total_beras_kg, 2, ',', '.'); ?> Kg</td>
                        <td><?php echo html_escape($row->status); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-light border">
    <strong>Info:</strong> Total penyaluran beras periode ini: <strong><?php echo number_format($penyaluranBeras, 2, ',', '.'); ?> Kg</strong>
</div>
