<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Kwitansi Infaq / Shodaqoh</title>
	<style>
		body {
			font-family: sans-serif;
			font-size: 9.5px;
			color: #000;
		}

		.receipt-wrap {
			width: 100%;
		}

		.kop-wrap {
			border-bottom: 2px solid #000;
			padding-bottom: 8px;
			margin-bottom: 12px;
		}

		.kop-table {
			width: 100%;
			border-collapse: collapse;
		}

		.kop-table td {
			vertical-align: middle;
		}

		.kop-logo {
			width: 70px;
			height: auto;
		}

		.text-center {
			text-align: center;
		}

		.receipt-title {
			font-size: 16px;
			font-weight: bold;
			margin: 0;
			letter-spacing: .5px;
		}

		.meta {
			font-size: 10px;
			margin-top: 2px;
		}

		.info-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 8px;
			margin-bottom: 8px;
			font-size: 11px;
		}

		.info-table td {
			padding: 3px 2px;
			vertical-align: top;
		}

		.label-col {
			width: 180px;
		}

		.highlight {
			border: 1px solid #000;
			padding: 8px;
			margin: 10px 0 12px;
		}

		.highlight-title {
			font-size: 12px;
			font-weight: bold;
			margin-bottom: 4px;
		}

		.highlight-value {
			font-size: 17px;
			font-weight: bold;
		}

		.sign-wrap {
			width: 100%;
			margin-top: 24px;
			border-collapse: collapse;
		}

		.sign-wrap td {
			width: 50%;
			text-align: center;
			vertical-align: top;
		}

		.sign-space {
			height: 40px;
		}
	</style>
</head>

<body>
	<div class="receipt-wrap">
		<div class="kop-wrap">
			<table class="kop-table">
				<tr>
					<td width="90" class="text-center">
						<?php if (!empty($logo_image_src)): ?>
							<img src="<?php echo $logo_image_src; ?>" alt="Logo" class="kop-logo">
						<?php endif; ?>
					</td>
					<td class="text-center">
						<div style="font-size:18px;font-weight:bold;line-height:1.2;">
							<?php echo html_escape(!empty($lembaga->nama_lembaga) ? $lembaga->nama_lembaga : 'ZISEDU'); ?>
						</div>
						<div><?php echo html_escape(!empty($lembaga->alamat) ? $lembaga->alamat : '-'); ?></div>
						<div>Telp: <?php echo html_escape(!empty($lembaga->no_telp) ? $lembaga->no_telp : '-'); ?> |
							Email: <?php echo html_escape(!empty($lembaga->email) ? $lembaga->email : '-'); ?></div>
					</td>
				</tr>
			</table>
		</div>

		<div class="text-center">
			<p class="receipt-title">KWITANSI PENERIMAAN INFAQ / SHODAQOH</p>
			<div class="meta">No. Kwitansi: <?php echo html_escape(isset($no_kwitansi) ? $no_kwitansi : '-'); ?></div>
			<div class="meta">No. Transaksi: <?php echo html_escape($row->nomor_transaksi); ?></div>
		</div>

		<table
			style="width: 100%; border-collapse: collapse; margin-top: 10px; border-top: 1px solid #000; padding-top: 10px;">
			<tr>
				<td style="width:50%; vertical-align: top; padding-right: 15px; border-right: 1px solid #000;">
					<div style="font-size:12px;font-weight:bold;margin-top:0;color:#000;">INFORMASI TRANSAKSI</div>
					<table class="info-table">
						<tr>
							<td class="label-col">Tanggal Transaksi</td>
							<td>: <?php echo html_escape(indo_date($row->tanggal_transaksi)); ?></td>
						</tr>
						<tr>
							<td class="label-col">Jenis Dana</td>
							<td>: <?php echo ucfirst(html_escape($row->jenis_dana)); ?></td>
						</tr>
						<tr>
							<td class="label-col">Nama Donatur</td>
							<td>: <strong><?php echo html_escape($row->nama_donatur); ?></strong></td>
						</tr>
						<tr>
							<td class="label-col">No. HP</td>
							<td>: <?php echo html_escape(!empty($row->no_hp) ? $row->no_hp : '-'); ?></td>
						</tr>
						<tr>
							<td class="label-col">Metode Bayar</td>
							<td>: <?php echo ucfirst(html_escape($row->metode_bayar)); ?></td>
						</tr>
						<tr>
							<td class="label-col">Status</td>
							<td>: <?php echo strtoupper(html_escape($row->status)); ?></td>
						</tr>
					</table>
					<?php if (!empty($row->keterangan)): ?>
						<div style="margin-top:6px;"><strong>Keterangan:</strong>
							<?php echo html_escape($row->keterangan); ?></div>
					<?php endif; ?>
				</td>
				<td style="width:50%; vertical-align: top; padding-left: 15px;">
					<div class="highlight" style="text-align:center;background:transparent;border:1px solid #000;">
						<div class="highlight-title">TOTAL NOMINAL DITERIMA</div>
						<div class="highlight-value">Rp
							<?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>
						</div>
					</div>
				</td>
			</tr>
		</table>

		<table class="sign-wrap">
			<tr>
				<td style="text-align:left; vertical-align: top;">
					<div>Donatur,</div>
					<div class="sign-space"></div>
					<div><strong>(<?php echo html_escape($row->nama_donatur); ?>)</strong></div>
				</td>
				<td style="text-align:right; vertical-align: top;">
					<div>Penerima,</div>
					<div class="sign-space"></div>
					<div><strong>(<?php echo html_escape($namaPenerima); ?>)</strong></div>
				</td>
			</tr>
		</table>
	</div>
</body>

</html>
