<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $nama_penerima = isset($nama_penerima) ? $nama_penerima : '-'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Kwitansi Gabungan - <?php echo html_escape($batch_id); ?></title>
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/dist/css/adminlte.min.css'); ?>">
	<style>
		/* Same print styles as existing kwitansi */
		@media print {
			.no-print {
				display: none !important;
			}
		}

		.receipt-section {
			page-break-inside: avoid;
			margin-bottom: 20px;
			border: 1px solid #ddd;
			padding: 15px;
		}

		.grand-total {
			background: #f8f9fa;
			padding: 20px;
			text-align: center;
			font-size: 1.5em;
		}
	</style>
</head>

<body>
	<div class="container-fluid receipt-wrap">
		<div class="mb-3 no-print text-right">
			<button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
			<a href="<?php echo site_url('transaksi_unified'); ?>" class="btn btn-secondary">Transaksi Baru</a>
			<a href="<?php echo site_url('dashboard'); ?>" class="btn btn-default">Dashboard</a>
		</div>

		<!-- Header Lembaga -->
		<div class="text-center mb-4 kop-wrap">
			<?php if ($lembaga && !empty($lembaga->logo_path)): ?>
				<img src="<?php echo base_url($lembaga->logo_path); ?>" alt="Logo" style="max-height: 80px;">
			<?php endif; ?>
			<h2><?php echo html_escape($lembaga->nama_lembaga ?? 'ZISEDU'); ?></h2>
			<p><?php echo html_escape($lembaga->alamat ?? ''); ?></p>
		</div>

		<div class="text-center mb-4">
			<h3><strong>KWITANSI GABUNGAN TRANSAKSI ZAKAT & INFAQ</strong></h3>
			<p><strong>Batch ID: <?php echo html_escape($batch_id); ?></strong></p>
			<small>Penerima: <?php echo html_escape($nama_penerima); ?></small>
		</div>

		<div class="row">
			<!-- Zakat Fitrah Section -->
			<?php if (!empty($fitrah)): ?>
				<div class="col-md-12 receipt-section">
					<h4><i class="fas fa-users text-success"></i> Zakat Fitrah</h4>
					<table class="table table-bordered">
						<tr>
							<td><strong>No. Transaksi:</strong> <?php echo html_escape($fitrah->nomor_transaksi); ?></td>
						</tr>
						<tr>
							<td><strong>No. Kwitansi:</strong> <?php echo html_escape($fitrah->no_kwitansi ?? '-'); ?></td>
						</tr>
						<tr>
							<strong>Muzakki:</strong>
							<?php echo html_escape($fitrah->nama_muzakki ?? $fitrah->muzakki_nama ?? ''); ?></td>

						</tr>
						<tr>
							<td><strong>Tanggal:</strong> <?php echo indo_date($fitrah->tanggal_bayar); ?></td>
						</tr>
						<tr>
							<td><strong>Jumlah Jiwa:</strong> <?php echo (int) $fitrah->jumlah_jiwa; ?></td>
						</tr>
						<tr>
							<td><strong>Total:</strong> Rp <?php echo number_format($fitrah->nominal_uang, 0, ',', '.'); ?>
							</td>
						</tr>
					</table>
				</div>
			<?php endif; ?>

			<!-- Zakat Mal Section -->
			<?php if (!empty($mal)): ?>
				<div class="col-md-12 receipt-section">
					<h4><i class="fas fa-coins text-warning"></i> Zakat Mal</h4>
					<table class="table table-bordered">
						<tr>
							<td><strong>No. Transaksi:</strong> <?php echo html_escape($mal->nomor_transaksi); ?></td>
						</tr>
						<tr>
							<td><strong>No. Kwitansi:</strong> <?php echo html_escape($mal->no_kwitansi ?? '-'); ?></td>
						</tr>
						<tr>
							<td><strong>Total Zakat:</strong> Rp
								<?php echo number_format($mal->total_zakat, 0, ',', '.'); ?>
							</td>
						</tr>
					</table>
				</div>
			<?php endif; ?>

			<!-- Infaq Section -->
			<?php if (!empty($infaq)): ?>
				<div class="col-md-12 receipt-section">
					<h4><i class="fas fa-hand-holding-heart text-info"></i> Infaq / Shodaqoh</h4>
					<table class="table table-bordered">
						<tr>
							<td><strong>No. Transaksi:</strong> <?php echo html_escape($infaq->nomor_transaksi); ?></td>
						</tr>
						<tr>
							<td><strong>No. Kwitansi:</strong> <?php echo html_escape($infaq->no_kwitansi ?? '-'); ?></td>
						</tr>
						<tr>
							<td><strong>Donatur:</strong> <?php echo html_escape($infaq->nama_donatur); ?></td>
						</tr>
						<tr>
							<td><strong>Total:</strong> Rp <?php echo number_format($infaq->nominal_uang, 0, ',', '.'); ?>
							</td>
						</tr>
					</table>
				</div>
			<?php endif; ?>
		</div>

		<!-- Grand Total -->
		<div class="grand-total mt-4">
			<h3><strong>GRAND TOTAL: Rp <?php echo number_format(
				(($fitrah->nominal_uang ?? 0) + ($mal->total_zakat ?? 0) + ($infaq->nominal_uang ?? 0)),
				0,
				',',
				'.'
			);
			?></strong></h3>
		</div>

		<!-- Signatures -->
		<div class="row mt-5">
			<div class="col-6 text-left">
				<p>Donatur / Muzakki,</p>
				<div style="height: 60px; border-bottom: 1px solid #000;"></div>
			</div>
			<div class="col-6 text-right">
				<p>Penerima,</p>
				<div style="height: 60px; border-bottom: 1px solid #000;"></div>
				<small>(<?php echo html_escape($nama_penerima); ?>)</small>
			</div>
		</div>
	</div>
</body>

</html>