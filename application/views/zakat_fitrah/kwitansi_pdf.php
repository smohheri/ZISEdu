<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $tanggungan = isset($tanggungan) && is_array($tanggungan) ? $tanggungan : array(); ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<?php $namaMuzakkiTtd = isset($row->nama_muzakki) && trim((string) $row->nama_muzakki) !== '' ? $row->nama_muzakki : '-'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Kwitansi Zakat Fitrah</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9.5px;
            color: #000;
        }

        .receipt-wrap {
            width: 100%;
        }

        .kop-wrap {
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 70px;
            height: auto;
        }

        .text-center {
            text-align: center;
        }

        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            letter-spacing: .5px;
        }

        .meta {
            font-size: 10px;
            margin-top: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .info-table td {
            padding: 3px 2px;
            vertical-align: top;
        }

        .label-col {
            width: 180px;
        }

        .highlight {
            border: 1px solid #000;
            padding: 8px;
            margin: 10px 0 12px;
        }

        .highlight-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .highlight-value {
            font-size: 17px;
            font-weight: bold;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0 6px;
        }

        .tanggungan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .tanggungan-table th,
        .tanggungan-table td {
            border: 1px solid #000;
            padding: 4px;
        }

        .sign-wrap {
            width: 100%;
            margin-top: 24px;
            border-collapse: collapse;
        }

        .sign-wrap td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .sign-space {
            height: 40px;
        }
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
                            <?php echo html_escape(!empty($lembaga->nama_lembaga) ? $lembaga->nama_lembaga : 'ZISEDU'); ?>
                        </div>
                        <div><?php echo html_escape(!empty($lembaga->alamat) ? $lembaga->alamat : '-'); ?></div>
                        <div>
                            Telp: <?php echo html_escape(!empty($lembaga->no_telp) ? $lembaga->no_telp : '-'); ?> |
                            Email: <?php echo html_escape(!empty($lembaga->email) ? $lembaga->email : '-'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="text-center">
            <p class="receipt-title">KWITANSI PENERIMAAN ZAKAT FITRAH</p>
            <div class="meta">No. Kwitansi: <?php echo html_escape(isset($no_kwitansi) ? $no_kwitansi : '-'); ?></div>
            <div class="meta">No. Transaksi: <?php echo html_escape($row->nomor_transaksi); ?></div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; border-top: 1px solid #000; padding-top: 10px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 15px; border-right: 1px solid #000;">
                    <div class="section-title" style="margin-top: 0; color: #000;">INFORMASI TRANSAKSI</div>
                    <table class="info-table">
                        <tr>
                            <td class="label-col">Tanggal Bayar</td>
                            <td>: <?php echo html_escape(indo_date($row->tanggal_bayar)); ?></td>
                        </tr>
                        <tr>
                            <td class="label-col">Muzakki</td>
                            <td>:
                                <strong><?php echo html_escape($row->nama_muzakki); ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">Jumlah Jiwa</td>
                            <td>: <?php echo (int) $row->jumlah_jiwa; ?> jiwa</td>
                        </tr>
                        <tr>
                            <td class="label-col">Metode Tunaikan</td>
                            <td>: <?php echo ucfirst(html_escape($row->metode_tunaikan)); ?></td>
                        </tr>
                        <tr>
                            <td class="label-col">Metode Bayar</td>
                            <td>: <?php echo ucfirst(html_escape($row->metode_bayar)); ?></td>
                        </tr>
                        <tr>
                            <td class="label-col">Status</td>
                            <td>: <?php echo strtoupper(html_escape($row->status)); ?></td>
                        </tr>
                    </table>

                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                    <div class="section-title" style="margin-top: 0; color: #000;">RINCIAN TANGGUNGAN</div>
                    <?php if (!empty($tanggungan)): ?>
                        <table class="tanggungan-table" style="margin-top: 8px;">
                            <thead style="background-color: #f4f4f4;">
                                <tr>
                                    <th width="30">No</th>
                                    <th>Nama Anggota</th>
                                    <th>Hubungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($tanggungan as $anggota): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td><?php echo html_escape($anggota->nama_anggota); ?></td>
                                        <td><?php echo html_escape(!empty($anggota->hubungan_keluarga) ? $anggota->hubungan_keluarga : '-'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div style="text-align: center; color: #000; margin-top: 30px; font-style: italic;">
                            Tidak ada tanggungan tercatat
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="highlight" style="text-align: center; background-color: transparent; border: 1px solid #000; padding: 4px; margin-top: 5px; margin-bottom: 10px;">
            <div class="highlight-title" style="color: #000; font-size: 11px;">TOTAL NOMINAL DITERIMA</div>
            <div class="highlight-value" style="color: #000; margin-top: 2px; font-size: 14px; font-weight: bold;">
                <?php if ($row->metode_tunaikan === 'beras'): ?>
                    <?php echo number_format((float) $row->beras_kg, 2, ',', '.'); ?> Kg Beras
                <?php else: ?>
                    Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>
                <?php endif; ?>
            </div>
        </div>

        <table class="sign-wrap">
            <tr>
                <td>Muzakki,</td>
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