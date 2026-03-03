<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$total_pengaturan = isset($total_pengaturan) ? (int) $total_pengaturan : 0;
$tahun_terbaru = '-';
$fitrah_terkini = 0;
$harga_beras_terkini = 0;
$nilai_emas_terkini = 0;

if (!empty($latest_pengaturan)) {
	$terkini = $latest_pengaturan;
	$tahun_terbaru = (int) $terkini->tahun;
	$fitrah_terkini = (float) $terkini->fitrah_per_jiwa_rupiah;
	$harga_beras_terkini = (float) $terkini->harga_beras_per_kg;
	$nilai_emas_terkini = (float) $terkini->nilai_emas_per_gram;
}
?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success alert-dismissible fade show" id="alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="alert alert-danger alert-dismissible fade show" id="alert-error">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-lg-3 col-6">
		<div class="small-box bg-gradient-primary">
			<div class="inner">
				<h3><?php echo $total_pengaturan; ?></h3>
				<p>Total Pengaturan</p>
			</div>
			<div class="icon">
				<i class="fas fa-cogs"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-6">
		<div class="small-box bg-gradient-info">
			<div class="inner">
				<h3><?php echo html_escape($tahun_terbaru); ?></h3>
				<p>Tahun Terbaru</p>
			</div>
			<div class="icon">
				<i class="fas fa-calendar-alt"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-6">
		<div class="small-box bg-gradient-success">
			<div class="inner">
				<h3>Rp <?php echo number_format($fitrah_terkini, 0, ',', '.'); ?></h3>
				<p>Fitrah/Jiwa Terkini</p>
			</div>
			<div class="icon">
				<i class="fas fa-hand-holding-usd"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-6">
		<div class="small-box bg-gradient-warning">
			<div class="inner">
				<h3>Rp <?php echo number_format($nilai_emas_terkini, 0, ',', '.'); ?></h3>
				<p>Nilai Emas/Gram</p>
			</div>
			<div class="icon">
				<i class="fas fa-coins"></i>
			</div>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Harga Beras Terkini</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">Rp
							<?php echo number_format($harga_beras_terkini, 0, ',', '.'); ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-seedling fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card border-left-danger shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Nilai Emas Terkini</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">Rp
							<?php echo number_format($nilai_emas_terkini, 0, ',', '.'); ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-coins fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h3 class="card-title mb-0">
			<i class="fas fa-sliders-h mr-2"></i>Data Pengaturan Zakat Tahunan
		</h3>
		<a href="<?php echo site_url('pengaturan_zakat/create'); ?>" class="btn btn-primary btn-sm float-right">
			<i class="fas fa-plus"></i> Tambah Pengaturan
		</a>
	</div>
	<div class="card-body">
		<form method="get" action="<?php echo site_url('pengaturan_zakat'); ?>" class="mb-3">
			<div class="row">
				<div class="col-md-8">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-search"></i></span>
						</div>
						<input type="text" name="q" class="form-control" placeholder="Cari tahun atau nilai..."
							value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
						<div class="input-group-append">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-search mr-1"></i>Cari
							</button>
							<a href="<?php echo site_url('pengaturan_zakat'); ?>" class="btn btn-default">
								<i class="fas fa-sync-alt mr-1"></i>Reset
							</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<table class="table table-sm table-bordered table-striped table-hover" style="width:100%">
			<thead>
				<tr>
					<th width="50">No</th>
					<th>Tahun</th>
					<th>Fitrah/Jiwa (Kg)</th>
					<th>Fitrah/Jiwa (Rp)</th>
					<th>Harga Beras/Kg</th>
					<th>Nilai Emas/Gram</th>
					<th width="120">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($rows)): ?>
					<?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
					<?php foreach ($rows as $row): ?>
						<tr>
							<td><?php echo $no++; ?></td>
							<td><?php echo html_escape($row->tahun); ?></td>
							<td><?php echo html_escape($row->fitrah_per_jiwa_kg); ?></td>
							<td>Rp <?php echo number_format((float) $row->fitrah_per_jiwa_rupiah, 0, ',', '.'); ?></td>
							<td>Rp <?php echo number_format((float) $row->harga_beras_per_kg, 0, ',', '.'); ?></td>
							<td>Rp <?php echo number_format((float) $row->nilai_emas_per_gram, 0, ',', '.'); ?></td>
							<td>
								<div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
									<a class="btn btn-warning"
										href="<?php echo site_url('pengaturan_zakat/edit/' . $row->id); ?>">
										<i class="fas fa-edit"></i>
									</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7" class="text-center text-muted">Belum ada data pengaturan zakat.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<div class="d-flex justify-content-between align-items-center mt-2">
			<span class="text-muted">
				<?php
				$total_rows = isset($paging['total_rows']) ? (int) $paging['total_rows'] : 0;
				$offset = isset($paging['offset']) ? (int) $paging['offset'] : 0;
				$shown = !empty($rows) ? count($rows) : 0;
				echo 'Menampilkan ' . ($shown > 0 ? ($offset + 1) : 0) . ' - ' . ($offset + $shown) . ' dari ' . $total_rows . ' data';
				?>
			</span>
			<div><?php echo isset($paging_links) ? $paging_links : ''; ?></div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		setTimeout(function () {
			$('#alert-success, #alert-error').fadeOut('slow');
		}, 5000);
	});
</script>

<style>
	.small-box {
		border-radius: 10px;
		box-shadow: 0 0.125rem 0.25rem 0 rgba(58, 59, 69, 0.15);
	}

	.small-box .icon {
		top: 10px;
		right: 15px;
		z-index: 0;
	}

	.border-left-success {
		border-left: 0.25rem solid #28a745 !important;
	}

	.border-left-danger {
		border-left: 0.25rem solid #dc3545 !important;
	}

	@media screen and (max-width: 768px) {
		.small-box h3 {
			font-size: 1.4rem;
		}

		.small-box p {
			font-size: 0.8rem;
		}

		#pengaturan-zakat-table {
			font-size: 0.85rem;
		}

		#pengaturan-zakat-table th,
		#pengaturan-zakat-table td {
			padding: 0.5rem 0.25rem;
		}

		.btn-sm {
			font-size: 0.72rem;
			padding: 0.25rem 0.5rem;
		}
	}
</style>
