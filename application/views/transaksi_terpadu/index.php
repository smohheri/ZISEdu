<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$CI =& get_instance();
?>
<?php if ($CI->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" id="alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $CI->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($CI->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" id="alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $CI->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-layer-group"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Transaksi Terpadu</span>
                <span class="info-box-number"><?php echo isset($total_transaksi) ? number_format($total_transaksi, 0, ',', '.') : 0; ?></span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Muzakki / Donatur</span>
                <span class="info-box-number"><?php echo isset($total_muzakki) ? number_format($total_muzakki, 0, ',', '.') : 0; ?></span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-money-bill-wave" style="color:#fff;"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Uang Terhimpun</span>
                <span class="info-box-number">Rp <?php echo isset($total_nominal) ? number_format((float)$total_nominal, 0, ',', '.') : 0; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-olive elevation-1"><i class="fas fa-hand-holding-heart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Zakat Fitrah (<?php echo isset($count_fitrah) ? number_format($count_fitrah, 0, ',', '.') : 0; ?>x)</span>
                <span class="info-box-number">
                    Rp <?php echo isset($total_fitrah) ? number_format((float)$total_fitrah, 0, ',', '.') : 0; ?> 
                    <?php if (!empty($total_beras_fitrah)): ?>
                        / <?php echo number_format((float)$total_beras_fitrah, 2, ',', '.'); ?> Kg
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Zakat Mal (<?php echo isset($count_mal) ? number_format($count_mal, 0, ',', '.') : 0; ?>)</span>
                <span class="info-box-number">Rp <?php echo isset($total_mal) ? number_format((float)$total_mal, 0, ',', '.') : 0; ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-maroon elevation-1"><i class="fas fa-donate"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Infaq/Shodaqoh (<?php echo isset($count_infaq) ? number_format($count_infaq, 0, ',', '.') : 0; ?>)</span>
                <span class="info-box-number">Rp <?php echo isset($total_infaq) ? number_format((float)$total_infaq, 0, ',', '.') : 0; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-layer-group mr-2"></i>Daftar Transaksi Terpadu
        </h3>
        <a href="<?php echo site_url('transaksi_terpadu/create'); ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Transaksi Baru
        </a>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('transaksi_terpadu'); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="q" class="form-control" placeholder="Cari nomor transaksi, muzakki..."
                            value="<?php echo html_escape(isset($search) ? $search : ''); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>Cari
                            </button>
                            <a href="<?php echo site_url('transaksi_terpadu'); ?>" class="btn btn-default">
                                <i class="fas fa-sync-alt mr-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover text-nowrap" style="width:100%">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>No. Terpadu</th>
                    <th>Tanggal</th>
                    <th>Muzakki</th>
                    <th>Isi Transaksi</th>
                    <th width="140" class="text-center">Aksi</th>
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
                            <td><?php echo html_escape($row->nama_muzakki); ?></td>
                            <td>
                                <?php if ($row->fitrah_id > 0): ?>
                                    <span class="badge badge-success">Z. Fitrah</span>
                                <?php endif; ?>
                                <?php if ($row->mal_id > 0): ?>
                                    <span class="badge badge-info">Z. Mal</span>
                                <?php endif; ?>
                                <?php if ($row->infaq_id > 0): ?>
                                    <span class="badge badge-warning">Infaq/Shodaqoh</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                    <a class="btn btn-success" target="_blank" href="<?php echo site_url('transaksi_terpadu/kwitansi/' . $row->id); ?>" title="Cetak Kwitansi">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <!-- Optional: No Edit form for multi-transaction yet, user should edit individually -->
                                    <?php
                                    $calc_nominal = 0;
                                    $html_rincian = '';
                                    if (!empty($row->fitrah_id)) {
                                        $f = $CI->db->select('nomor_transaksi, keterangan, beras_kg, nominal_uang')->where('id', $row->fitrah_id)->get('zakat_fitrah')->row();
                                        if ($f) {
                                            $calc_nominal += (float) $f->nominal_uang;
                                            $html_rincian .= '<tr><td>Zakat Fitrah</td><td>' . html_escape($f->nomor_transaksi) . '</td><td>' . html_escape($f->keterangan) . '</td><td class="text-right">' . number_format((float)$f->beras_kg, 2, ',', '.') . '</td><td class="text-right">' . number_format((float)$f->nominal_uang, 0, ',', '.') . '</td></tr>';
                                        }
                                    }
                                    if (!empty($row->mal_id)) {
                                        $m = $CI->db->select('nomor_transaksi, keterangan, total_zakat')->where('id', $row->mal_id)->get('zakat_mal')->row();
                                        if ($m) {
                                            $calc_nominal += (float) $m->total_zakat;
                                            $html_rincian .= '<tr><td>Zakat Mal</td><td>' . html_escape($m->nomor_transaksi) . '</td><td>' . html_escape($m->keterangan) . '</td><td class="text-right">-</td><td class="text-right">' . number_format((float)$m->total_zakat, 0, ',', '.') . '</td></tr>';
                                        }
                                    }
                                    if (!empty($row->infaq_id)) {
                                        $i = $CI->db->select('nomor_transaksi, keterangan, nominal_uang')->where('id', $row->infaq_id)->get('infaq_shodaqoh')->row();
                                        if ($i) {
                                            $calc_nominal += (float) $i->nominal_uang;
                                            $html_rincian .= '<tr><td>Infaq/Shodaqoh</td><td>' . html_escape($i->nomor_transaksi) . '</td><td>' . html_escape($i->keterangan) . '</td><td class="text-right">-</td><td class="text-right">' . number_format((float)$i->nominal_uang, 0, ',', '.') . '</td></tr>';
                                        }
                                    }
                                    ?>
                                    <button type="button" class="btn btn-info btn-detail-terpadu" title="Detail"
                                        data-toggle="modal" data-target="#modal-detail-terpadu"
                                        data-nomor="<?php echo html_escape($row->nomor_transaksi); ?>"
                                        data-tanggal="<?php echo html_escape(indo_date($row->tanggal_transaksi)); ?>"
                                        data-donatur="<?php echo html_escape(isset($row->nama_muzakki) ? $row->nama_muzakki : (isset($row->nama_donatur) ? $row->nama_donatur : '-')); ?>"
                                        data-nominal="Rp <?php echo number_format($calc_nominal, 0, ',', '.'); ?>"
                                        data-rincian="<?php echo htmlspecialchars($html_rincian, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a class="btn btn-danger" href="<?php echo site_url('transaksi_terpadu/delete/' . $row->id); ?>"
                                        title="Hapus Terpadu" onclick="return confirm('Peringatan: Menghapus data terpadu ini juga akan menghapus data di Tabel Fitrah/Mal/Infaq terkait. Lanjutkan?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data transaksi terpadu.</td>
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

<div class="modal fade" id="modal-detail-terpadu" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi Terpadu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2"><strong>No Transaksi:</strong> <span id="detail-terpadu-nomor">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Tanggal:</strong> <span id="detail-terpadu-tanggal">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Donatur:</strong> <span id="detail-terpadu-donatur">-</span></div>
                    <div class="col-md-6 mb-2"><strong>Total Nominal:</strong> <span id="detail-terpadu-nominal">-</span></div>
                </div>
                <hr>
                <h6>Rincian Transaksi</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Jenis Transaksi</th>
                                <th>No Transaksi</th>
                                <th>Keterangan</th>
                                <th class="text-right">Beras (Kg)</th>
                                <th class="text-right">Nominal Uang</th>
                            </tr>
                        </thead>
                        <tbody id="detail-terpadu-tbody">
                            <tr><td colspan="5" class="text-center text-muted">Memuat baris...</td></tr>
                        </tbody>
                    </table>
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

        $('.btn-detail-terpadu').on('click', function () {
            const btn = $(this);
            $('#detail-terpadu-nomor').text(btn.data('nomor') || '-');
            $('#detail-terpadu-tanggal').text(btn.data('tanggal') || '-');
            $('#detail-terpadu-donatur').text(btn.data('donatur') || '-');
            $('#detail-terpadu-nominal').text(btn.data('nominal') || '-');
            
            const rincianHtml = btn.data('rincian');
            if (rincianHtml) {
                $('#detail-terpadu-tbody').html(rincianHtml);
            } else {
                $('#detail-terpadu-tbody').html('<tr><td colspan="5" class="text-center text-muted">Tidak ada rincian transaksi</td></tr>');
            }
        });
    });
</script>
