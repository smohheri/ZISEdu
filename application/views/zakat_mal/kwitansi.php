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
			size: 9.5in 11in;
			margin: 8mm 10mm;
		}

		@media print {

			html,
			body {
				width: 9.5in;
				height: 11in;
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
			}

			.receipt-wrap {
				margin: 0;
				max-width: 100%;
			}

			.card-body {
				padding: 12px;
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

				<table class="table table-borderless table-sm mb-3">
					<tr>
						<td class="label-col">Tanggal Hitung</td>
						<td>: <?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
					</tr>
					<tr>
						<td class="label-col">Tanggal Bayar</td>
						<td>: <?php echo html_escape($row->tanggal_bayar ? indo_date($row->tanggal_bayar) : '-'); ?>
						</td>
					</tr>
					<tr>
						<td class="label-col">Muzakki</td>
						<td>:
							<?php echo html_escape(($row->kode_muzakki ? $row->kode_muzakki . ' - ' : '') . $row->nama_muzakki); ?>
						</td>
					</tr>
					<tr>
						<td class="label-col">Harta Bersih</td>
						<td>: Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="label-col">Nishab</td>
						<td>: Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="label-col">Persentase Zakat</td>
						<td>: <?php echo number_format((float) $row->persentase_zakat, 2, ',', '.'); ?>%</td>
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

				<div class="p-3 mb-3 bg-light rounded border">
					<h5 class="mb-1">Total Zakat Diterima</h5>
					<h3 class="mb-0">Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></h3>
				</div>

				<?php if (!empty($detail_rows)): ?>
					<h6 class="mt-4"><strong>Rincian Harta</strong></h6>
					<div class="table-responsive">
						<table class="table table-bordered table-sm">
							<thead>
								<tr>
									<th>Jenis Harta</th>
									<th>Nilai Harta</th>
									<th>Haul (bulan)</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($detail_rows as $detail): ?>
									<tr>
										<td><?php echo html_escape(isset($jenis_harta_options[$detail->jenis_harta_id]) ? $jenis_harta_options[$detail->jenis_harta_id] : $detail->jenis_harta_id); ?>
										</td>
										<td>Rp <?php echo number_format((float) $detail->nilai_harta, 0, ',', '.'); ?></td>
										<td><?php echo $detail->nilai_haul_bulan !== NULL ? (int) $detail->nilai_haul_bulan : '-'; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>

				<div class="row mt-5">
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
</body>

</html>