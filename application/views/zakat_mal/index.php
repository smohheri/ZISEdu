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
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>Rp
                    <?php echo number_format(isset($stats['total_harta_bersih']) ? (float) $stats['total_harta_bersih'] : 0, 0, ',', '.'); ?>
                </h3>
                <p>Total Harta Bersih</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>Rp
                    <?php echo number_format(isset($stats['total_zakat']) ? (float) $stats['total_zakat'] : 0, 0, ',', '.'); ?>
                </h3>
                <p>Total Zakat</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3><?php echo isset($stats['lunas']) ? (int) $stats['lunas'] : 0; ?></h3>
                <p>Status Lunas</p>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Transaksi Lunas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo isset($stats['lunas']) ? (int) $stats['lunas'] : 0; ?></div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo ((int) (isset($stats['draft']) ? $stats['draft'] : 0)) + ((int) (isset($stats['batal']) ? $stats['batal'] : 0)); ?>
                        </div>
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
            <i class="fas fa-coins mr-2"></i>Transaksi Zakat Mal
        </h3>
        <a href="<?php echo site_url('zakat_mal/create'); ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Transaksi Baru
        </a>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('zakat_mal'); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="q" class="form-control" placeholder="Cari nomor, muzakki, status..."
                            value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>Cari
                            </button>
                            <a href="<?php echo site_url('zakat_mal'); ?>" class="btn btn-default">
                                <i class="fas fa-sync-alt mr-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="zakat-mal-table" class="table table-sm table-bordered table-striped table-hover text-nowrap" style="width:100%">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>No Transaksi</th>
                    <th>Muzakki</th>
                    <th>Tanggal Hitung</th>
                    <th>Harta Bersih</th>
                    <th>Nishab</th>
                    <th>Total Zakat</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php $no = isset($paging['offset']) ? ((int) $paging['offset'] + 1) : 1; ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                            <td><?php echo html_escape(isset($row->nama_muzakki) ? $row->nama_muzakki : $row->muzakki_id); ?>
                            </td>
                            <td><?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
                            <td>Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($row->status === 'lunas'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Lunas</span>
                                <?php elseif ($row->status === 'draft'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-pencil-alt mr-1"></i>Draft</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Batal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                    <a class="btn btn-success" target="_blank"
                                        href="<?php echo site_url('zakat_mal/kwitansi/' . $row->id); ?>" title="Kwitansi">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a class="btn btn-warning" href="<?php echo site_url('zakat_mal/edit/' . $row->id); ?>"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-info btn-detail-zakat-mal" title="Detail"
                                        data-toggle="modal" data-target="#modal-detail-zakat-mal"
                                        data-nomor="<?php echo html_escape($row->nomor_transaksi); ?>"
                                        data-muzakki="<?php echo html_escape(isset($row->nama_muzakki) ? $row->nama_muzakki : $row->muzakki_id); ?>"
                                        data-tanggal-hitung="<?php echo html_escape(indo_date($row->tanggal_hitung)); ?>"
                                        data-tanggal-bayar="<?php echo html_escape(!empty($row->tanggal_bayar) ? indo_date($row->tanggal_bayar) : '-'); ?>"
                                        data-total-harta="Rp <?php echo number_format((float) $row->total_harta, 0, ',', '.'); ?>"
                                        data-total-hutang="Rp <?php echo number_format((float) $row->total_hutang_jatuh_tempo, 0, ',', '.'); ?>"
                                        data-harta-bersih="Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?>"
                                        data-nishab="Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?>"
                                        data-persentase="<?php echo number_format((float) $row->persentase_zakat, 2, ',', '.'); ?>%"
                                        data-total-zakat="Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?>"
                                        data-metode-bayar="<?php echo html_escape(ucfirst($row->metode_bayar)); ?>"
                                        data-status="<?php echo html_escape(strtoupper($row->status)); ?>"
                                        data-keterangan="<?php echo html_escape(!empty($row->keterangan) ? $row->keterangan : '-'); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a class="btn btn-danger" href="<?php echo site_url('zakat_mal/delete/' . $row->id); ?>"
                                        title="Hapus" onclick="return confirm('Hapus data?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada transaksi zakat mal.</td>
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

<div class="modal fade" id="modal-detail-zakat-mal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Zakat Mal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2"><strong>No Transaksi:</strong> <span id="detail-nomor">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Muzakki:</strong> <span id="detail-muzakki">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Tanggal Hitung:</strong> <span
                            id="detail-tanggal-hitung">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Tanggal Bayar:</strong> <span id="detail-tanggal-bayar">-</span>
                    </div>
                    <div class="col-md-6 mb-2"><strong>Total Harta:</strong> <span id="detail-total-harta">-</span>
                    </div>
                    <div class="col-md-6 mb-2"><strong>Total Hutang:</strong> <span id="detail-total-hutang">-</span>
                    </div>
                    <div class="col-md-6 mb-2"><strong>Harta Bersih:</strong> <span id="detail-harta-bersih">-</span>
                    </div>
                    <div class="col-md-6 mb-2"><strong>Nishab:</strong> <span id="detail-nishab">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Persentase:</strong> <span id="detail-persentase">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Total Zakat:</strong> <span id="detail-total-zakat">-</span>
                    </div>
                    <div class="col-md-6 mb-2"><strong>Metode Bayar:</strong> <span id="detail-metode-bayar">-</span>
                    </div>
                    <div class="col-md-6 mb-2"><strong>Status:</strong> <span id="detail-status">-</span></div>
                    <div class="col-md-12 mb-2"><strong>Keterangan:</strong><br><span id="detail-keterangan">-</span>
                    </div>
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

        $('.btn-detail-zakat-mal').on('click', function () {
            const btn = $(this);
            $('#detail-nomor').text(btn.data('nomor') || '-');
            $('#detail-muzakki').text(btn.data('muzakki') || '-');
            $('#detail-tanggal-hitung').text(btn.data('tanggal-hitung') || '-');
            $('#detail-tanggal-bayar').text(btn.data('tanggal-bayar') || '-');
            $('#detail-total-harta').text(btn.data('total-harta') || '-');
            $('#detail-total-hutang').text(btn.data('total-hutang') || '-');
            $('#detail-harta-bersih').text(btn.data('harta-bersih') || '-');
            $('#detail-nishab').text(btn.data('nishab') || '-');
            $('#detail-persentase').text(btn.data('persentase') || '-');
            $('#detail-total-zakat').text(btn.data('total-zakat') || '-');
            $('#detail-metode-bayar').text(btn.data('metode-bayar') || '-');
            $('#detail-status').text(btn.data('status') || '-');
            $('#detail-keterangan').text(btn.data('keterangan') || '-');
        });
    });
</script>