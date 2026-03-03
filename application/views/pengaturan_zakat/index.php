<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Pengaturan Zakat Tahunan</h3>
        <a href="<?php echo site_url('pengaturan_zakat/create'); ?>" class="btn btn-primary btn-sm"><i
                class="fas fa-plus"></i> Tambah</a>
    </div>
    <div class="card-body table-responsive p-0">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success m-3 mb-0"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger m-3 mb-0"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Fitrah/Jiwa (Kg)</th>
                    <th>Fitrah/Jiwa (Rp)</th>
                    <th>Harga Beras/Kg</th>
                    <th>Nilai Emas/Gram</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)):
                    foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo html_escape($row->tahun); ?></td>
                            <td><?php echo html_escape($row->fitrah_per_jiwa_kg); ?></td>
                            <td><?php echo number_format((float) $row->fitrah_per_jiwa_rupiah, 0, ',', '.'); ?></td>
                            <td><?php echo number_format((float) $row->harga_beras_per_kg, 0, ',', '.'); ?></td>
                            <td><?php echo number_format((float) $row->nilai_emas_per_gram, 0, ',', '.'); ?></td>
                            <td>
                                <a class="btn btn-warning btn-xs"
                                    href="<?php echo site_url('pengaturan_zakat/edit/' . $row->id); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data pengaturan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
