<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Tanggungan Zakat Fitrah</h3>
        <a href="<?php echo site_url('tanggungan_fitrah/create'); ?>" class="btn btn-primary btn-sm"><i
                class="fas fa-plus"></i> Tambah Tanggungan</a>
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
                    <th>Muzakki</th>
                    <th>Nama Anggota</th>
                    <th>Hubungan</th>
                    <th>Aktif Dihitung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)):
                    foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo html_escape(isset($row->nama_muzakki) ? $row->nama_muzakki : $row->muzakki_id); ?>
                            </td>
                            <td><?php echo html_escape($row->nama_anggota); ?></td>
                            <td><?php echo html_escape($row->hubungan_keluarga); ?></td>
                            <td><?php echo ((int) $row->aktif_dihitung === 1) ? 'Ya' : 'Tidak'; ?></td>
                            <td>
                                <a class="btn btn-warning btn-xs"
                                    href="<?php echo site_url('tanggungan_fitrah/edit/' . $row->id); ?>">Edit</a>
                                <a class="btn btn-danger btn-xs"
                                    href="<?php echo site_url('tanggungan_fitrah/delete/' . $row->id); ?>"
                                    onclick="return confirm('Hapus data?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data tanggungan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
