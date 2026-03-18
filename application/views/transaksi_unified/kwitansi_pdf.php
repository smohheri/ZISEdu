<!DOCTYPE html>
<html>

<head>
	<title>Kwitansi Gabungan PDF - <?php echo html_escape($batch_id); ?></title>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 12px;
		}

		.header {
			text-align: center;
			margin-bottom: 20px;
		}

		.section {
			margin-bottom: 20px;
			border: 1px solid #000;
			padding: 10px;
		}

		.total {
			font-size: 16px;
			font-weight: bold;
			text-align: center;
			background: #f0f0f0;
			padding: 10px;
		}
	</style>
</head>

<body>
	<div class="header">
		<h2><?php echo html_escape($lembaga->nama_lembaga ?? 'ZISEdu'); ?></h2>
		<h3>KWITANSI GABUNGAN TRANSAKSI</h3>
		<p>Batch ID: <?php echo html_escape($batch_id); ?></p>
	</div>

	<?php if ($fitrah): ?>
		<div class="section">
			<h4>Zakat Fitrah</h4>
			<p>No Transaksi: <?php echo html_escape($fitrah->nomor_transaksi); ?></p>
			<p>Total: Rp <?php echo number_format($fitrah->nominal_uang, 0, ',', '.'); ?></p>
		</div>
	<?php endif; ?>

	<?php if ($mal): ?>
		<div class="section">
			<h4>Zakat Mal</h4>
			<p>No Transaksi: <?php echo html_escape($mal->nomor_transaksi); ?></p>
			<p>Total Zakat: Rp <?php echo number_format($mal->total_zakat, 0, ',', '.'); ?></p>
		</div>
	<?php endif; ?>

	<?php if ($infaq): ?>
		<div class="section">
			<h4>Infaq Shodaqoh</h4>
			<p>No Transaksi: <?php echo html_escape($infaq->nomor_transaksi); ?></p>
			<p>Total: Rp <?php echo number_format($infaq->nominal_uang, 0, ',', '.'); ?></p>
		</div>
	<?php endif; ?>

	<div class="total">
		GRAND TOTAL: Rp <?php echo number_format(
			($fitrah->nominal_uang ?? 0) + ($mal->total_zakat ?? 0) + ($infaq->nominal_uang ?? 0),
			0,
			',',
			'.'
		); ?>
	</div>

	<div style="margin-top: 50px;">
		<div style="float: left; width: 50%; text-align: center;">
			<p>Donatur/Muzakki</p>
			<div style="height: 40px;"></div>
		</div>
		<div style="float: right; width: 50%; text-align: center;">
			<p>Penerima</p>
			<div style="height: 40px;"></div>
			<p>(<?php echo html_escape($nama_penerima); ?>)</p>
		</div>
	</div>
</body>

</html>