<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$is_data_ready = !empty($row);
$status_label = $is_data_ready ? (((int) $row->aktif === 1) ? 'Aktif' : 'Nonaktif') : '-';
$status_badge = $is_data_ready ? (((int) $row->aktif === 1) ? 'success' : 'danger') : 'secondary';
?>

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
                <h3><?php echo $is_data_ready ? 1 : 0; ?></h3>
                <p>Profil Lembaga</p>
            </div>
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3><?php echo $is_data_ready && $row->kode_lembaga ? html_escape($row->kode_lembaga) : '-'; ?></h3>
                <p>Kode Lembaga</p>
            </div>
            <div class="icon">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3><?php echo $is_data_ready && $row->nama_pimpinan ? 1 : 0; ?></h3>
                <p>Data Pimpinan</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3><?php echo $status_label; ?></h3>
                <p>Status Aplikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-toggle-on"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-cogs mr-2"></i>Pengaturan Aplikasi / Profil Lembaga
        </h3>
        <a href="<?php echo site_url('pengaturan_aplikasi/edit'); ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
    <div class="card-body">
        <?php if (!$is_data_ready): ?>
            <div class="alert alert-warning mb-0">
                Belum ada data pengaturan aplikasi. Silakan klik tombol Edit untuk mengisi data lembaga.
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-sm table-bordered table-striped mb-0">
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
                            <td>
                                <?php echo html_escape(($row->rekening_bank ? $row->rekening_bank : '-') . ' - ' . ($row->rekening_nomor ? $row->rekening_nomor : '-') . ' a/n ' . ($row->rekening_atas_nama ? $row->rekening_atas_nama : '-')); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><span class="badge badge-<?php echo $status_badge; ?>"><?php echo $status_label; ?></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="card card-outline card-primary mb-3">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0">Logo</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php if (!empty($row->logo_path)): ?>
                                <img src="<?php echo base_url($row->logo_path); ?>" alt="Logo" class="img-fluid img-thumbnail media-preview">
                            <?php else: ?>
                                <div class="text-muted">Belum ada logo.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card card-outline card-info mb-3">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0">Kop Surat</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php if (!empty($row->kop_path)): ?>
                                <img src="<?php echo base_url($row->kop_path); ?>" alt="Kop" class="img-fluid img-thumbnail media-preview">
                            <?php else: ?>
                                <div class="text-muted">Belum ada kop.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card card-outline card-secondary mb-0">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0">Stempel</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php if (!empty($row->stempel_path)): ?>
                                <img src="<?php echo base_url($row->stempel_path); ?>" alt="Stempel" class="img-fluid img-thumbnail media-preview">
                            <?php else: ?>
                                <div class="text-muted">Belum ada stempel.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#alert-success, #alert-error').fadeOut('slow');
        }, 5000);
    });
</script>

<style>
    .small-box {
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem 0 rgba(58, 59, 69, 0.15);
    }

    .small-box .icon {
        top: 10px;
        right: 15px;
        z-index: 0;
    }

    .media-preview {
        max-height: 160px;
        width: auto;
    }

    @media screen and (max-width: 768px) {
        .small-box h3 {
            font-size: 1.2rem;
        }

        .small-box p {
            font-size: 0.8rem;
        }
    }
</style>

