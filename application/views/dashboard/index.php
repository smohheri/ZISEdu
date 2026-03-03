<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$summary = isset($summary) && is_array($summary) ? $summary : array();
$monthlyChart = isset($monthly_chart) && is_array($monthly_chart) ? $monthly_chart : array();

$totalMuzakki = isset($summary['total_muzakki']) ? (int) $summary['total_muzakki'] : 0;
$totalMustahik = isset($summary['total_mustahik']) ? (int) $summary['total_mustahik'] : 0;
$totalMasukUang = isset($summary['total_masuk_uang']) ? (float) $summary['total_masuk_uang'] : 0;
$totalSaldoUang = isset($summary['total_saldo_uang']) ? (float) $summary['total_saldo_uang'] : 0;
$totalFitrahUang = isset($summary['total_fitrah_uang']) ? (float) $summary['total_fitrah_uang'] : 0;
$totalMalUang = isset($summary['total_mal_uang']) ? (float) $summary['total_mal_uang'] : 0;
$totalInfaqShodaqoh = isset($summary['total_infaq_shodaqoh_uang']) ? (float) $summary['total_infaq_shodaqoh_uang'] : 0;
$totalFitrahBeras = isset($summary['total_fitrah_beras']) ? (float) $summary['total_fitrah_beras'] : 0;
$totalPenyaluranUang = isset($summary['total_penyaluran_uang']) ? (float) $summary['total_penyaluran_uang'] : 0;
$totalPenyaluranBeras = isset($summary['total_penyaluran_beras']) ? (float) $summary['total_penyaluran_beras'] : 0;
$totalInfaq = isset($summary['total_infaq_uang']) ? (float) $summary['total_infaq_uang'] : 0;
$totalShodaqoh = isset($summary['total_shodaqoh_uang']) ? (float) $summary['total_shodaqoh_uang'] : 0;

$chartLabels = isset($monthlyChart['labels']) ? $monthlyChart['labels'] : array();
$chartMasukZakat = isset($monthlyChart['masuk_zakat']) ? $monthlyChart['masuk_zakat'] : array();
$chartMasukInfaq = isset($monthlyChart['masuk_infaq_shodaqoh']) ? $monthlyChart['masuk_infaq_shodaqoh'] : array();
$chartKeluar = isset($monthlyChart['keluar_penyaluran']) ? $monthlyChart['keluar_penyaluran'] : array();
$chartSaldo = isset($monthlyChart['saldo_arus_kas']) ? $monthlyChart['saldo_arus_kas'] : array();

$chartFitrah = isset($monthlyChart['fitrah_uang']) ? $monthlyChart['fitrah_uang'] : array();
$chartMal = isset($monthlyChart['mal_uang']) ? $monthlyChart['mal_uang'] : array();
$chartInfaq = isset($monthlyChart['infaq_uang']) ? $monthlyChart['infaq_uang'] : array();
?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>Rp <?php echo number_format($totalMasukUang, 0, ',', '.'); ?></h3>
                <p>Total Pemasukan Uang</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>Rp <?php echo number_format($totalSaldoUang, 0, ',', '.'); ?></h3>
                <p>Saldo Uang (Masuk - Salur)</p>
            </div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3><?php echo number_format($totalMuzakki, 0, ',', '.'); ?></h3>
                <p>Total Muzakki</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3><?php echo number_format($totalMustahik, 0, ',', '.'); ?></h3>
                <p>Total Mustahik</p>
            </div>
            <div class="icon"><i class="fas fa-hands-helping"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="info-box bg-light">
            <span class="info-box-icon bg-primary"><i class="fas fa-donate"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Zakat Fitrah (Uang)</span>
                <span class="info-box-number">Rp <?php echo number_format($totalFitrahUang, 0, ',', '.'); ?></span>
                <span class="text-muted">Beras: <?php echo number_format($totalFitrahBeras, 2, ',', '.'); ?> Kg</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-light">
            <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Zakat Mal (Uang)</span>
                <span class="info-box-number">Rp <?php echo number_format($totalMalUang, 0, ',', '.'); ?></span>
                <span class="text-muted">Status lunas</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-light">
            <span class="info-box-icon bg-info"><i class="fas fa-hand-holding-usd"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Infaq & Shodaqoh</span>
                <span class="info-box-number">Rp <?php echo number_format($totalInfaqShodaqoh, 0, ',', '.'); ?></span>
                <span class="text-muted">Infaq: Rp <?php echo number_format($totalInfaq, 0, ',', '.'); ?> | Shodaqoh: Rp <?php echo number_format($totalShodaqoh, 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i>Arus Dana 6 Bulan Terakhir</h3>
            </div>
            <div class="card-body">
                <canvas id="arusDanaChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i>Komposisi Pemasukan 6 Bulan</h3>
            </div>
            <div class="card-body">
                <canvas id="komposisiMasukChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-share-square mr-1"></i>Ringkasan Penyaluran</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <th>Total Penyaluran Uang</th>
                        <td class="text-right">Rp <?php echo number_format($totalPenyaluranUang, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Total Penyaluran Beras</th>
                        <td class="text-right"><?php echo number_format($totalPenyaluranBeras, 2, ',', '.'); ?> Kg</td>
                    </tr>
                    <tr>
                        <th>Saldo Uang Tersisa</th>
                        <td class="text-right font-weight-bold">Rp <?php echo number_format($totalSaldoUang, 0, ',', '.'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-donate mr-1"></i>Zakat Fitrah Terbaru</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No Transaksi</th>
                            <th>Muzakki</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_fitrah)): ?>
                            <?php foreach ($recent_fitrah as $rf): ?>
                                <tr>
                                    <td><?php echo html_escape($rf->nomor_transaksi); ?></td>
                                    <td><?php echo html_escape($rf->nama_muzakki); ?></td>
                                    <td><?php echo html_escape($rf->metode_tunaikan); ?></td>
                                    <td>
                                        <?php if ($rf->metode_tunaikan === 'beras'): ?>
                                            <?php echo number_format((float) $rf->beras_kg, 2, ',', '.'); ?> Kg
                                        <?php else: ?>
                                            Rp <?php echo number_format((float) $rf->nominal_uang, 0, ',', '.'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada transaksi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-hand-holding-usd mr-1"></i>Infaq & Shodaqoh Terbaru</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No Transaksi</th>
                            <th>Jenis</th>
                            <th>Donatur</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_infaq_shodaqoh)): ?>
                            <?php foreach ($recent_infaq_shodaqoh as $ri): ?>
                                <tr>
                                    <td><?php echo html_escape($ri->nomor_transaksi); ?></td>
                                    <td><?php echo ucfirst(html_escape($ri->jenis_dana)); ?></td>
                                    <td><?php echo html_escape($ri->nama_donatur); ?></td>
                                    <td>Rp <?php echo number_format((float) $ri->nominal_uang, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada transaksi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-truck-loading mr-1"></i>Penyaluran Terbaru</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No Penyaluran</th>
                            <th>Tanggal</th>
                            <th>Sumber</th>
                            <th>Total Uang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_penyaluran)): ?>
                            <?php foreach ($recent_penyaluran as $rp): ?>
                                <tr>
                                    <td><?php echo html_escape($rp->nomor_penyaluran); ?></td>
                                    <td><?php echo html_escape(indo_date($rp->tanggal_penyaluran)); ?></td>
                                    <td><?php echo html_escape($rp->jenis_sumber); ?></td>
                                    <td>Rp <?php echo number_format((float) $rp->total_uang, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada data penyaluran.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('adminlte/plugins/chart.js/Chart.min.js'); ?>"></script>
<script>
    (function () {
        const labels = <?php echo json_encode($chartLabels); ?>;
        const masukZakat = <?php echo json_encode($chartMasukZakat); ?>;
        const masukInfaq = <?php echo json_encode($chartMasukInfaq); ?>;
        const keluarPenyaluran = <?php echo json_encode($chartKeluar); ?>;
        const saldoKas = <?php echo json_encode($chartSaldo); ?>;

        const fitrah = <?php echo json_encode($chartFitrah); ?>;
        const mal = <?php echo json_encode($chartMal); ?>;
        const infaq = <?php echo json_encode($chartInfaq); ?>;

        const arusCanvas = document.getElementById('arusDanaChart');
        if (arusCanvas && typeof Chart !== 'undefined') {
            new Chart(arusCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Masuk Zakat (Rp)',
                            data: masukZakat,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.08)',
                            fill: true,
                            tension: 0.25
                        },
                        {
                            label: 'Masuk Infaq/Shodaqoh (Rp)',
                            data: masukInfaq,
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23, 162, 184, 0.08)',
                            fill: true,
                            tension: 0.25
                        },
                        {
                            label: 'Keluar Penyaluran (Rp)',
                            data: keluarPenyaluran,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.08)',
                            fill: true,
                            tension: 0.25
                        },
                        {
                            label: 'Arus Kas Bersih (Rp)',
                            data: saldoKas,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.08)',
                            fill: true,
                            tension: 0.25
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function (value) {
                                    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                                }
                            }
                        }]
                    }
                }
            });
        }

        const komposisiCanvas = document.getElementById('komposisiMasukChart');
        if (komposisiCanvas && typeof Chart !== 'undefined') {
            new Chart(komposisiCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Fitrah (Rp)',
                            data: fitrah,
                            backgroundColor: 'rgba(0, 123, 255, 0.6)'
                        },
                        {
                            label: 'Mal (Rp)',
                            data: mal,
                            backgroundColor: 'rgba(40, 167, 69, 0.6)'
                        },
                        {
                            label: 'Infaq/Shodaqoh (Rp)',
                            data: infaq,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function (value) {
                                    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                                }
                            }
                        }]
                    }
                }
            });
        }
    })();
</script>
