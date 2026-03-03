<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Penyaluran Zakat</h3>
        <a href="<?php echo site_url('penyaluran/create'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Penyaluran Baru</a>
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
                    <th>No Penyaluran</th>
                    <th>Tanggal</th>
                    <th>Jenis Sumber</th>
                    <th>Total Uang</th>
                    <th>Total Beras (Kg)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo html_escape($row->nomor_penyaluran); ?></td>
                        <td><?php echo html_escape(indo_date($row->tanggal_penyaluran)); ?></td>
                        <td><?php echo html_escape($row->jenis_sumber); ?></td>
                        <td>Rp <?php echo number_format((float) $row->total_uang, 0, ',', '.'); ?></td>
                        <td><?php echo number_format((float) $row->total_beras_kg, 2, ',', '.'); ?></td>
                        <td><?php echo html_escape($row->status); ?></td>
                        <td>
                            <a class="btn btn-warning btn-xs" href="<?php echo site_url('penyaluran/edit/' . $row->id); ?>">Edit</a>
                            <a class="btn btn-info btn-xs" href="<?php echo site_url('penyaluran/detail/' . $row->id); ?>">Detail</a>
                            <a class="btn btn-danger btn-xs" href="<?php echo site_url('penyaluran/delete/' . $row->id); ?>" onclick="return confirm('Hapus data?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Belum ada data penyaluran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
