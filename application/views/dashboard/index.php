<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$summary = isset($summary) && is_array($summary) ? $summary : array();

$totalMuzakki = isset($summary['total_muzakki']) ? (int) $summary['total_muzakki'] : 0;
$totalMustahik = isset($summary['total_mustahik']) ? (int) $summary['total_mustahik'] : 0;
$totalZakatMasuk = isset($summary['total_zakat_masuk']) ? (float) $summary['total_zakat_masuk'] : 0;
$totalFitrahBeras = isset($summary['total_fitrah_beras']) ? (float) $summary['total_fitrah_beras'] : 0;
$totalPenyaluranUang = isset($summary['total_penyaluran_uang']) ? (float) $summary['total_penyaluran_uang'] : 0;
$totalPenyaluranBeras = isset($summary['total_penyaluran_beras']) ? (float) $summary['total_penyaluran_beras'] : 0;

$monthlyChart = isset($monthly_chart) && is_array($monthly_chart) ? $monthly_chart : array();
$chartLabels = isset($monthlyChart['labels']) && is_array($monthlyChart['labels']) ? $monthlyChart['labels'] : array();
$chartMasuk = isset($monthlyChart['masuk']) && is_array($monthlyChart['masuk']) ? $monthlyChart['masuk'] : array();
$chartKeluar = isset($monthlyChart['keluar']) && is_array($monthlyChart['keluar']) ? $monthlyChart['keluar'] : array();
$chartFitrahUang = isset($monthlyChart['fitrah_uang']) && is_array($monthlyChart['fitrah_uang']) ? $monthlyChart['fitrah_uang'] : array();
$chartFitrahBeras = isset($monthlyChart['fitrah_beras']) && is_array($monthlyChart['fitrah_beras']) ? $monthlyChart['fitrah_beras'] : array();
?>

<div class="row">
	<div class="col-lg-3 col-6">
		<div class="small-box bg-info">
			<div class="inner">
				<h3><?php echo number_format($totalMuzakki, 0, ',', '.'); ?></h3>
				<p>Total Muzakki</p>
			</div>
			<div class="icon">
				<i class="fas fa-users"></i>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<div class="small-box bg-primary">
			<div class="inner">
				<h3><?php echo number_format($totalMustahik, 0, ',', '.'); ?></h3>
				<p>Total Mustahik</p>
			</div>
			<div class="icon">
				<i class="fas fa-hands-helping"></i>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<div class="small-box bg-success">
			<div class="inner">
				<h3><?php echo number_format($totalZakatMasuk, 0, ',', '.'); ?></h3>
				<p>Total Zakat Masuk (Rp)</p>
			</div>
			<div class="icon">
				<i class="fas fa-wallet"></i>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<div class="small-box bg-warning">
			<div class="inner">
				<h3><?php echo number_format($totalFitrahBeras, 2, ',', '.'); ?></h3>
				<p>Total Fitrah Beras (Kg)</p>
			</div>
			<div class="icon">
				<i class="fas fa-seedling"></i>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="card card-outline card-success">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-hand-holding-usd mr-1"></i>Ringkasan Penyaluran</h3>
			</div>
			<div class="card-body p-3">
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item d-flex justify-content-between">
						<span><i class="fas fa-money-bill-wave mr-2"></i>Total Penyaluran Uang</span>
						<strong>Rp <?php echo number_format($totalPenyaluranUang, 0, ',', '.'); ?></strong>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span><i class="fas fa-box mr-2"></i>Total Penyaluran Beras</span>
						<strong>
							<?php echo number_format($totalPenyaluranBeras, 2, ',', '.'); ?> Kg
						</strong>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card card-outline card-info">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-donate mr-1"></i>Transaksi Zakat Fitrah Terbaru</h3>
			</div>
			<div class="card-body table-responsive p-0">
				<table class="table table-sm table-hover mb-0">
					<thead>
						<tr>
							<th><i class="fas fa-hashtag"></i> No</th>
							<th><i class="fas fa-user"></i> Muzakki</th>
							<th><i class="fas fa-money-bill"></i> Metode</th>
							<th><i class="fas fa-coins"></i> Nominal</th>
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
							<tr>
								<td colspan="4" class="text-center text-muted">Belum ada transaksi.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card card-outline card-warning">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-chart-line mr-1"></i>Grafik 6 Bulan Terakhir (Uang)</h3>
			</div>
			<div class="card-body">
				<canvas id="zakatMonthlyChart" height="90"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card card-outline card-info">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i>Grafik Zakat Fitrah 6 Bulan Terakhir</h3>
			</div>
			<div class="card-body">
				<canvas id="fitrahMonthlyChart" height="90"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card card-outline card-primary">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-share-square mr-1"></i>Penyaluran Terbaru</h3>
			</div>
			<div class="card-body table-responsive p-0">
				<table class="table table-sm table-hover mb-0">
					<thead>
						<tr>
							<th><i class="fas fa-file-alt"></i> No Penyaluran</th>
							<th><i class="fas fa-calendar"></i> Tanggal</th>
							<th><i class="fas fa-folder"></i> Jenis Sumber</th>
							<th><i class="fas fa-money-bill-wave"></i> Total Uang</th>
							<th><i class="fas fa-boxes"></i> Total Beras</th>
							<th><i class="fas fa-check-circle"></i> Status</th>
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
									<td><?php echo number_format((float) $rp->total_beras_kg, 2, ',', '.'); ?> Kg</td>
									<td><?php echo html_escape($rp->status); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted">Belum ada data penyaluran.</td>
							</tr>
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
		const zakatMasuk = <?php echo json_encode($chartMasuk); ?>;
		const zakatKeluar = <?php echo json_encode($chartKeluar); ?>;
		const fitrahUang = <?php echo json_encode($chartFitrahUang); ?>;
		const fitrahBeras = <?php echo json_encode($chartFitrahBeras); ?>;

		const canvas = document.getElementById('zakatMonthlyChart');
		if (!canvas || typeof Chart === 'undefined') {
			return;
		}

		new Chart(canvas.getContext('2d'), {
			type: 'line',
			data: {
				labels: labels,
				datasets: [
					{
						label: 'Zakat Masuk (Rp)',
						data: zakatMasuk,
						borderColor: '#28a745',
						backgroundColor: 'rgba(40, 167, 69, 0.15)',
						fill: true,
						tension: 0.2
					},
					{
						label: 'Penyaluran (Rp)',
						data: zakatKeluar,
						borderColor: '#007bff',
						backgroundColor: 'rgba(0, 123, 255, 0.08)',
						fill: true,
						tension: 0.2
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

		const fitrahCanvas = document.getElementById('fitrahMonthlyChart');
		if (!fitrahCanvas) {
			return;
		}

		new Chart(fitrahCanvas.getContext('2d'), {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [
					{
						label: 'Fitrah Uang (Rp)',
						data: fitrahUang,
						backgroundColor: 'rgba(0, 123, 255, 0.35)',
						borderColor: '#007bff',
						borderWidth: 1,
						yAxisID: 'y-uang'
					},
					{
						label: 'Fitrah Beras (Kg)',
						type: 'line',
						data: fitrahBeras,
						borderColor: '#fd7e14',
						backgroundColor: 'rgba(253, 126, 20, 0.12)',
						fill: true,
						tension: 0.25,
						yAxisID: 'y-beras'
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					yAxes: [
						{
							id: 'y-uang',
							position: 'left',
							ticks: {
								beginAtZero: true,
								callback: function (value) {
									return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
								}
							}
						},
						{
							id: 'y-beras',
							position: 'right',
							ticks: {
								beginAtZero: true,
								callback: function (value) {
									return Number(value || 0).toLocaleString('id-ID') + ' Kg';
								}
							},
							gridLines: {
								drawOnChartArea: false
							}
						}
					]
				}
			}
		});
	})();
</script>
