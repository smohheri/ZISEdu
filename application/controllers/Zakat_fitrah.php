<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zakat_fitrah extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Zakat_fitrah_model', 'zakat_fitrah');
        $this->_require_login();
    }

    public function index()
    {
        $search = trim((string) $this->input->get('q', TRUE));
        $per_page = 10;
        $total_filtered = $this->zakat_fitrah->count_filtered($search);
        $paging = zisedu_build_paging(array(
            'base_url' => site_url('zakat_fitrah'),
            'total_rows' => $total_filtered,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'reuse_query_string' => TRUE
        ));

        $rows = $this->zakat_fitrah->get_paginated($paging['limit'], $paging['offset'], $search);
        foreach ($rows as $r) {
            $tanggungan = $this->zakat_fitrah->get_tanggungan_aktif($r->muzakki_id);
            $t_names = array();
            foreach ($tanggungan as $t) {
                $t_names[] = trim((string) $t->nama_anggota) . ' (' . trim((string) $t->hubungan_keluarga) . ')';
            }
            $r->tanggungan_list = empty($t_names) ? '-' : implode(', ', $t_names);
        }

        $data = array(
            'page_title' => 'Zakat Fitrah',
            'content_view' => 'zakat_fitrah/index',
            'rows' => $rows,
            'stats' => $this->zakat_fitrah->get_statistics(),
            'paging' => $paging,
            'paging_links' => $paging['links'],
            'search' => $search
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Zakat Fitrah',
            'content_view' => 'zakat_fitrah/form',
            'form_action' => 'zakat_fitrah/store',
            'muzakki_options' => $this->zakat_fitrah->get_muzakki_options(),
            'auto_nomor' => $this->zakat_fitrah->generate_next_nomor()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $nomor = trim((string) $this->input->post('nomor_transaksi', TRUE));
        if ($nomor === '') {
            $nomor = $this->zakat_fitrah->generate_next_nomor($this->input->post('tanggal_bayar', TRUE));
        }

        if ($this->zakat_fitrah->exists_nomor($nomor)) {
            $nomor = $this->zakat_fitrah->generate_next_nomor($this->input->post('tanggal_bayar', TRUE));
            if ($this->zakat_fitrah->exists_nomor($nomor)) {
                $this->session->set_flashdata('error', 'Gagal membuat nomor transaksi otomatis.');
                return $this->create();
            }
        }

        $payload = $this->_build_payload($nomor);
        $this->_apply_auto_jumlah_jiwa($payload);

        if ($payload['metode_tunaikan'] === 'uang' && $payload['nominal_uang'] <= 0) {
            $this->session->set_flashdata('error', 'Nominal uang harus lebih dari 0 untuk metode uang.');
            return $this->create();
        }

        if ($payload['metode_tunaikan'] === 'beras' && $payload['beras_kg'] <= 0) {
            $this->session->set_flashdata('error', 'Jumlah beras harus lebih dari 0 untuk metode beras.');
            return $this->create();
        }

        $this->zakat_fitrah->insert($payload);
        $fitrahId = (int) $this->db->insert_id();
        if ($fitrahId > 0 && $this->db->field_exists('no_kwitansi', 'zakat_fitrah')) {
            $this->zakat_fitrah->update($fitrahId, array(
                'no_kwitansi' => $this->_generate_no_kwitansi($fitrahId, $payload['tanggal_bayar'], 'KW/FTR')
            ));
        }

        $this->session->set_flashdata('success', 'Transaksi zakat fitrah berhasil ditambahkan.');
        redirect('zakat_fitrah');
    }

    public function edit($id = NULL)
    {
        $row = $this->zakat_fitrah->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Zakat Fitrah',
            'content_view' => 'zakat_fitrah/form',
            'form_action' => 'zakat_fitrah/update/' . (int) $row->id,
            'row' => $row,
            'muzakki_options' => $this->zakat_fitrah->get_muzakki_options()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->zakat_fitrah->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $nomor = trim((string) $this->input->post('nomor_transaksi', TRUE));
        if ($nomor === '') {
            $this->session->set_flashdata('error', 'Nomor transaksi wajib terisi.');
            return $this->edit($id);
        }

        if ($this->zakat_fitrah->exists_nomor($nomor, $id)) {
            $this->session->set_flashdata('error', 'Nomor transaksi sudah digunakan.');
            return $this->edit($id);
        }

        $payload = $this->_build_payload($nomor, FALSE);
        $this->_apply_auto_jumlah_jiwa($payload);

        if ($payload['metode_tunaikan'] === 'uang' && $payload['nominal_uang'] <= 0) {
            $this->session->set_flashdata('error', 'Nominal uang harus lebih dari 0 untuk metode uang.');
            return $this->edit($id);
        }

        if ($payload['metode_tunaikan'] === 'beras' && $payload['beras_kg'] <= 0) {
            $this->session->set_flashdata('error', 'Jumlah beras harus lebih dari 0 untuk metode beras.');
            return $this->edit($id);
        }

        $this->zakat_fitrah->update($id, $payload);
        $this->session->set_flashdata('success', 'Transaksi zakat fitrah berhasil diperbarui.');
        redirect('zakat_fitrah');
    }

    public function delete($id = NULL)
    {
        $row = $this->zakat_fitrah->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->zakat_fitrah->delete($id);
        $this->session->set_flashdata('success', 'Transaksi zakat fitrah berhasil dihapus.');
        redirect('zakat_fitrah');
    }

    public function kwitansi($id = NULL)
    {
        $row = $this->zakat_fitrah->get_receipt_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();
        $tanggungan = $this->zakat_fitrah->get_tanggungan_aktif((int) $row->muzakki_id);

        $noKwitansi = $this->_resolve_no_kwitansi($row, 'KW/FTR');
        $namaPenerima = trim((string) $this->session->userdata('nama_lengkap'));
        if ($namaPenerima === '') {
            $namaPenerima = trim((string) $this->session->userdata('username'));
        }

        $data = array(
            'page_title' => 'Kwitansi Penerimaan Zakat Fitrah',
            'row' => $row,
            'no_kwitansi' => $noKwitansi,
            'lembaga' => $lembaga,
            'tanggungan' => $tanggungan,
            'nama_penerima' => $namaPenerima
        );

        $this->load->view('zakat_fitrah/kwitansi', $data);
    }

    public function export_pdf($id = NULL)
    {
        $row = $this->zakat_fitrah->get_receipt_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();
        $tanggungan = $this->zakat_fitrah->get_tanggungan_aktif((int) $row->muzakki_id);

        $noKwitansi = $this->_resolve_no_kwitansi($row, 'KW/FTR');

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
            'no_kwitansi' => $noKwitansi,
            'lembaga' => $lembaga,
            'tanggungan' => $tanggungan,
            'nama_penerima' => $namaPenerima,
            'logo_image_src' => $logoImageSrc
        );

        $vendorAutoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($vendorAutoload)) {
            show_error('Autoload Composer tidak ditemukan. Pastikan dependency mPDF sudah terpasang.');
        }

        require_once $vendorAutoload;

        $html = $this->load->view('zakat_fitrah/kwitansi_pdf', $viewData, TRUE);
        $mpdf = new \Mpdf\Mpdf(array(
            'format' => array(210, 139),
            'margin_left' => 6,
            'margin_right' => 6,
            'margin_top' => 6,
            'margin_bottom' => 4,
            'autoPageBreak' => false
        ));
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->SetTitle('Kwitansi Zakat Fitrah - ' . $row->nomor_transaksi);
        $mpdf->WriteHTML($html);

        $filename = 'kwitansi-zakat-fitrah-' . (int) $row->id . '.pdf';
        $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
    }

    public function muzakki_info($muzakkiId = NULL)
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(array('message' => 'Unauthorized')));
        }

        $info = $this->zakat_fitrah->get_muzakki_info((int) $muzakkiId);
        if (!$info) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(array('message' => 'Muzakki tidak ditemukan')));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($info));
    }

    private function _build_payload($nomor, $includeCreatedBy = TRUE)
    {
        $payload = array(
            'nomor_transaksi' => $nomor,
            'muzakki_id' => (int) $this->input->post('muzakki_id', TRUE),
            'tanggal_bayar' => (string) $this->input->post('tanggal_bayar', TRUE),
            'tahun_hijriah' => $this->_null_if_empty($this->input->post('tahun_hijriah', TRUE)),
            'tahun_masehi' => (int) $this->input->post('tahun_masehi', TRUE),
            'jumlah_jiwa' => (int) $this->input->post('jumlah_jiwa', TRUE),
            'metode_tunaikan' => (string) $this->input->post('metode_tunaikan', TRUE),
            'beras_kg' => (float) $this->input->post('beras_kg', TRUE),
            'nominal_uang' => (float) $this->input->post('nominal_uang', TRUE),
            'metode_bayar' => (string) $this->input->post('metode_bayar', TRUE),
            'keterangan' => $this->_null_if_empty($this->input->post('keterangan', TRUE)),
            'status' => (string) $this->input->post('status', TRUE)
        );

        if ($includeCreatedBy) {
            $payload['created_by'] = (int) $this->session->userdata('user_id');
        }

        return $payload;
    }

    private function _generate_no_kwitansi($id, $tanggal, $prefix)
    {
        $tanggal = !empty($tanggal) ? $tanggal : date('Y-m-d');
        return $prefix . '/' . date('Y', strtotime($tanggal)) . '/' . str_pad((string) ((int) $id), 4, '0', STR_PAD_LEFT);
    }

    private function _resolve_no_kwitansi($row, $prefix)
    {
        $existing = isset($row->no_kwitansi) ? trim((string) $row->no_kwitansi) : '';
        if ($existing !== '') {
            return $existing;
        }

        $generated = $this->_generate_no_kwitansi((int) $row->id, isset($row->tanggal_bayar) ? $row->tanggal_bayar : NULL, $prefix);

        if ($this->db->field_exists('no_kwitansi', 'zakat_fitrah')) {
            $this->zakat_fitrah->update((int) $row->id, array('no_kwitansi' => $generated));
        }

        return $generated;
    }

    private function _apply_auto_jumlah_jiwa(array &$payload)
    {
        $info = $this->zakat_fitrah->get_muzakki_info((int) $payload['muzakki_id']);
        if (!$info) {
            return;
        }

        if ($info['jenis_muzakki'] !== 'individu') {
            $payload['jumlah_jiwa'] = max(1, (int) $info['jumlah_jiwa_otomatis']);
        }
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|max_length[40]');
        $this->form_validation->set_rules('muzakki_id', 'Muzakki', 'trim|required|integer');
        $this->form_validation->set_rules('tanggal_bayar', 'Tanggal Bayar', 'trim|required');
        $this->form_validation->set_rules('tahun_masehi', 'Tahun Masehi', 'trim|required|integer');
        $this->form_validation->set_rules('jumlah_jiwa', 'Jumlah Jiwa', 'trim|required|integer|greater_than[0]');
        $this->form_validation->set_rules('metode_tunaikan', 'Metode Tunaikan', 'trim|required|in_list[uang,beras]');
        $this->form_validation->set_rules('metode_bayar', 'Metode Bayar', 'trim|required|in_list[tunai,transfer,qris,lainnya]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[draft,lunas,batal]');
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
