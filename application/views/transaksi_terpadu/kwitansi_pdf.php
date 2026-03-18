<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kwitansi Terpadu</title>
    <style>
        body { font-family: sans-serif; font-size: 9.5px; color: #000; }
        .receipt-wrap { width: 100%; }
        .kop-wrap { border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-table td { vertical-align: middle; }
        .kop-logo { width: 70px; height: auto; }
        .text-center { text-align: center; }
        .receipt-title { font-size: 16px; font-weight: bold; margin: 0; letter-spacing: .5px; }
        .meta { font-size: 10px; margin-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 11px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 5px; }
        .items-table th { background-color: #f4f4f4; }
        .highlight { border: 1px solid #000; padding: 8px; margin: 15px 0; text-align: center; }
        .highlight-title { font-size: 12px; font-weight: bold; margin-bottom: 4px; }
        .highlight-value { font-size: 14px; font-weight: bold; }
        .sign-wrap { width: 100%; margin-top: 24px; border-collapse: collapse; }
        .sign-wrap td { width: 50%; text-align: center; vertical-align: top; }
        .sign-space { height: 40px; }
    </style>
</head>
<body>
    <div class="receipt-wrap">
        <div class="kop-wrap">
            <table class="kop-table">
                <tr>
                    <td width="90" class="text-center">
                        <?php if (!empty($logo_image_src)): ?>
                            <img src="<?php echo $logo_image_src; ?>" alt="Logo" class="kop-logo">
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div style="font-size:18px;font-weight:bold;line-height:1.2;">
                            <?php echo html_escape(!empty($lembaga) && !empty($lembaga->nama_lembaga) ? $lembaga->nama_lembaga : 'ZISEDU'); ?>
                        </div>
                        <div><?php echo html_escape(!empty($lembaga) && !empty($lembaga->alamat) ? $lembaga->alamat : '-'); ?></div>
                        <div>
                            Telp: <?php echo html_escape(!empty($lembaga) && !empty($lembaga->no_telp) ? $lembaga->no_telp : '-'); ?> |
                            Email: <?php echo html_escape(!empty($lembaga) && !empty($lembaga->email) ? $lembaga->email : '-'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="text-center">
            <p class="receipt-title">KWITANSI PENERIMAAN TERPADU</p>
            <div class="meta">No. Kwitansi: <?php echo html_escape(isset($no_kwitansi_terpadu) ? $no_kwitansi_terpadu : '-'); ?></div>
        </div>

        <div style="margin-top: 15px;">
            <p style="margin: 0 0 5px 0;">Telah terima dari: <strong><?php echo html_escape($nama_muzakki_ttd); ?></strong></p>
            <p style="margin: 0 0 5px 0;">Untuk pembayaran:</p>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Jenis Transaksi</th>
                        <th>No Transaksi</th>
                        <th>Keterangan</th>
                        <th style="text-align: right;">Beras (Kg)</th>
                        <th style="text-align: right;">Nominal Uang</th>
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
                        <td style="text-align: right;"><?php echo number_format((float)$row_fitrah->beras_kg, 2, ',', '.'); ?></td>
                        <td style="text-align: right;"><?php echo number_format((float)$row_fitrah->nominal_uang, 0, ',', '.'); ?></td>
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
                        <td style="text-align: right;">0,00</td>
                        <td style="text-align: right;"><?php echo number_format((float)$row_mal->total_zakat, 0, ',', '.'); ?></td>
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
                        <td style="text-align: right;">0,00</td>
                        <td style="text-align: right;"><?php echo number_format((float)$row_infaq->nominal_uang, 0, ',', '.'); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="highlight">
            <div class="highlight-title">TOTAL NOMINAL DITERIMA</div>
            <div class="highlight-value">
                <?php echo number_format($total_beras, 2, ',', '.'); ?> Kg Beras
                &nbsp;|&nbsp;
                Rp <?php echo number_format($total_uang, 0, ',', '.'); ?>
            </div>
        </div>

        <table class="sign-wrap">
            <tr>
                <td>Muzakki / Donatur,</td>
                <td>Penerima,</td>
            </tr>
            <tr>
                <td class="sign-space"></td>
                <td class="sign-space"></td>
            </tr>
            <tr>
                <td><strong>(<?php echo html_escape($nama_muzakki_ttd); ?>)</strong></td>
                <td><strong>(<?php echo html_escape($namaPenerima); ?>)</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
