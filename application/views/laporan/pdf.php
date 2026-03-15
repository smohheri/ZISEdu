<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$lembaga = isset($lembaga) ? $lembaga : NULL;
$ringkasan = isset($ringkasan) && is_array($ringkasan) ? $ringkasan : array();
$fitrahUang = isset($ringkasan['fitrah_uang']) ? (float) $ringkasan['fitrah_uang'] : 0;
$fitrahBeras = isset($ringkasan['fitrah_beras']) ? (float) $ringkasan['fitrah_beras'] : 0;
$malUang = isset($ringkasan['mal_uang']) ? (float) $ringkasan['mal_uang'] : 0;
$penyaluranUang = isset($ringkasan['penyaluran_uang']) ? (float) $ringkasan['penyaluran_uang'] : 0;
$penyaluranBeras = isset($ringkasan['penyaluran_beras']) ? (float) $ringkasan['penyaluran_beras'] : 0;
$infaqUang = isset($ringkasan['infaq_shodaqoh_uang']) ? (float) $ringkasan['infaq_shodaqoh_uang'] : 0;
$logoImageSrc = isset($logo_image_src) ? $logo_image_src : NULL;
$kopImageSrc = isset($kop_image_src) ? $kop_image_src : NULL;

$namaLembaga = ($lembaga && !empty($lembaga->nama_lembaga)) ? $lembaga->nama_lembaga : 'Lembaga Zakat';
$alamatParts = array();
if ($lembaga) {
    if (!empty($lembaga->alamat)) $alamatParts[] = $lembaga->alamat;
    if (!empty($lembaga->kelurahan)) $alamatParts[] = 'Kel. ' . $lembaga->kelurahan;
    if (!empty($lembaga->kecamatan)) $alamatParts[] = 'Kec. ' . $lembaga->kecamatan;
    if (!empty($lembaga->kota_kabupaten)) $alamatParts[] = $lembaga->kota_kabupaten;
    if (!empty($lembaga->provinsi)) $alamatParts[] = $lembaga->provinsi;
    if (!empty($lembaga->kode_pos)) $alamatParts[] = $lembaga->kode_pos;
}
$alamatLembaga = !empty($alamatParts) ? implode(', ', $alamatParts) : '-';

$kontakParts = array();
if ($lembaga) {
    if (!empty($lembaga->no_telp)) $kontakParts[] = 'Telp: ' . $lembaga->no_telp;
    if (!empty($lembaga->no_hp)) $kontakParts[] = 'HP: ' . $lembaga->no_hp;
    if (!empty($lembaga->email)) $kontakParts[] = 'Email: ' . $lembaga->email;
    if (!empty($lembaga->website)) $kontakParts[] = 'Web: ' . $lembaga->website;
}
$kontakLembaga = !empty($kontakParts) ? implode(' | ', $kontakParts) : '-';

$pimpinan = ($lembaga && !empty($lembaga->nama_pimpinan)) ? $lembaga->nama_pimpinan : '..........................';
$bendahara = ($lembaga && !empty($lembaga->nama_bendahara)) ? $lembaga->nama_bendahara : '..........................';
$kota = ($lembaga && !empty($lembaga->kota_kabupaten)) ? $lembaga->kota_kabupaten : '..........';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Zakat - <?php echo html_escape($namaLembaga); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .kop { border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .kop-image-wrap { text-align: center; margin-bottom: 6px; }
        .kop-image { max-height: 120px; width: auto; }
        .kop-text { text-align: center; }
        .kop-title { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .kop-sub { font-size: 10px; margin-bottom: 2px; }
        h3 { margin: 10px 0 6px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #333; padding: 4px; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signature-table { width: 100%; border: none !important; margin-top: 50px; }
        .signature-table td { border: none !important; padding: 10px; vertical-align: top; text-align: center; }
        .signature-wrapper { margin-top: 60px; }
    </style>
</head>
<body>
    <div class="kop">
        <?php if (!empty($kopImageSrc)): ?>
            <div class="kop-image-wrap">
                <img src="<?php echo $kopImageSrc; ?>" class="kop-image" alt="Kop Lembaga">
            </div>
        <?php else: ?>
            <?php if (!empty($logoImageSrc)): ?>
                <div class="kop-image-wrap">
                    <img src="<?php echo $logoImageSrc; ?>" class="kop-image" alt="Logo Lembaga">
                </div>
            <?php endif; ?>
            <div class="kop-text">
                <div class="kop-title"><?php echo html_escape($namaLembaga); ?></div>
                <div class="kop-sub"><?php echo html_escape($alamatLembaga); ?></div>
                <div class="kop-sub"><?php echo html_escape($kontakLembaga); ?></div>
            </div>
        <?php endif; ?>
    </div>

    <h3 class="text-center">LAPORAN ZAKAT PERIODE <?php echo html_escape(indo_date($start_date)); ?> s.d. <?php echo html_escape(indo_date($end_date)); ?></h3>

    <table>
        <thead>
            <tr>
                <th>Total Fitrah Uang</th>
                <th>Total Fitrah Beras</th>
                <th>Total Zakat Mal</th>
                <th>Total Infaq/Shodaqoh</th>
                <th>Total Penyaluran Uang</th>
                <th>Total Penyaluran Beras</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-right">Rp <?php echo number_format($fitrahUang, 0, ',', '.'); ?></td>
                <td class="text-right"><?php echo number_format($fitrahBeras, 2, ',', '.'); ?> Kg</td>
                <td class="text-right">Rp <?php echo number_format($malUang, 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?php echo number_format($infaqUang, 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?php echo number_format($penyaluranUang, 0, ',', '.'); ?></td>
                <td class="text-right"><?php echo number_format($penyaluranBeras, 2, ',', '.'); ?> Kg</td>
            </tr>
        </tbody>
    </table>

    <h3>1. Laporan Zakat Fitrah</h3>
    <table>
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Muzakki</th>
                <th>Jiwa</th>
                <th>Metode</th>
                <th>Nominal/Beras</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows_fitrah)): foreach ($rows_fitrah as $row): ?>
                <tr>
                    <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                    <td><?php echo html_escape(indo_date($row->tanggal_bayar)); ?></td>
                    <td><?php echo html_escape($row->nama_muzakki); ?></td>
                    <td class="text-center"><?php echo (int) $row->jumlah_jiwa; ?></td>
                    <td><?php echo html_escape($row->metode_tunaikan); ?></td>
                    <td class="text-right">
                        <?php if ($row->metode_tunaikan === 'beras'): ?>
                            <?php echo number_format((float) $row->beras_kg, 2, ',', '.'); ?> Kg
                        <?php else: ?>
                            Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo html_escape($row->status); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>2. Laporan Zakat Mal</h3>
    <table>
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Tanggal Hitung</th>
                <th>Muzakki</th>
                <th>Harta Bersih</th>
                <th>Nishab</th>
                <th>Total Zakat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows_mal)): foreach ($rows_mal as $row): ?>
                <tr>
                    <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                    <td><?php echo html_escape(indo_date($row->tanggal_hitung)); ?></td>
                    <td><?php echo html_escape($row->nama_muzakki); ?></td>
                    <td class="text-right">Rp <?php echo number_format((float) $row->harta_bersih, 0, ',', '.'); ?></td>
                    <td class="text-right">Rp <?php echo number_format((float) $row->nilai_nishab, 0, ',', '.'); ?></td>
                    <td class="text-right">Rp <?php echo number_format((float) $row->total_zakat, 0, ',', '.'); ?></td>
                    <td><?php echo html_escape($row->status); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>3. Laporan Infaq & Shodaqoh</h3>
    <table>
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Jenis Dana</th>
                <th>Donatur</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows_infaq_shodaqoh)): foreach ($rows_infaq_shodaqoh as $row): ?>
                <tr>
                    <td><?php echo html_escape($row->nomor_transaksi); ?></td>
                    <td><?php echo html_escape(indo_date($row->tanggal_transaksi)); ?></td>
                    <td><?php echo ucfirst(html_escape($row->jenis_dana)); ?></td>
                    <td><?php echo html_escape($row->nama_donatur); ?></td>
                    <td class="text-right">Rp <?php echo number_format((float) $row->nominal_uang, 0, ',', '.'); ?></td>
                    <td><?php echo strtoupper(html_escape($row->metode_bayar)); ?></td>
                    <td><?php echo html_escape($row->status); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right">Rp <?php echo number_format($infaqUang, 0, ',', '.'); ?></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>

    <h3>4. Laporan Penyaluran</h3>
    <table>
        <thead>
            <tr>
                <th>No Penyaluran</th>
                <th>Tanggal</th>
                <th>Sumber</th>
                <th>Total Uang</th>
                <th>Total Beras</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows_penyaluran)): foreach ($rows_penyaluran as $row): ?>
                <tr>
                    <td><?php echo html_escape($row->nomor_penyaluran); ?></td>
                    <td><?php echo html_escape(indo_date($row->tanggal_penyaluran)); ?></td>
                    <td><?php echo html_escape($row->jenis_sumber); ?></td>
                    <td class="text-right">Rp <?php echo number_format((float) $row->total_uang, 0, ',', '.'); ?></td>
                    <td class="text-right"><?php echo number_format((float) $row->total_beras_kg, 2, ',', '.'); ?> Kg</td>
                    <td><?php echo html_escape($row->status); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>5. List Mustahik Penerima</h3>
    <table>
        <thead>
            <tr>
                <th>Kode Mustahik</th>
                <th>Nama Mustahik</th>
                <th>Jumlah Penerimaan</th>
                <th>Total Uang Diterima</th>
                <th>Total Beras Diterima</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows_mustahik)): foreach ($rows_mustahik as $row): ?>
                <tr>
                    <td><?php echo html_escape(!empty($row->kode_mustahik) ? $row->kode_mustahik : '-'); ?></td>
                    <td><?php echo html_escape(!empty($row->nama_mustahik) ? $row->nama_mustahik : '-'); ?></td>
                    <td class="text-center"><?php echo (int) $row->jumlah_penerimaan; ?></td>
                    <td class="text-right">Rp <?php echo number_format((float) $row->total_nominal_uang, 0, ',', '.'); ?></td>
                    <td class="text-right"><?php echo number_format((float) $row->total_beras_kg, 2, ',', '.'); ?> Kg</td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-wrapper">
        <table class="signature-table">
            <tr>
                <td width="50%">
                    Mengetahui,<br>
                    Ketua <?php echo html_escape($namaLembaga); ?><br><br><br><br><br>
                    <strong><u><?php echo html_escape($pimpinan); ?></u></strong>
                </td>
                <td width="50%">
                    <?php echo html_escape($kota); ?>, <?php echo html_escape(indo_date(date('Y-m-d'))); ?><br>
                    Bendahara <?php echo html_escape($namaLembaga); ?><br><br><br><br><br>
                    <strong><u><?php echo html_escape($bendahara); ?></u></strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
