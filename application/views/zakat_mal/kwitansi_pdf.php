<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lembaga = isset($lembaga) ? $lembaga : NULL; ?>
<?php $namaPenerima = isset($nama_penerima) && trim((string) $nama_penerima) !== '' ? $nama_penerima : '-'; ?>
<?php $namaMuzakkiTtd = isset($row->nama_muzakki) && trim((string) $row->nama_muzakki) !== '' ? $row->nama_muzakki : '-'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Kwitansi Zakat Mal</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
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
            margin-top: 12px;
            margin-bottom: 12px;
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

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .detail-table th,
        .detail-table td {
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
            height: 56px;
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
            <p class="receipt-title">KWITANSI PENERIMAAN ZAKAT MAL</p>
            <div class="meta">No. Kwitansi: <?php echo html_escape(isset($no_kwitansi) ? $no_kwitansi : '-'); ?></div>
            <div class="meta">No. Transaksi: <?php echo html_escape($row->nomor_transaksi); ?></div>
        </div>

        <table class="info-table">
            <tr>
                <td class="label-col">Tanggal Hitung</td>
                <td>: <?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Bayar</td>
                <td>: <?php echo html_escape($row->tanggal_bayar ? indo_date($row->tanggal_bayar) : '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">Muzakki</td>
                <td>:
                    <?php echo html_escape(($row->kode_muzakki ? $row->kode_muzakki . ' - ' : '') . $row->nama_muzakki); ?>
                </td>
            </tr>
            <tr>
                <td class="label-col">Harta Bersih</td>
                <td>: Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td class="label-col">Nishab</td>
                <td>: Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td class="label-col">Persentase Zakat</td>
                <td>: <?php echo number_format((float) $row->persentase_zakat, 2, ',', '.'); ?>%</td>
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

        <div class="highlight">
            <div class="highlight-title">Total Zakat Diterima</div>
            <div class="highlight-value">Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></div>
        </div>

        <?php if (!empty($detail_rows)): ?>
            <div class="section-title">Rincian Harta</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Jenis Harta</th>
                        <th>Nilai Harta</th>
                        <th>Haul (bulan)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail_rows as $detail): ?>
                        <tr>
                            <td><?php echo html_escape(isset($jenis_harta_options[$detail->jenis_harta_id]) ? $jenis_harta_options[$detail->jenis_harta_id] : $detail->jenis_harta_id); ?>
                            </td>
                            <td>Rp <?php echo number_format((float) $detail->nilai_harta, 0, ',', '.'); ?></td>
                            <td><?php echo $detail->nilai_haul_bulan !== NULL ? (int) $detail->nilai_haul_bulan : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

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