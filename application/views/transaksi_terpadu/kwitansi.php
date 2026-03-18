<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title : 'Kwitansi Terpadu'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('asset/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/adminlte/dist/css/adminlte.min.css'); ?>">
    <style>
        body { background: #f4f6f9; }
        .receipt-wrap { max-width: 860px; margin: 24px auto; }
        .receipt-title { letter-spacing: 1px; }
        .kop-wrap { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .kop-img { max-height: 95px; width: auto; }
        .kop-title { font-size: 21px; font-weight: 700; line-height: 1.2; }
        @page { size: 210mm 139mm; margin: 0; }
        @media print {
            html, body {
                width: 210mm; height: 139mm; max-width: 210mm;
                max-height: 139mm; overflow: hidden; margin: 0; padding: 0; font-size: 12px;
            }
            body { background: #fff; }
            .no-print { display: none !important; }
            .card { border: 1px solid #000 !important; box-shadow: none !important; margin: 0 !important; }
            .receipt-wrap { width: 100%; height: 100%; margin: 0; padding: 8mm 10mm; box-sizing: border-box; max-width: 100%; }
            .card-body { padding: 12px; }
            * { color: #000 !important; }
            .receipt-cols, .receipt-cols table { font-size: 14px !important; }
        }
    </style>
</head>
<body>
    <div class="receipt-wrap">
        <div class="mb-3 no-print text-right">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
            <?php 
            $pdf_url = isset($row) ? site_url('transaksi_terpadu/export_pdf/' . $row->id) : '#';
            ?>
            <a href="<?php echo $pdf_url; ?>" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?php echo site_url('transaksi_terpadu'); ?>" class="btn btn-default">Kembali</a>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="kop-wrap">
                    <div class="row align-items-center">
                        <div class="col-2 text-center">
                            <?php if (!empty($lembaga) && !empty($lembaga->logo_path)): ?>
                                <img src="<?php echo base_url($lembaga->logo_path); ?>" alt="Logo" class="kop-img">
                            <?php endif; ?>
                        </div>
                        <div class="col-10 text-center">
                            <div class="kop-title">
                                <?php echo html_escape(!empty($lembaga) && !empty($lembaga->nama_lembaga) ? $lembaga->nama_lembaga : 'ZISEDU'); ?>
                            </div>
                            <div><?php echo html_escape(!empty($lembaga) && !empty($lembaga->alamat) ? $lembaga->alamat : '-'); ?></div>
                            <div>
                                Telp: <?php echo html_escape(!empty($lembaga) && !empty($lembaga->no_telp) ? $lembaga->no_telp : '-'); ?> |
                                Email: <?php echo html_escape(!empty($lembaga) && !empty($lembaga->email) ? $lembaga->email : '-'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <h3 class="receipt-title mb-0"><strong>KWITANSI PENERIMAAN TERPADU</strong></h3>
                    <small>No. Kwitansi: <?php echo html_escape(isset($no_kwitansi_terpadu) ? $no_kwitansi_terpadu : '-'); ?></small>
                </div>

                <div class="row pt-3 border-top receipt-cols">
                    <div class="col-12">
                        <p class="mb-2">Telah terima dari: <strong><?php echo html_escape($nama_muzakki_ttd); ?></strong></p>
                        <p class="mb-2">Untuk pembayaran:</p>
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
                            <tbody>
                                <?php 
                                $total_beras = 0;
                                $total_uang = 0;
                                
                                if($row_fitrah): 
                                    $total_beras += (float)$row_fitrah->beras_kg;
                                    $total_uang += (float)$row_fitrah->nominal_uang;
                                ?>
                                <tr>
                                    <td>Zakat Fitrah</td>
                                    <td><?php echo html_escape($row_fitrah->nomor_transaksi); ?></td>
                                    <td><?php echo (int)$row_fitrah->jumlah_jiwa; ?> jiwa. <?php echo html_escape($row_fitrah->keterangan); ?></td>
                                    <td class="text-right"><?php echo number_format((float)$row_fitrah->beras_kg, 2, ',', '.'); ?></td>
                                    <td class="text-right"><?php echo number_format((float)$row_fitrah->nominal_uang, 0, ',', '.'); ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php 
                                if($row_mal): 
                                    $total_uang += (float)$row_mal->total_zakat;
                                ?>
                                <tr>
                                    <td>Zakat Mal</td>
                                    <td><?php echo html_escape($row_mal->nomor_transaksi); ?></td>
                                    <td><?php echo html_escape($row_mal->keterangan); ?></td>
                                    <td class="text-right">0,00</td>
                                    <td class="text-right"><?php echo number_format((float)$row_mal->total_zakat, 0, ',', '.'); ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php 
                                if($row_infaq): 
                                    $total_uang += (float)$row_infaq->nominal_uang;
                                ?>
                                <tr>
                                    <td>Infaq / Shodaqoh</td>
                                    <td><?php echo html_escape($row_infaq->nomor_transaksi); ?></td>
                                    <td><?php echo ucfirst(html_escape($row_infaq->jenis_dana)); ?>. <?php echo html_escape($row_infaq->keterangan); ?></td>
                                    <td class="text-right">0,00</td>
                                    <td class="text-right"><?php echo number_format((float)$row_infaq->nominal_uang, 0, ',', '.'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-center">TOTAL DITERIMA</td>
                                    <td class="text-right">
                                        <?php echo number_format($total_beras, 2, ',', '.'); ?> Kg
                                    </td>
                                    <td class="text-right">
                                        Rp <?php echo number_format($total_uang, 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6 text-center">
                        <p class="mb-5">Muzakki / Donatur,</p>
                        <p><strong>(<?php echo html_escape($nama_muzakki_ttd); ?>)</strong></p>
                    </div>
                    <div class="col-6 text-center">
                        <p class="mb-5">Penerima,</p>
                        <p><strong>(<?php echo html_escape($namaPenerima); ?>)</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('beforeprint', function () {
            var card = document.querySelector('.card');
            card.style.transform = 'none';
            card.style.transformOrigin = 'top center';
            
            var dummy = document.createElement('div');
            dummy.style.position = 'absolute';
            dummy.style.height = '123mm'; 
            document.body.appendChild(dummy);
            var maxHeight = dummy.offsetHeight;
            document.body.removeChild(dummy);

            var actualHeight = card.scrollHeight;

            if (actualHeight > maxHeight) {
                var scale = maxHeight / actualHeight;
                card.style.transform = 'scale(' + (scale - 0.01) + ')';
            }
        });

        window.addEventListener('afterprint', function () {
            document.querySelector('.card').style.transform = 'none';
        });
    </script>
</body>
</html>
