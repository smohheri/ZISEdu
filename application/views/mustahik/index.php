<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Master Mustahik</h3>
        <a href="<?php echo site_url('mustahik/create'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
            Tambah Mustahik</a>
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
                    <th>Nama</th>
                    <th>Kategori Asnaf</th>
                    <th>No HP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)):
                    foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo html_escape($row->kode_mustahik); ?></td>
                            <td><?php echo html_escape($row->nama); ?></td>
                            <td><span class="badge badge-info"><?php echo html_escape($row->kategori_asnaf); ?></span></td>
                            <td><?php echo html_escape($row->no_hp); ?></td>
                            <td><?php echo ((int) $row->aktif === 1) ? 'Aktif' : 'Nonaktif'; ?></td>
                            <td>
                                <a class="btn btn-warning btn-xs"
                                    href="<?php echo site_url('mustahik/edit/' . $row->id); ?>">Edit</a>
                                <a class="btn btn-danger btn-xs" href="<?php echo site_url('mustahik/delete/' . $row->id); ?>"
                                    onclick="return confirm('Hapus data?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data mustahik.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
