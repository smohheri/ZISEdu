<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
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

<!-- Statistics Cards -->
<div class="row">
	<div class="col-lg-3 col-6">
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
	<div class="col-lg-3 col-6">
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
	<div class="col-lg-3 col-6">
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
	<div class="col-lg-3 col-6">
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

<!-- Secondary Stats Row -->
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

<!-- Main Card -->
<div class="card">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h3 class="card-title mb-0">
			<i class="fas fa-users mr-2"></i>Data Users / Amil
		</h3>
		<a href="<?php echo site_url('users/create'); ?>" class="btn btn-primary btn-sm">
			<i class="fas fa-plus"></i> Tambah User
		</a>
	</div>
	<div class="card-body">
		<!-- Search Box -->
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-search"></i></span>
					</div>
					<input type="text" id="search-input" class="form-control" placeholder="Cari user...">
				</div>
			</div>
		</div>

		<!-- Table -->
		<table id="users-table" class="table table-bordered table-striped table-hover" style="width:100%">
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
			</tbody>
		</table>

		<!-- Pagination Info -->
		<div class="mt-2">
			<span id="table-info" class="text-muted"></span>
		</div>
	</div>
</div>

<!-- Delete Confirmation Modal -->
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

<!-- DataTables CSS & JS -->
<link rel="stylesheet"
	href="<?php echo base_url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
<script src="<?php echo base_url('adminlte/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>

<script>
	$(document).ready(function () {
		// Get CSRF token name and hash
		var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
		var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

		// Initialize DataTable
		var table = $('#users-table').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: '<?php echo site_url('users/ajax_list'); ?>',
				type: 'POST',
				data: function (d) {
					d.search = $('#search-input').val();
					d[csrfName] = csrfHash;
				},
				dataSrc: function (json) {
					// Update CSRF token after each request
					if (json.csrf_hash) {
						csrfHash = json.csrf_hash;
					}
					return json.data;
				},
				error: function (xhr, error, thrown) {
					console.log('Ajax Error:', xhr.responseText);
					alert('Error loading data: ' + thrown);
				}
			},
			columns: [
				{
					data: null,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					},
					orderable: false,
					searchable: false
				},
				{
					data: 'nama_lengkap',
					render: function (data, type, row) {
						// Create avatar with initials
						var initials = data.split(' ').map(function (n) { return n[0]; }).join('').substring(0, 2).toUpperCase();
						var colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6c757d', '#6610f2', '#e83e8c'];
						var colorIndex = row.id % colors.length;

						return '<div class="user-avatar d-inline-flex align-items-center">' +
							'<div class="avatar-circle mr-2" style="background-color: ' + colors[colorIndex] + '">' +
							initials + '</div>' +
							'<span>' + data + '</span></div>';
					}
				},
				{ data: 'username' },
				{ data: 'email' },
				{
					data: 'role',
					render: function (data) {
						var badges = {
							'super_admin': '<span class="badge badge-danger"><i class="fas fa-shield-alt mr-1"></i>Super Admin</span>',
							'amil': '<span class="badge badge-info"><i class="fas fa-hands-helping mr-1"></i>Amil</span>',
							'operator': '<span class="badge badge-secondary"><i class="fas fa-cog mr-1"></i>Operator</span>'
						};
						return badges[data] || data;
					}
				},
				{
					data: 'is_active',
					render: function (data) {
						return data === 1
							? '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aktif</span>'
							: '<span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Nonaktif</span>';
					}
				},
				{ data: 'last_login' },
				{
					data: null,
					render: function (data, type, row) {
						return '<div class="btn-group">' +
							'<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">' +
							'<i class="fas fa-ellipsis-v"></i></button>' +
							'<div class="dropdown-menu">' +
							'<a class="dropdown-item" href="<?php echo site_url('users/edit/'); ?>' + row.id + '">' +
							'<i class="fas fa-edit mr-2 text-warning"></i>Edit</a>' +
							'<div class="dropdown-divider"></div>' +
							'<a class="dropdown-item delete-btn" href="#" data-id="' + row.id + '" data-name="' + row.nama_lengkap + '">' +
							'<i class="fas fa-trash mr-2 text-danger"></i>Hapus</a>' +
							'</div></div>';
					},
					orderable: false,
					searchable: false
				}
			],
			order: [[0, 'desc']],
			language: {
				emptyTable: 'Belum ada data user.',
				info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
				infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
				lengthMenu: 'Tampilkan _MENU_ data per halaman',
				loadingRecords: 'Memuat data...',
				processing: 'Memproses data...',
				search: 'Cari:',
				zeroRecords: 'Tidak ada data yang cocok.',
				paginate: {
					first: 'Pertama',
					last: 'Terakhir',
					next: 'Berikutnya',
					previous: 'Sebelumnya'
				}
			},
			drawCallback: function (settings) {
				var info = table.page.info();
				$('#table-info').text('Halaman ' + (info.page + 1) + ' dari ' + info.pages + ' halaman');
			}
		});

		// Custom search delay
		var searchTimeout = null;
		$('#search-input').on('keyup', function () {
			clearTimeout(searchTimeout);
			searchTimeout = setTimeout(function () {
				table.draw();
			}, 300);
		});

		// Delete modal
		$(document).on('click', '.delete-btn', function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			var name = $(this).data('name');
			$('#delete-user-name').text(name);
			$('#delete-url').attr('href', '<?php echo site_url('users/delete/'); ?>' + id);
			$('#deleteModal').modal('show');
		});

		// Auto-hide alerts after 5 seconds
		setTimeout(function () {
			$('#alert-success, #alert-error').fadeOut('slow');
		}, 5000);
	});
</script>

<style>
	.user-avatar {
		display: flex;
		align-items: center;
	}

	.avatar-circle {
		width: 32px;
		height: 32px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		color: white;
		font-weight: bold;
		font-size: 12px;
	}

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

	.btn-group .dropdown-menu {
		min-width: 120px;
	}

	/* Responsive adjustments */
	@media screen and (max-width: 768px) {
		.small-box h3 {
			font-size: 1.5rem;
		}

		.small-box p {
			font-size: 0.8rem;
		}

		#users-table {
			font-size: 0.85rem;
		}

		#users-table th,
		#users-table td {
			padding: 0.5rem 0.25rem;
		}

		.avatar-circle {
			width: 28px;
			height: 28px;
			font-size: 10px;
		}

		.badge {
			font-size: 0.7rem;
			padding: 0.25rem 0.4rem;
		}
	}
</style>
