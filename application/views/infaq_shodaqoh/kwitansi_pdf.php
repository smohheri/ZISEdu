<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<?php $namaMuzakkiTtd = isset($row->nama_donatur) && trim((string) $row->nama_donatur) !== '' ? $row->nama_donatur : '-'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kwitansi <?php echo ucfirst(html_escape($row->jenis_dana)); ?></title>
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
        .info-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        .info-table td { padding: 5px 2px; vertical-align: top; }
        .label-col { width: 150px; }
        .highlight { border: 2px solid #000; padding: 15px; margin: 25px auto; text-align: center; width: 60%; font-size: 18px; font-weight: bold; }
        .sign-wrap { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .sign-wrap td { width: 50%; text-align: center; vertical-align: top; }
        .sign-space { height: 60px; }
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
            <p class="receipt-title">KWITANSI PENERIMAAN <?php echo strtoupper(html_escape($row->jenis_dana)); ?></p>
            <div class="meta">No. Kwitansi: <?php echo html_escape(isset($no_kwitansi) ? $no_kwitansi : '-'); ?></div>
            <div class="meta">No. Transaksi: <?php echo html_escape($row->nomor_transaksi); ?></div>
        </div>

        <table class="info-table">
            <tr>
                <td class="label-col">Telah terima dari</td>
                <td width="10">:</td>
                <td><strong><?php echo html_escape($row->nama_donatur); ?></strong></td>
            </tr>
            <tr>
                <td class="label-col">Pada Tanggal</td>
                <td>:</td>
                <td><?php echo html_escape(indo_date($row->tanggal_transaksi)); ?></td>
            </tr>
            <tr>
                <td class="label-col">Untuk Pembayaran</td>
                <td>:</td>
                <td><?php echo ucfirst(html_escape($row->jenis_dana)); ?> <?php echo html_escape($row->keterangan ? ' - ' . $row->keterangan : ''); ?></td>
            </tr>
            <tr>
                <td class="label-col">Metode Bayar</td>
                <td>:</td>
                <td><?php echo strtoupper(html_escape($row->metode_bayar)); ?></td>
            </tr>
            <tr>
                <td class="label-col">Status</td>
                <td>:</td>
                <td><?php echo strtoupper(html_escape($row->status)); ?></td>
            </tr>
        </table>

        <div class="highlight">
            Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>
        </div>

        <table class="sign-wrap">
            <tr>
                <td>Donatur,</td>
                <td>Penerima,</td>
            </tr>
            <tr>
                <td class="sign-space"></td>
                <td class="sign-space"></td>
            </tr>
            <tr>
                <td><strong>(<?php echo html_escape($namaMuzakkiTtd); ?>)</strong></td>
                <td><strong>(<?php echo html_escape($namaPenerima); ?>)</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
