<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($page_title) ? $page_title : 'Kwitansi'; ?></title>
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/dist/css/adminlte.min.css'); ?>">
	<style>
		body {
			background: #f4f6f9;
		}

		.receipt-wrap {
			max-width: 860px;
			margin: 24px auto;
		}

		.receipt-title {
			letter-spacing: 1px;
		}

		.kop-wrap {
			border-bottom: 3px solid #000;
			padding-bottom: 10px;
			margin-bottom: 15px;
		}

		.kop-img {
			max-height: 95px;
			width: auto;
		}

		.kop-title {
			font-size: 21px;
			font-weight: 700;
			line-height: 1.2;
		}

		@page {
			size: 210mm 139mm;
			margin: 0;
		}

		@media print {

			html,
			body {
				width: 210mm;
				height: 139mm;
				max-width: 210mm;
				max-height: 139mm;
				overflow: hidden;
				margin: 0;
				padding: 0;
				font-size: 12px;
			}

			body {
				background: #fff;
			}

			.no-print {
				display: none !important;
			}

			.card {
				border: 1px solid #000 !important;
				box-shadow: none !important;
				margin: 0 !important;
			}

			.receipt-wrap {
				width: 100%;
				height: 100%;
				margin: 0;
				padding: 8mm 10mm;
				box-sizing: border-box;
				max-width: 100%;
			}

			.card-body {
				padding: 12px;
			}

			* {
				color: #000 !important;
			}
		}
	</style>
</head>

<body>
	<div class="receipt-wrap">
		<div class="mb-3 no-print text-right">
			<button type="button" class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i>
				Cetak</button>
			<a href="<?php echo site_url('infaq_shodaqoh/export_pdf/' . (int) $row->id); ?>" class="btn btn-danger"><i
					class="fas fa-file-pdf"></i> Export PDF</a>
			<a href="<?php echo site_url('infaq_shodaqoh'); ?>" class="btn btn-default">Kembali</a>
		</div>

		<div class="card card-outline card-primary">
			<div class="card-body">
				<div class="kop-wrap">
					<div class="row align-items-center">
						<div class="col-2 text-center">
							<?php if (!empty($lembaga->logo_path)): ?>
								<img src="<?php echo base_url($lembaga->logo_path); ?>" alt="Logo" class="kop-img">
							<?php endif; ?>
						</div>
						<div class="col-10 text-center">
							<div class="kop-title">
								<?php echo html_escape(!empty($lembaga->nama_lembaga) ? $lembaga->nama_lembaga : 'ZISEDU'); ?>
							</div>
							<div><?php echo html_escape(!empty($lembaga->alamat) ? $lembaga->alamat : '-'); ?></div>
							<div>Telp: <?php echo html_escape(!empty($lembaga->no_telp) ? $lembaga->no_telp : '-'); ?> |
								Email: <?php echo html_escape(!empty($lembaga->email) ? $lembaga->email : '-'); ?></div>
						</div>
					</div>
				</div>

				<div class="text-center mb-3">
					<h3 class="receipt-title mb-0"><strong>KWITANSI PENERIMAAN INFAQ / SHODAQOH</strong></h3>
					<small>No. Kwitansi:
						<?php echo html_escape(isset($no_kwitansi) ? $no_kwitansi : '-'); ?></small><br>
					<small>No. Transaksi: <?php echo html_escape($row->nomor_transaksi); ?></small>
				</div>

				<div class="row pt-3 border-top receipt-cols">
					<div class="col-6 border-right pr-4">
						<h6 class="mb-3 font-weight-bold">INFORMASI TRANSAKSI</h6>
						<table class="table table-borderless table-sm mb-3 w-auto">
							<tr>
								<td class="pr-3">Tanggal Transaksi</td>
								<td>: <?php echo html_escape(indo_date($row->tanggal_transaksi)); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Jenis Dana</td>
								<td>: <?php echo ucfirst(html_escape($row->jenis_dana)); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Nama Donatur</td>
								<td>: <strong><?php echo html_escape($row->nama_donatur); ?></strong></td>
							</tr>
							<tr>
								<td class="pr-3">No. HP</td>
								<td>: <?php echo html_escape(!empty($row->no_hp) ? $row->no_hp : '-'); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Metode Bayar</td>
								<td>: <?php echo ucfirst(html_escape($row->metode_bayar)); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Status</td>
								<td>: <?php echo strtoupper(html_escape($row->status)); ?></td>
							</tr>
						</table>
						<?php if (!empty($row->keterangan)): ?>
							<div class="mt-2"><strong>Keterangan:</strong> <?php echo html_escape($row->keterangan); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="col-6 pl-4">
						<div class="py-2 mt-1 rounded border text-center" style="border-color: #000 !important;">
							<span class="d-block mb-0" style="font-size:13px;">Total Nominal Diterima</span>
							<h3 class="mb-0 font-weight-bold">Rp
								<?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>
							</h3>
						</div>

					</div>
				</div>

				<div class="row mt-4">
					<div class="col-6 text-left">
						<div>Donatur,</div>
						<div class="sign-space" style="height:40px"></div>
						<div><strong>(<?php echo html_escape($row->nama_donatur); ?>)</strong></div>
					</div>
					<div class="col-6 text-right">
						<div>Penerima,</div>
						<div class="sign-space" style="height:40px"></div>
						<div><strong>(<?php echo html_escape($namaPenerima); ?>)</strong></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		window.addEventListener('beforeprint', function () {
			var card = document.querySelector('.card');
			card.style.transform = 'none';
			card.style.transformOrigin = 'top center';

			// Buat elemen dummy untuk membaca 123mm (tinggi bersih kertas) menjadi pixel riil di browser
			var dummy = document.createElement('div');
			dummy.style.position = 'absolute';
			// Kertas 139mm - (padding 8mm atas + 8mm bawah)
			dummy.style.height = '123mm';
			document.body.appendChild(dummy);
			var maxHeight = dummy.offsetHeight;
			document.body.removeChild(dummy);

			var actualHeight = card.scrollHeight;

			// Jika tinggi riil blok kwitansi melebihi batas kertas
			if (actualHeight > maxHeight) {
				var scale = maxHeight / actualHeight;
				// Terapkan penekanan rasio (kurangi sedikit margin of error)
				card.style.transform = 'scale(' + (scale - 0.01) + ')';
			}
		});

		window.addEventListener('afterprint', function () {
			document.querySelector('.card').style.transform = 'none';
		});
	</script>
</body>

</html>
