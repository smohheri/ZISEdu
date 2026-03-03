<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Master Jenis Harta Zakat Mal</h3>
        <a href="<?php echo site_url('jenis_harta_mal/create'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Jenis</a>
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
                    <th>Kode</th>
                    <th>Nama Jenis</th>
                    <th>Tarif (%)</th>
                    <th>Butuh Haul</th>
                    <th>Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo html_escape($row->kode_jenis); ?></td>
                        <td><?php echo html_escape($row->nama_jenis); ?></td>
                        <td><?php echo html_escape($row->tarif_persen); ?></td>
                        <td><?php echo ((int) $row->butuh_haul === 1) ? 'Ya' : 'Tidak'; ?></td>
                        <td><?php echo ((int) $row->aktif === 1) ? 'Aktif' : 'Nonaktif'; ?></td>
                        <td>
                            <a class="btn btn-warning btn-xs" href="<?php echo site_url('jenis_harta_mal/edit/' . $row->id); ?>">Edit</a>
                            <a class="btn btn-danger btn-xs" href="<?php echo site_url('jenis_harta_mal/delete/' . $row->id); ?>" onclick="return confirm('Hapus data?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data jenis harta.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
