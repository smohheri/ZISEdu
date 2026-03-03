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
                <p>Total Penyaluran</p>
            </div>
            <div class="icon">
                <i class="fas fa-donate"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>Rp <?php echo number_format(isset($stats['total_uang']) ? (float) $stats['total_uang'] : 0, 0, ',', '.'); ?></h3>
                <p>Total Uang Disalurkan</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3><?php echo number_format(isset($stats['total_beras']) ? (float) $stats['total_beras'] : 0, 2, ',', '.'); ?> Kg</h3>
                <p>Total Beras</p>
            </div>
            <div class="icon">
                <i class="fas fa-seedling"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3><?php echo isset($stats['disalurkan']) ? (int) $stats['disalurkan'] : 0; ?></h3>
                <p>Status Disalurkan</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Transaksi Disalurkan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($stats['disalurkan']) ? (int) $stats['disalurkan'] : 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Draft / Batal</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo ((int) (isset($stats['draft']) ? $stats['draft'] : 0)) + ((int) (isset($stats['batal']) ? $stats['batal'] : 0)); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-hand-holding-heart mr-2"></i>Penyaluran Zakat
        </h3>
        <a href="<?php echo site_url('penyaluran/create'); ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Penyaluran Baru
        </a>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('penyaluran'); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="q" class="form-control" placeholder="Cari nomor, sumber, status..."
                            value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>Cari
                            </button>
                            <a href="<?php echo site_url('penyaluran'); ?>" class="btn btn-default">
                                <i class="fas fa-sync-alt mr-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <table id="penyaluran-table" class="table table-sm table-bordered table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>No Penyaluran</th>
                    <th>Tanggal</th>
                    <th>Jenis Sumber</th>
                    <th>Total Uang</th>
                    <th>Total Beras (Kg)</th>
                    <th>Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo html_escape($row->nomor_penyaluran); ?></td>
                            <td><?php echo html_escape(indo_date($row->tanggal_penyaluran)); ?></td>
                            <td><?php echo html_escape($row->jenis_sumber); ?></td>
                            <td>Rp <?php echo number_format((float) $row->total_uang, 0, ',', '.'); ?></td>
                            <td><?php echo number_format((float) $row->total_beras_kg, 2, ',', '.'); ?></td>
                            <td>
                                <?php if ($row->status === 'disalurkan'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Disalurkan</span>
                                <?php elseif ($row->status === 'draft'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-pencil-alt mr-1"></i>Draft</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Batal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                    <a class="btn btn-warning" href="<?php echo site_url('penyaluran/edit/' . $row->id); ?>" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-info" href="<?php echo site_url('penyaluran/detail/' . $row->id); ?>" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-danger" href="<?php echo site_url('penyaluran/delete/' . $row->id); ?>"
                                        title="Hapus" onclick="return confirm('Hapus data?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data penyaluran.</td>
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

        #penyaluran-table {
            font-size: 0.85rem;
        }

        #penyaluran-table th,
        #penyaluran-table td {
            padding: 0.5rem 0.25rem;
        }

        .btn-sm {
            font-size: 0.72rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>

