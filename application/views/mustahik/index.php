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
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3><?php echo isset($stats['total']) ? (int) $stats['total'] : 0; ?></h3>
                <p>Total Mustahik</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3><?php echo isset($stats['fakir']) ? (int) $stats['fakir'] : 0; ?></h3>
                <p>Asnaf Fakir</p>
            </div>
            <div class="icon">
                <i class="fas fa-hands"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3><?php echo isset($stats['miskin']) ? (int) $stats['miskin'] : 0; ?></h3>
                <p>Asnaf Miskin</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3><?php echo isset($stats['amil']) ? (int) $stats['amil'] : 0; ?></h3>
                <p>Asnaf Amil</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mustahik Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($stats['aktif']) ? (int) $stats['aktif'] : 0; ?></div>
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
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Mustahik Nonaktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($stats['nonaktif']) ? (int) $stats['nonaktif'] : 0; ?></div>
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
            <i class="fas fa-user-check mr-2"></i>Master Mustahik
        </h3>
        <a href="<?php echo site_url('mustahik/create'); ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Tambah Mustahik
        </a>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('mustahik'); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="q" class="form-control" placeholder="Cari kode, nama, asnaf, no hp..."
                            value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>Cari
                            </button>
                            <a href="<?php echo site_url('mustahik'); ?>" class="btn btn-default">
                                <i class="fas fa-sync-alt mr-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="mustahik-table" class="table table-sm table-bordered table-striped table-hover text-nowrap" style="width:100%">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori Asnaf</th>
                    <th>No HP</th>
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
                            <td><?php echo html_escape($row->kode_mustahik); ?></td>
                            <td><?php echo html_escape($row->nama); ?></td>
                            <td><span class="badge badge-info"><?php echo html_escape($row->kategori_asnaf); ?></span></td>
                            <td><?php echo html_escape($row->no_hp ? $row->no_hp : '-'); ?></td>
                            <td>
                                <?php if ((int) $row->aktif === 1): ?>
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                    <a class="btn btn-warning" href="<?php echo site_url('mustahik/edit/' . $row->id); ?>" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-danger" href="<?php echo site_url('mustahik/delete/' . $row->id); ?>"
                                        title="Hapus" onclick="return confirm('Hapus data?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data mustahik.</td>
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

        #mustahik-table {
            font-size: 0.85rem;
        }

        #mustahik-table th,
        #mustahik-table td {
            padding: 0.5rem 0.25rem;
        }

        .btn-sm {
            font-size: 0.72rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>

