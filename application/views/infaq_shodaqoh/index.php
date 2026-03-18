<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
	<div class="col-lg-3 col-sm-6 col-12">
		<div class="small-box bg-gradient-primary">
			<div class="inner">
				<h3><?php echo isset($stats['total']) ? (int) $stats['total'] : 0; ?></h3>
				<p>Total Transaksi</p>
			</div>
			<div class="icon">
				<i class="fas fa-receipt"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6 col-6">
		<div class="small-box bg-gradient-info">
			<div class="inner">
				<h3><?php echo isset($stats['infaq']) ? (int) $stats['infaq'] : 0; ?></h3>
				<p>Transaksi Infaq</p>
			</div>
			<div class="icon">
				<i class="fas fa-donate"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6 col-6">
		<div class="small-box bg-gradient-success">
			<div class="inner">
				<h3><?php echo isset($stats['shodaqoh']) ? (int) $stats['shodaqoh'] : 0; ?></h3>
				<p>Transaksi Shodaqoh</p>
			</div>
			<div class="icon">
				<i class="fas fa-hand-holding-heart"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6 col-12">
		<div class="small-box bg-gradient-warning">
			<div class="inner">
				<h3>Rp
					<?php echo number_format((float) (isset($stats['total_nominal']) ? $stats['total_nominal'] : 0), 0, ',', '.'); ?>
				</h3>
				<p>Total Diterima</p>
			</div>
			<div class="icon">
				<i class="fas fa-wallet"></i>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h3 class="card-title mb-0">
			<i class="fas fa-coins mr-2"></i>Transaksi Infaq & Shodaqoh
		</h3>
		<a href="<?php echo site_url('infaq_shodaqoh/create'); ?>" class="btn btn-primary btn-sm float-right">
			<i class="fas fa-plus"></i> Transaksi Baru
		</a>
	</div>
	<div class="card-body">
		<form method="get" action="<?php echo site_url('infaq_shodaqoh'); ?>" class="mb-3">
			<div class="row">
				<div class="col-md-8">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-search"></i></span>
						</div>
						<input type="text" name="q" class="form-control"
							placeholder="Cari nomor, donatur, jenis, status..."
							value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
						<div class="input-group-append">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-search mr-1"></i>Cari
							</button>
							<a href="<?php echo site_url('infaq_shodaqoh'); ?>" class="btn btn-default">
								<i class="fas fa-sync-alt mr-1"></i>Reset
							</a>
						</div>
					</div>
				</div>
			</div>
		</form>

<<<<<<< HEAD
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped table-hover text-nowrap">
				<thead>
					<tr>
						<th width="50">No</th>
						<th>No Transaksi</th>
						<th>Tanggal</th>
						<th>Jenis Dana</th>
						<th>Donatur</th>
						<th>Nominal</th>
						<th>Metode</th>
						<th>Status</th>
						<th width="120">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($rows)): ?>
						<?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
						<?php foreach ($rows as $row): ?>
							<tr>
								<td><?php echo $no++; ?></td>
								<td><?php echo html_escape($row->nomor_transaksi); ?></td>
								<td><?php echo html_escape(indo_date($row->tanggal_transaksi)); ?></td>
								<td>
									<?php
									$jenisLabels = [
										'infaq' => 'Infaq',
										'shodaqoh' => 'Shodaqoh',
										'infaq_shodaqoh' => 'Infaq & Shodaqoh'
									];
									echo html_escape($jenisLabels[$row->jenis_dana] ?? ucfirst($row->jenis_dana));
									?>
								</td>
								<td><?php echo html_escape($row->nama_muzakki ?: $row->nama_donatur); ?></td>
								</xai:function_call; <xai:function_call name="edit_file">
								<parameter name="path">application/views/infaq_shodaqoh/kwitansi.php
									<td>Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?></td>
									<td><?php echo strtoupper(html_escape($row->metode_bayar)); ?></td>
									<td>
										<?php if ($row->status === 'diterima'): ?>
											<span class="badge badge-success">Diterima</span>
										<?php elseif ($row->status === 'draft'): ?>
											<span class="badge badge-warning">Draft</span>
										<?php else: ?>
											<span class="badge badge-danger">Batal</span>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
											<a class="btn btn-success" target="_blank"
												href="<?php echo site_url('infaq_shodaqoh/kwitansi/' . $row->id); ?>"
												title="Kwitansi">
												<i class="fas fa-print"></i>
											</a>
											<a class="btn btn-danger" target="_blank"
												href="<?php echo site_url('infaq_shodaqoh/export_pdf/' . $row->id); ?>"
												title="Export PDF">
												<i class="fas fa-file-pdf"></i>
											</a>
											<a class="btn btn-warning"
												href="<?php echo site_url('infaq_shodaqoh/edit/' . $row->id); ?>" title="Edit">
												<i class="fas fa-edit"></i>
											</a>
											<a class="btn btn-danger"
												href="<?php echo site_url('infaq_shodaqoh/delete/' . $row->id); ?>"
												title="Hapus" onclick="return confirm('Hapus data?')">
												<i class="fas fa-trash"></i>
											</a>
										</div>
									</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="9" class="text-center text-muted">Belum ada transaksi infaq/shodaqoh.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
=======
        <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Jenis Dana</th>
                    <th>Donatur</th>
                    <th>Nominal</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                            <td><?php echo html_escape(indo_date($row->tanggal_transaksi)); ?></td>
                            <td><?php echo ucfirst(html_escape($row->jenis_dana)); ?></td>
                            <td><?php echo html_escape($row->nama_donatur); ?></td>
                            <td>Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?></td>
                            <td><?php echo strtoupper(html_escape($row->metode_bayar)); ?></td>
                            <td>
                                <?php if ($row->status === 'diterima'): ?>
                                    <span class="badge badge-success">Diterima</span>
                                <?php elseif ($row->status === 'draft'): ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                    <a class="btn btn-success" target="_blank" href="<?php echo site_url('infaq_shodaqoh/kwitansi/' . $row->id); ?>" title="Cetak Kwitansi">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <button type="button" class="btn btn-info btn-detail-infaq" title="Detail"
                                        data-toggle="modal" data-target="#modal-detail-infaq"
                                        data-nomor="<?php echo html_escape($row->nomor_transaksi); ?>"
                                        data-tanggal="<?php echo html_escape(indo_date($row->tanggal_transaksi)); ?>"
                                        data-donatur="<?php echo html_escape($row->nama_donatur); ?>"
                                        data-jenis="<?php echo ucfirst(html_escape($row->jenis_dana)); ?>"
                                        data-nohp="<?php echo html_escape(!empty($row->no_hp) ? $row->no_hp : '-'); ?>"
                                        data-nominal="Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>"
                                        data-metode="<?php echo html_escape(ucfirst($row->metode_bayar)); ?>"
                                        data-status="<?php echo html_escape(strtoupper($row->status)); ?>"
                                        data-keterangan="<?php echo html_escape(!empty($row->keterangan) ? $row->keterangan : '-'); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a class="btn btn-warning" href="<?php echo site_url('infaq_shodaqoh/edit/' . $row->id); ?>" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-danger" href="<?php echo site_url('infaq_shodaqoh/delete/' . $row->id); ?>"
                                        title="Hapus" onclick="return confirm('Hapus data?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada transaksi infaq/shodaqoh.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
>>>>>>> 9e961c5 (Release v1.1.6: Dashboard layout and MVC refactor)

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

<div class="modal fade" id="modal-detail-infaq" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Infaq & Shodaqoh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2"><strong>No Transaksi:</strong> <span id="detail-infaq-nomor">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Tanggal:</strong> <span id="detail-infaq-tanggal">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Jenis Dana:</strong> <span id="detail-infaq-jenis">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Donatur:</strong> <span id="detail-infaq-donatur">-</span></div>
                    <div class="col-md-6 mb-2"><strong>No HP:</strong> <span id="detail-infaq-nohp">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Nominal:</strong> <span id="detail-infaq-nominal">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Metode Bayar:</strong> <span id="detail-infaq-metode">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Status:</strong> <span id="detail-infaq-status">-</span></div>
                    <div class="col-md-12 mb-2"><strong>Keterangan:</strong><br><span id="detail-infaq-keterangan">-</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
<<<<<<< HEAD
	$(document).ready(function () {
		setTimeout(function () {
			$('#alert-success, #alert-error').fadeOut('slow');
		}, 5000);
	});
</script>
=======
    $(document).ready(function () {
        setTimeout(function () {
            $('#alert-success, #alert-error').fadeOut('slow');
        }, 5000);

        $('.btn-detail-infaq').on('click', function () {
            const btn = $(this);
            $('#detail-infaq-nomor').text(btn.data('nomor') || '-');
            $('#detail-infaq-tanggal').text(btn.data('tanggal') || '-');
            $('#detail-infaq-jenis').text(btn.data('jenis') || '-');
            $('#detail-infaq-donatur').text(btn.data('donatur') || '-');
            $('#detail-infaq-nohp').text(btn.data('nohp') || '-');
            $('#detail-infaq-nominal').text(btn.data('nominal') || '-');
            $('#detail-infaq-metode').text(btn.data('metode') || '-');
            $('#detail-infaq-status').text(btn.data('status') || '-');
            $('#detail-infaq-keterangan').text(btn.data('keterangan') || '-');
        });
    });
</script>
>>>>>>> 9e961c5 (Release v1.1.6: Dashboard layout and MVC refactor)
