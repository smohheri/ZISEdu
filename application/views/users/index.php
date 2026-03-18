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

<style>
.avatar-circle {
	width: 38px;
	height: 38px;
	border-radius: 50%;
	color: #fff;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 15px;
	font-weight: 600;
	box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.user-avatar span {
	font-weight: 500;
}
</style>

<div class="row">
	<div class="col-lg-3 col-sm-6 col-12">
		<div class="small-box bg-gradient-primary">
			<div class="inner">
				<h3><?php echo $stats['total']; ?></h3>
				<p>Total Users</p>
			</div>
			<div class="icon">
				<i class="fas fa-users"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6 col-6">
		<div class="small-box bg-gradient-info">
			<div class="inner">
				<h3><?php echo $stats['super_admin']; ?></h3>
				<p>Super Admin</p>
			</div>
			<div class="icon">
				<i class="fas fa-user-shield"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6 col-6">
		<div class="small-box bg-gradient-success">
			<div class="inner">
				<h3><?php echo $stats['amil']; ?></h3>
				<p>Amil</p>
			</div>
			<div class="icon">
				<i class="fas fa-hands-helping"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6 col-12">
		<div class="small-box bg-gradient-warning">
			<div class="inner">
				<h3><?php echo $stats['operator']; ?></h3>
				<p>Operator</p>
			</div>
			<div class="icon">
				<i class="fas fa-user-cog"></i>
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
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">User Aktif</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['aktif']; ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
						<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">User Nonaktif</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['nonaktif']; ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-times-circle fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h3 class="card-title mb-0">
			<i class="fas fa-users mr-2"></i>Data Users / Amil
		</h3>
		<a href="<?php echo site_url('users/create'); ?>" class="btn btn-primary btn-sm float-right">
			<i class="fas fa-plus"></i> Tambah User
		</a>
	</div>
	<div class="card-body">
		<form method="get" action="<?php echo site_url('users'); ?>" class="mb-3">
			<div class="row">
				<div class="col-md-8">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-search"></i></span>
						</div>
						<input type="text" name="q" class="form-control" placeholder="Cari user..."
							value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
						<div class="input-group-append">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-search mr-1"></i>Cari
							</button>
							<a href="<?php echo site_url('users'); ?>" class="btn btn-default">
								<i class="fas fa-sync-alt mr-1"></i>Reset
							</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<div class="table-responsive">
		<table id="users-table" class="table table-sm table-bordered table-striped table-hover text-nowrap" style="width:100%">
			<thead>
				<tr>
					<th width="50">No</th>
					<th>User</th>
					<th>Username</th>
					<th>Email</th>
					<th>Role</th>
					<th>Status</th>
					<th>Last Login</th>
					<th width="120">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($rows)): ?>
					<?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
					<?php foreach ($rows as $row): ?>
						<?php
						$nama = (string) $row->nama_lengkap;
						$parts = preg_split('/\s+/', trim($nama));
						$initials = '';
						foreach ($parts as $part) {
							if ($part !== '') {
								$initials .= strtoupper(substr($part, 0, 1));
							}
							if (strlen($initials) >= 2) {
								break;
							}
						}
						if ($initials === '') {
							$initials = 'U';
						}
						$colors = array('#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6c757d', '#6610f2', '#e83e8c');
						$color = $colors[((int) $row->id) % count($colors)];
						?>
						<tr>
							<td><?php echo $no++; ?></td>
							<td>
								<div class="user-avatar d-inline-flex align-items-center">
									<div class="avatar-circle mr-2" style="background-color: <?php echo $color; ?>">
										<?php echo html_escape($initials); ?>
									</div>
									<span><?php echo html_escape($row->nama_lengkap); ?></span>
								</div>
							</td>
							<td><?php echo html_escape($row->username); ?></td>
							<td><?php echo html_escape($row->email ? $row->email : '-'); ?></td>
							<td>
								<?php if ($row->role === 'super_admin'): ?>
									<span class="badge badge-danger"><i class="fas fa-shield-alt mr-1"></i>Super Admin</span>
								<?php elseif ($row->role === 'amil'): ?>
									<span class="badge badge-info"><i class="fas fa-hands-helping mr-1"></i>Amil</span>
								<?php else: ?>
									<span class="badge badge-secondary"><i class="fas fa-cog mr-1"></i>Operator</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if ((int) $row->is_active === 1): ?>
									<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aktif</span>
								<?php else: ?>
									<span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Nonaktif</span>
								<?php endif; ?>
							</td>
							<td><?php echo $row->last_login ? html_escape($row->last_login) : '-'; ?></td>
							<td class="text-center">
								<div class="btn-group btn-group-sm" role="group" aria-label="Aksi" style="display:inline-flex;">
									<a class="btn btn-warning" href="<?php echo site_url('users/edit/' . $row->id); ?>" title="Edit">
										<i class="fas fa-edit"></i>
									</a>
									<a class="btn btn-danger delete-btn" href="#"
										data-id="<?php echo (int) $row->id; ?>"
										data-name="<?php echo html_escape($row->nama_lengkap); ?>" title="Hapus">
										<i class="fas fa-trash"></i>
									</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="8" class="text-center text-muted">Belum ada data user.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		</div>

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

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title" id="deleteModalLabel">
					<i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus User
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Apakah Anda yakin ingin menghapus user <strong id="delete-user-name"></strong>?</p>
				<p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times mr-1"></i> Batal
				</button>
				<a href="#" id="delete-url" class="btn btn-danger">
					<i class="fas fa-trash mr-1"></i> Hapus
				</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$(document).on('click', '.delete-btn', function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			var name = $(this).data('name');
			$('#delete-user-name').text(name);
			$('#delete-url').attr('href', '<?php echo site_url('users/delete/'); ?>' + id);
			$('#deleteModal').modal('show');
		});

		setTimeout(function () {
			$('#alert-success, #alert-error').fadeOut('slow');
		}, 5000);
	});
</script>

