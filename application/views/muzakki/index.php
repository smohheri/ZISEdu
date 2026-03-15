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
                <p>Total Muzakki</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3><?php echo isset($stats['individu']) ? (int) $stats['individu'] : 0; ?></h3>
                <p>Individu</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3><?php echo isset($stats['lembaga']) ? (int) $stats['lembaga'] : 0; ?></h3>
                <p>Lembaga</p>
            </div>
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3><?php echo isset($stats['kepala_keluarga']) ? (int) $stats['kepala_keluarga'] : 0; ?></h3>
                <p>Kepala Keluarga</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Data Dengan NIK</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo isset($stats['punya_nik']) ? (int) $stats['punya_nik'] : 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-id-card fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Data Dengan No HP</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo isset($stats['punya_no_hp']) ? (int) $stats['punya_no_hp'] : 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-phone fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-user-friends mr-2"></i>Master Muzakki
        </h3>
        <a href="<?php echo site_url('muzakki/create'); ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Tambah Muzakki
        </a>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('muzakki'); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="q" class="form-control"
                            placeholder="Cari kode, nama, jenis, NIK, HP, alamat..."
                            value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>Cari
                            </button>
                            <a href="<?php echo site_url('muzakki'); ?>" class="btn btn-default">
                                <i class="fas fa-sync-alt mr-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="muzakki-table" class="table table-sm table-bordered table-striped table-hover text-nowrap mb-0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>No HP</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo html_escape($row->kode_muzakki); ?></td>
                                <td>
                                    <?php echo html_escape($row->nama); ?><br>
                                    <small class="text-muted">NIK:
                                        <?php echo html_escape($row->nik ? $row->nik : '-'); ?></small>
                                </td>
                                <td><?php echo html_escape($row->jenis_muzakki); ?></td>
                                <td><?php echo html_escape($row->no_hp ? $row->no_hp : '-'); ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-detail-muzakki" data-toggle="modal"
                                            data-target="#modal-detail-muzakki"
                                            data-kode="<?php echo html_escape($row->kode_muzakki); ?>"
                                            data-nama="<?php echo html_escape($row->nama); ?>"
                                            data-jenis="<?php echo html_escape($row->jenis_muzakki); ?>"
                                            data-nik="<?php echo html_escape($row->nik ? $row->nik : '-'); ?>"
                                            data-nohp="<?php echo html_escape($row->no_hp ? $row->no_hp : '-'); ?>"
                                            data-email="<?php echo html_escape($row->email ? $row->email : '-'); ?>"
                                            data-pekerjaan="<?php echo html_escape($row->pekerjaan ? $row->pekerjaan : '-'); ?>"
                                            data-alamat="<?php echo html_escape($row->alamat ? $row->alamat : '-'); ?>"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a class="btn btn-warning" href="<?php echo site_url('muzakki/edit/' . $row->id); ?>"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a class="btn btn-danger" href="<?php echo site_url('muzakki/delete/' . $row->id); ?>"
                                            title="Hapus" onclick="return confirm('Hapus data?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data muzakki.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="text-muted">
                <?php
                $total_filtered = isset($paging['total_rows']) ? (int) $paging['total_rows'] : 0;
                $offset = isset($paging['offset']) ? (int) $paging['offset'] : 0;
                $shown = !empty($rows) ? count($rows) : 0;
                echo 'Menampilkan ' . ($shown > 0 ? ($offset + 1) : 0) . ' - ' . ($offset + $shown) . ' dari ' . $total_filtered . ' data';
                ?>
            </span>
            <div><?php echo isset($paging_links) ? $paging_links : ''; ?></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail-muzakki" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Muzakki</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2"><strong>Kode:</strong> <span id="detail-kode">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Jenis:</strong> <span id="detail-jenis">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Nama:</strong> <span id="detail-nama">-</span></div>
                    <div class="col-md-6 mb-2"><strong>NIK:</strong> <span id="detail-nik">-</span></div>
                    <div class="col-md-6 mb-2"><strong>No HP:</strong> <span id="detail-nohp">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Email:</strong> <span id="detail-email">-</span></div>
                    <div class="col-md-12 mb-2"><strong>Pekerjaan:</strong> <span id="detail-pekerjaan">-</span></div>
                    <div class="col-md-12 mb-2"><strong>Alamat:</strong><br><span id="detail-alamat">-</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#alert-success, #alert-error').fadeOut('slow');
        }, 5000);

        $('.btn-detail-muzakki').on('click', function () {
            const btn = $(this);
            $('#detail-kode').text(btn.data('kode') || '-');
            $('#detail-jenis').text(btn.data('jenis') || '-');
            $('#detail-nama').text(btn.data('nama') || '-');
            $('#detail-nik').text(btn.data('nik') || '-');
            $('#detail-nohp').text(btn.data('nohp') || '-');
            $('#detail-email').text(btn.data('email') || '-');
            $('#detail-pekerjaan').text(btn.data('pekerjaan') || '-');
            $('#detail-alamat').text(btn.data('alamat') || '-');
        });
    });
</script>