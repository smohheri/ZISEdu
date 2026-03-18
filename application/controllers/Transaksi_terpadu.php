<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_terpadu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Zakat_fitrah_model', 'zakat_fitrah');
        $this->load->model('Zakat_mal_model', 'zakat_mal');
        $this->load->model('Infaq_shodaqoh_model', 'infaq_shodaqoh');
        $this->load->model('Transaksi_terpadu_model', 'transaksi_terpadu');
        $this->_require_login();
    }

    public function index()
    {
        $search = trim((string) $this->input->get('q', TRUE));
        $per_page = 10;
        $total_filtered = $this->transaksi_terpadu->count_filtered($search);
        
        $paging = zisedu_build_paging(array(
            'base_url' => site_url('transaksi_terpadu'),
            'total_rows' => $total_filtered,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'reuse_query_string' => TRUE
        ));

        $q_stats = $this->transaksi_terpadu->get_stats();

        $data = array(
            'page_title' => 'Transaksi Terpadu',
            'content_view' => 'transaksi_terpadu/index',
            'rows' => $this->transaksi_terpadu->get_paginated($paging['limit'], $paging['offset'], $search),
            'paging' => $paging,
            'paging_links' => $paging['links'],
            'search' => $search,
            'total_transaksi' => $q_stats ? $q_stats->total_transaksi : 0,
            'total_muzakki' => $q_stats ? $q_stats->total_muzakki : 0,
            'total_nominal' => $q_stats ? $q_stats->total_nominal : 0,
            'count_fitrah' => $q_stats ? $q_stats->count_fitrah : 0,
            'total_fitrah' => $q_stats ? $q_stats->total_fitrah : 0,
            'total_beras_fitrah' => $q_stats ? $q_stats->total_beras_fitrah : 0,
            'count_mal' => $q_stats ? $q_stats->count_mal : 0,
            'total_mal' => $q_stats ? $q_stats->total_mal : 0,
            'count_infaq' => $q_stats ? $q_stats->count_infaq : 0,
            'total_infaq' => $q_stats ? $q_stats->total_infaq : 0
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Transaksi Terpadu',
            'content_view' => 'transaksi_terpadu/form',
            'form_action' => 'transaksi_terpadu/store',
            'muzakki_options' => $this->zakat_fitrah->get_muzakki_options(),
            'jenis_harta_options' => $this->zakat_mal->get_jenis_harta_options(),
            'auto_nomor_fitrah' => $this->zakat_fitrah->generate_next_nomor(),
            'auto_nomor_mal' => $this->zakat_mal->generate_next_nomor(),
            'auto_nomor_infaq' => $this->infaq_shodaqoh->generate_next_nomor()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $muzakki_id = (int) $this->input->post('muzakki_id', TRUE);
        if ($muzakki_id <= 0) {
            $this->session->set_flashdata('error', 'Muzakki harus dipilih.');
            return redirect('transaksi_terpadu');
        }

        $tanggal_transaksi = (string) $this->input->post('tanggal_transaksi', TRUE);
        if (empty($tanggal_transaksi)) {
            $tanggal_transaksi = date('Y-m-d');
        }

        $enable_fitrah = $this->input->post('enable_fitrah') === '1';
        $enable_mal = $this->input->post('enable_mal') === '1';
        $enable_infaq = $this->input->post('enable_infaq') === '1';

        if (!$enable_fitrah && !$enable_mal && !$enable_infaq) {
            $this->session->set_flashdata('error', 'Pilih minimal satu jenis transaksi penyetoran.');
            return redirect('transaksi_terpadu');
        }

        $this->db->trans_begin();
        $messages = [];

        // --- Zakat Fitrah ---
        if ($enable_fitrah) {
            $nomor_fitrah = trim((string) $this->input->post('nomor_transaksi_fitrah', TRUE));
            if ($nomor_fitrah === '') {
                $nomor_fitrah = $this->zakat_fitrah->generate_next_nomor($tanggal_transaksi);
            }
            if ($this->zakat_fitrah->exists_nomor($nomor_fitrah)) {
                $nomor_fitrah = $this->zakat_fitrah->generate_next_nomor($tanggal_transaksi);
            }

            $payload_fitrah = array(
                'nomor_transaksi' => $nomor_fitrah,
                'muzakki_id' => $muzakki_id,
                'tanggal_bayar' => $tanggal_transaksi,
                'tahun_masehi' => (int) $this->input->post('tahun_masehi', TRUE),
                'tahun_hijriah' => $this->_null_if_empty($this->input->post('tahun_hijriah', TRUE)),
                'jumlah_jiwa' => (int) $this->input->post('fitrah_jumlah_jiwa', TRUE),
                'metode_tunaikan' => (string) $this->input->post('fitrah_metode_tunaikan', TRUE),
                'beras_kg' => (float) $this->input->post('fitrah_beras_kg', TRUE),
                'nominal_uang' => (float) $this->input->post('fitrah_nominal_uang', TRUE),
                'metode_bayar' => (string) $this->input->post('fitrah_metode_bayar', TRUE),
                'keterangan' => $this->_null_if_empty($this->input->post('fitrah_keterangan', TRUE)),
                'status' => 'lunas'
            );
            $payload_fitrah['created_by'] = (int) $this->session->userdata('user_id');

            $this->zakat_fitrah->insert($payload_fitrah);
            $fitrahId = (int) $this->db->insert_id();
            if ($fitrahId > 0 && $this->db->field_exists('no_kwitansi', 'zakat_fitrah')) {
                $this->zakat_fitrah->update($fitrahId, array(
                    'no_kwitansi' => $this->_generate_no_kwitansi_fitrah($fitrahId, $tanggal_transaksi)
                ));
            }
            $messages[] = 'Zakat Fitrah berhasil disimpan.';
        }

        // --- Zakat Mal ---
        if ($enable_mal) {
            $nomor_mal = trim((string) $this->input->post('nomor_transaksi_mal', TRUE));
            if ($nomor_mal === '') {
                $nomor_mal = $this->zakat_mal->generate_next_nomor($tanggal_transaksi);
            }
            if ($this->zakat_mal->exists_nomor($nomor_mal)) {
                $nomor_mal = $this->zakat_mal->generate_next_nomor($tanggal_transaksi);
            }

            $payload_mal = array(
                'nomor_transaksi' => $nomor_mal,
                'muzakki_id' => $muzakki_id,
                'tanggal_hitung' => $tanggal_transaksi,
                'tanggal_bayar' => $tanggal_transaksi,
                'tahun_masehi' => (int) $this->input->post('tahun_masehi', TRUE),
                'total_harta' => (float) $this->input->post('mal_total_harta', TRUE),
                'total_hutang_jatuh_tempo' => (float) $this->input->post('mal_total_hutang', TRUE),
                'harta_bersih' => (float) $this->input->post('mal_harta_bersih', TRUE),
                'nilai_nishab' => (float) $this->input->post('mal_nilai_nishab', TRUE),
                'persentase_zakat' => (float) $this->input->post('mal_persentase_zakat', TRUE),
                'total_zakat' => (float) $this->input->post('mal_total_zakat', TRUE),
                'metode_bayar' => (string) $this->input->post('mal_metode_bayar', TRUE),
                'keterangan' => $this->_null_if_empty($this->input->post('mal_keterangan', TRUE)),
                'status' => 'lunas'
            );
            $payload_mal['created_by'] = (int) $this->session->userdata('user_id');

            $this->zakat_mal->insert($payload_mal);
            $zakatMalId = (int) $this->db->insert_id();
            if ($zakatMalId > 0 && $this->db->field_exists('no_kwitansi', 'zakat_mal')) {
                $this->zakat_mal->update($zakatMalId, array(
                    'no_kwitansi' => $this->_generate_no_kwitansi_mal($zakatMalId, $tanggal_transaksi)
                ));
            }
            $detailRows = $this->_collect_mal_detail_rows();
            if (!empty($detailRows)) {
                $this->zakat_mal->replace_detail_rows($zakatMalId, $detailRows);
            }
            $messages[] = 'Zakat Mal berhasil disimpan.';
        }

        // --- Infaq Shodaqoh ---
        if ($enable_infaq) {
            $nomor_infaq = trim((string) $this->input->post('nomor_transaksi_infaq', TRUE));
            if ($nomor_infaq === '') {
                $nomor_infaq = $this->infaq_shodaqoh->generate_next_nomor($tanggal_transaksi);
            }
            if ($this->infaq_shodaqoh->exists_nomor($nomor_infaq)) {
                $nomor_infaq = $this->infaq_shodaqoh->generate_next_nomor($tanggal_transaksi);
            }
            
            $all_muzakki = $this->zakat_fitrah->get_muzakki_options();
            $nama_donatur = isset($all_muzakki[$muzakki_id]) ? $all_muzakki[$muzakki_id] : 'Hamba Allah';
            $input_nama_donatur = $this->input->post('infaq_nama_donatur', TRUE);
            if (!empty($input_nama_donatur)) {
                $nama_donatur = $input_nama_donatur;
            }

            $payload_infaq = array(
                'nomor_transaksi' => $nomor_infaq,
                'tanggal_transaksi' => $tanggal_transaksi,
                'jenis_dana' => (string) $this->input->post('infaq_jenis_dana', TRUE),
                'nama_donatur' => $nama_donatur,
                'no_hp' => $this->_null_if_empty($this->input->post('infaq_no_hp', TRUE)),
                'nominal_uang' => (float) $this->input->post('infaq_nominal_uang', TRUE),
                'metode_bayar' => (string) $this->input->post('infaq_metode_bayar', TRUE),
                'keterangan' => $this->_null_if_empty($this->input->post('infaq_keterangan', TRUE)),
                'status' => 'diterima'
            );
            $payload_infaq['created_by'] = (int) $this->session->userdata('user_id');

            $this->infaq_shodaqoh->insert($payload_infaq);
            $infaqId = (int) $this->db->insert_id();
            if ($infaqId > 0 && $this->db->field_exists('no_kwitansi', 'infaq_shodaqoh')) {
                $this->infaq_shodaqoh->update($infaqId, array(
                    'no_kwitansi' => $this->_generate_no_kwitansi_infaq($infaqId, $tanggal_transaksi)
                ));
            }
            $messages[] = 'Infaq/Shodaqoh berhasil disimpan.';
        }

        // --- Save to Transaksi Terpadu ---
        $noKwitansiTerpadu = 'KW/TRP/' . date('Y', strtotime($tanggal_transaksi)) . '/' . substr(md5(uniqid(rand(), true)), 0, 6);
        $payload_terpadu = array(
            'nomor_transaksi' => $noKwitansiTerpadu,
            'tanggal_transaksi' => $tanggal_transaksi,
            'muzakki_id' => $muzakki_id,
            'fitrah_id' => isset($fitrahId) ? $fitrahId : NULL,
            'mal_id' => isset($zakatMalId) ? $zakatMalId : NULL,
            'infaq_id' => isset($infaqId) ? $infaqId : NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => (int) $this->session->userdata('user_id')
        );
        $this->transaksi_terpadu->insert($payload_terpadu);
        $terpaduId = (int) $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan transaksi terpadu.');
            return redirect('transaksi_terpadu/create');
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', implode('<br>', $messages));
        
        // Cukup alihkan ke kwitansi dengan ID terpadu
        redirect('transaksi_terpadu/kwitansi/' . $terpaduId);
    }

    public function delete($id = NULL)
    {
        $row = $this->transaksi_terpadu->get_by_id($id);
        if (!$row) {
            show_404();
        }

        // Optional: you can choose to delete children too, but let's just delete the header for safety or both
        $this->db->trans_begin();
        if ($row->fitrah_id) $this->zakat_fitrah->delete($row->fitrah_id);
        if ($row->mal_id) $this->zakat_mal->delete($row->mal_id);
        if ($row->infaq_id) $this->infaq_shodaqoh->delete($row->infaq_id);
        $this->transaksi_terpadu->delete($id);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menghapus transaksi terpadu.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Transaksi terpadu berhasil dihapus beserta isinya.');
        }

        redirect('transaksi_terpadu');
    }

    public function kwitansi($id = NULL)
    {
        $row = $this->transaksi_terpadu->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $fitrahId = (int) $row->fitrah_id;
        $malId = (int) $row->mal_id;
        $infaqId = (int) $row->infaq_id;

        if ($fitrahId <= 0 && $malId <= 0 && $infaqId <= 0) {
            show_404();
        }

        $row_fitrah = $fitrahId > 0 ? $this->zakat_fitrah->get_receipt_by_id($fitrahId) : NULL;
        $row_mal = $malId > 0 ? $this->zakat_mal->get_receipt_by_id($malId) : NULL;
        $row_infaq = $infaqId > 0 ? $this->infaq_shodaqoh->get_by_id($infaqId) : NULL;

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();

        $namaPenerima = trim((string) $this->session->userdata('nama_lengkap'));
        if ($namaPenerima === '') {
            $namaPenerima = trim((string) $this->session->userdata('username'));
        }

        $noKwitansiTerpadu = $row->nomor_transaksi;

        // Try to get proper Muzakki Name
        $namaMuzakkiTtd = $row->nama_muzakki;
        if (empty($namaMuzakkiTtd) && $row_infaq && !empty($row_infaq->nama_donatur)) {
            $namaMuzakkiTtd = $row_infaq->nama_donatur;
        }

        $data = array(
            'page_title' => 'Kwitansi Terpadu',
            'row' => $row,
            'row_fitrah' => $row_fitrah,
            'row_mal' => $row_mal,
            'row_infaq' => $row_infaq,
            'lembaga' => $lembaga,
            'no_kwitansi_terpadu' => $noKwitansiTerpadu,
            'nama_penerima' => $namaPenerima,
            'nama_muzakki_ttd' => $namaMuzakkiTtd,
            'fitrah_id' => $fitrahId,
            'mal_id' => $malId,
            'infaq_id' => $infaqId
        );

        $this->load->view('transaksi_terpadu/kwitansi', $data);
    }

    public function export_pdf($id = NULL)
    {
        $row = $this->transaksi_terpadu->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $fitrahId = (int) $row->fitrah_id;
        $malId = (int) $row->mal_id;
        $infaqId = (int) $row->infaq_id;

        if ($fitrahId <= 0 && $malId <= 0 && $infaqId <= 0) {
            show_404();
        }

        $row_fitrah = $fitrahId > 0 ? $this->zakat_fitrah->get_receipt_by_id($fitrahId) : NULL;
        $row_mal = $malId > 0 ? $this->zakat_mal->get_receipt_by_id($malId) : NULL;
        $row_infaq = $infaqId > 0 ? $this->infaq_shodaqoh->get_by_id($infaqId) : NULL;

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();

        $noKwitansiTerpadu = $row->nomor_transaksi;

        // Try to get proper Muzakki Name
        $namaMuzakkiTtd = $row->nama_muzakki;
        if (empty($namaMuzakkiTtd) && $row_infaq && !empty($row_infaq->nama_donatur)) {
            $namaMuzakkiTtd = $row_infaq->nama_donatur;
        }

        $namaPenerima = trim((string) $this->session->userdata('nama_lengkap'));
        if ($namaPenerima === '') {
            $namaPenerima = trim((string) $this->session->userdata('username'));
        }

        $logoImageSrc = NULL;
        if ($lembaga && !empty($lembaga->logo_path)) {
            $logoFullPath = FCPATH . ltrim($lembaga->logo_path, '/\\');
            if (is_file($logoFullPath)) {
                $logoImageSrc = 'file:///' . str_replace('\\', '/', $logoFullPath);
            }
        }

        $viewData = array(
            'row' => $row,
            'row_fitrah' => $row_fitrah,
            'row_mal' => $row_mal,
            'row_infaq' => $row_infaq,
            'lembaga' => $lembaga,
            'no_kwitansi_terpadu' => $noKwitansiTerpadu,
            'nama_penerima' => $namaPenerima,
            'nama_muzakki_ttd' => $namaMuzakkiTtd,
            'logo_image_src' => $logoImageSrc
        );

        $vendorAutoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($vendorAutoload)) {
            show_error('Autoload Composer tidak ditemukan. Pastikan dependency mPDF sudah terpasang.');
        }

        require_once $vendorAutoload;

        $html = $this->load->view('transaksi_terpadu/kwitansi_pdf', $viewData, TRUE);
        $mpdf = new \Mpdf\Mpdf(array(
            'format' => array(210, 139),
            'margin_left' => 6,
            'margin_right' => 6,
            'margin_top' => 6,
            'margin_bottom' => 4,
            'autoPageBreak' => false
        ));
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->SetTitle('Kwitansi Terpadu');
        $mpdf->WriteHTML($html);

        $filename = 'kwitansi-terpadu.pdf';
        $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
    }

    private function _collect_mal_detail_rows()
    {
        $details = (array) $this->input->post('mal_detail');
        $rows = array();

        foreach ($details as $d) {
            if (!is_array($d)) continue;
            $jenisHartaId = isset($d['jenis_harta_id']) ? (int) $d['jenis_harta_id'] : 0;
            $nilaiHarta = isset($d['nilai_harta']) ? (float) $d['nilai_harta'] : 0;
            $haul = isset($d['nilai_haul_bulan']) && $d['nilai_haul_bulan'] !== '' ? (int) $d['nilai_haul_bulan'] : NULL;
            $ket = isset($d['keterangan']) ? trim((string) $d['keterangan']) : '';

            if ($jenisHartaId <= 0 && $nilaiHarta <= 0 && $ket === '') {
                continue;
            }

            if ($jenisHartaId <= 0 || $nilaiHarta <= 0) {
                continue;
            }

            $rows[] = array(
                'jenis_harta_id' => $jenisHartaId,
                'nilai_harta' => $nilaiHarta,
                'nilai_haul_bulan' => $haul,
                'keterangan' => $ket !== '' ? $ket : NULL
            );
        }

        return $rows;
    }

    private function _null_if_empty($value) {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _generate_no_kwitansi_fitrah($id, $tanggal) {
        $tanggal = !empty($tanggal) ? $tanggal : date('Y-m-d');
        return 'KW/FTR/' . date('Y', strtotime($tanggal)) . '/' . str_pad((string) ((int) $id), 4, '0', STR_PAD_LEFT);
    }
    private function _generate_no_kwitansi_mal($id, $tanggal) {
        $tanggal = !empty($tanggal) ? $tanggal : date('Y-m-d');
        return 'KW/MAL/' . date('Y', strtotime($tanggal)) . '/' . str_pad((string) ((int) $id), 4, '0', STR_PAD_LEFT);
    }
    private function _generate_no_kwitansi_infaq($id, $tanggal) {
        $tanggal = !empty($tanggal) ? $tanggal : date('Y-m-d');
        return 'KW/IS/' . date('Y', strtotime($tanggal)) . '/' . str_pad((string) ((int) $id), 4, '0', STR_PAD_LEFT);
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
