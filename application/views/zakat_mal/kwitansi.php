<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<?php $namaMuzakkiTtd = isset($row->nama_muzakki) && trim((string) $row->nama_muzakki) !== '' ? $row->nama_muzakki : '-'; ?>
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

		.label-col {
			width: 240px;
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

			.receipt-cols, .receipt-cols table {
				font-size: 14px !important;
			}
		}
	</style>
</head>

<body>
	<div class="receipt-wrap">
		<div class="mb-3 no-print text-right">
			<button type="button" class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Cetak
				Kwitansi</button>
			<a href="<?php echo site_url('zakat_mal/export_pdf/' . (int) $row->id); ?>" class="btn btn-danger">
				<i class="fas fa-file-pdf"></i> Export PDF
			</a>
			<a href="<?php echo site_url('zakat_mal'); ?>" class="btn btn-default">Kembali</a>
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
							<div>
								Telp: <?php echo html_escape(!empty($lembaga->no_telp) ? $lembaga->no_telp : '-'); ?> |
								Email: <?php echo html_escape(!empty($lembaga->email) ? $lembaga->email : '-'); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="text-center mb-3">
					<h3 class="receipt-title mb-0"><strong>KWITANSI PENERIMAAN ZAKAT MAL</strong></h3>
					<small>No. Kwitansi:
						<?php echo html_escape(isset($no_kwitansi) ? $no_kwitansi : '-'); ?></small><br>
					<small>No. Transaksi: <?php echo html_escape($row->nomor_transaksi); ?></small>
				</div>

				<div class="row pt-3 border-top receipt-cols">
					<div class="col-6 border-right pr-4">
						<h6 class="mb-3 font-weight-bold">INFORMASI TRANSAKSI</h6>
						<table class="table table-borderless table-sm mb-3 w-auto">
							<tr>
								<td class="pr-3">Tanggal Hitung</td>
								<td>: <?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Tanggal Bayar</td>
								<td>: <?php echo html_escape($row->tanggal_bayar ? indo_date($row->tanggal_bayar) : '-'); ?>
								</td>
							</tr>
							<tr>
								<td class="pr-3">Muzakki</td>
								<td>:
									<strong><?php echo html_escape($row->nama_muzakki); ?></strong>
								</td>
							</tr>
							<tr>
								<td class="pr-3">Harta Bersih</td>
								<td>: Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Nishab</td>
								<td>: Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
							</tr>
							<tr>
								<td class="pr-3">Persentase Zakat</td>
								<td>: <?php echo number_format((float) $row->persentase_zakat, 2, ',', '.'); ?>%</td>
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
					</div>

					<div class="col-6 pl-4">
						<?php if (!empty($detail_rows)): ?>
							<h6 class="mb-3 font-weight-bold">RINCIAN HARTA</h6>
							<div class="table-responsive">
								<table class="table table-bordered table-sm table-striped">
									<thead class="bg-light">
										<tr>
											<th>Jenis Harta</th>
											<th>Nilai Harta</th>
											<th>Haul</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($detail_rows as $detail): ?>
											<tr>
												<td><?php echo html_escape(isset($jenis_harta_options[$detail->jenis_harta_id]) ? $jenis_harta_options[$detail->jenis_harta_id] : $detail->jenis_harta_id); ?>
												</td>
												<td>Rp <?php echo number_format((float) $detail->nilai_harta, 0, ',', '.'); ?></td>
												<td><?php echo $detail->nilai_haul_bulan !== NULL ? (int) $detail->nilai_haul_bulan . ' bln' : '-'; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php else: ?>
							<div class="text-center mt-5 pt-4">
								<p><i>Tidak ada rincian harta tercatat</i></p>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="py-2 mt-3 rounded border text-center" style="border-color: #000 !important;">
					<span class="d-block mb-0" style="font-size: 13px;">Total Zakat Diterima</span>
					<h3 class="mb-0 font-weight-bold">Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></h3>
				</div>

				<div class="row mt-4">
					<div class="col-6 text-center">
						<p class="mb-5">Muzakki,</p>
						<p><strong>(<?php echo html_escape($namaMuzakkiTtd); ?>)</strong></p>
					</div>
					<div class="col-6 text-center">
						<p class="mb-5">Penerima,</p>
						<p><strong>(<?php echo html_escape($namaPenerima); ?>)</strong></p>
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