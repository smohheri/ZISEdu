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
                <p>Total Transaksi</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
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
    <div class="col-lg-3 col-6">
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
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>Rp <?php echo number_format((float) (isset($stats['total_nominal']) ? $stats['total_nominal'] : 0), 0, ',', '.'); ?></h3>
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
                        <input type="text" name="q" class="form-control" placeholder="Cari nomor, donatur, jenis, status..."
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

        <table class="table table-sm table-bordered table-striped table-hover">
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
