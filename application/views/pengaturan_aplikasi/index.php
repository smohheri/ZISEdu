<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Pengaturan Aplikasi / Profil Lembaga</h3>
        <a href="<?php echo site_url('pengaturan_aplikasi/edit'); ?>" class="btn btn-primary btn-sm"><i
                class="fas fa-edit"></i> Edit</a>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if (empty($row)): ?>
            <div class="alert alert-warning mb-0">Belum ada data pengaturan aplikasi. Silakan klik tombol Edit untuk mengisi
                data lembaga.</div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th width="230">Kode Lembaga</th>
                            <td><?php echo html_escape($row->kode_lembaga); ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lembaga</th>
                            <td><?php echo html_escape($row->nama_lembaga); ?></td>
                        </tr>
                        <tr>
                            <th>Pimpinan</th>
                            <td><?php echo html_escape($row->nama_pimpinan ? $row->nama_pimpinan : '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Bendahara</th>
                            <td><?php echo html_escape($row->nama_bendahara ? $row->nama_bendahara : '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo nl2br(html_escape($row->alamat ? $row->alamat : '-')); ?></td>
                        </tr>
                        <tr>
                            <th>Kontak</th>
                            <td><?php echo html_escape($row->no_telp ? $row->no_telp : '-'); ?> /
                                <?php echo html_escape($row->no_hp ? $row->no_hp : '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo html_escape($row->email ? $row->email : '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td><?php echo html_escape($row->website ? $row->website : '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Rekening</th>
                            <td><?php echo html_escape(($row->rekening_bank ? $row->rekening_bank : '-') . ' - ' . ($row->rekening_nomor ? $row->rekening_nomor : '-') . ' a/n ' . ($row->rekening_atas_nama ? $row->rekening_atas_nama : '-')); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?php echo ((int) $row->aktif === 1) ? 'Aktif' : 'Nonaktif'; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="d-block font-weight-bold">Logo</label>
                        <?php if (!empty($row->logo_path)): ?>
                            <img src="<?php echo base_url($row->logo_path); ?>" alt="Logo" class="img-fluid img-thumbnail">
                        <?php else: ?>
                            <div class="text-muted">Belum ada logo.</div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="d-block font-weight-bold">Kop Surat</label>
                        <?php if (!empty($row->kop_path)): ?>
                            <img src="<?php echo base_url($row->kop_path); ?>" alt="Kop" class="img-fluid img-thumbnail">
                        <?php else: ?>
                            <div class="text-muted">Belum ada kop.</div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="d-block font-weight-bold">Stempel</label>
                        <?php if (!empty($row->stempel_path)): ?>
                            <img src="<?php echo base_url($row->stempel_path); ?>" alt="Stempel"
                                class="img-fluid img-thumbnail">
                        <?php else: ?>
                            <div class="text-muted">Belum ada stempel.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
