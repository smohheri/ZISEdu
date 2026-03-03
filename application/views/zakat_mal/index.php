<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Transaksi Zakat Mal</h3>
        <a href="<?php echo site_url('zakat_mal/create'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Transaksi Baru</a>
    </div>
    <div class="card-body table-responsive p-0">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success m-3 mb-0"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger m-3 mb-0"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>No Transaksi</th>
                    <th>Muzakki</th>
                    <th>Tanggal Hitung</th>
                    <th>Harta Bersih</th>
                    <th>Nishab</th>
                    <th>Total Zakat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                        <td><?php echo html_escape(isset($row->nama_muzakki) ? $row->nama_muzakki : $row->muzakki_id); ?></td>
                        <td><?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
                        <td>Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></td>
                        <td><?php echo html_escape($row->status); ?></td>
                        <td>
                            <a class="btn btn-success btn-xs" target="_blank" href="<?php echo site_url('zakat_mal/kwitansi/' . $row->id); ?>">Kwitansi</a>
                            <a class="btn btn-warning btn-xs" href="<?php echo site_url('zakat_mal/edit/' . $row->id); ?>">Edit</a>
                            <a class="btn btn-info btn-xs" href="<?php echo site_url('zakat_mal/detail/' . $row->id); ?>">Detail</a>
                            <a class="btn btn-danger btn-xs" href="<?php echo site_url('zakat_mal/delete/' . $row->id); ?>" onclick="return confirm('Hapus data?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8" class="text-center text-muted">Belum ada transaksi zakat mal.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
