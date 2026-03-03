<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_zakat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengaturan_zakat_model', 'pengaturan');
        $this->_require_login();
    }

    public function index()
    {
        $data = array(
            'page_title' => 'Pengaturan Zakat',
            'content_view' => 'pengaturan_zakat/index',
            'rows' => $this->pengaturan->get_all()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Pengaturan Zakat',
            'content_view' => 'pengaturan_zakat/form',
            'form_action' => 'pengaturan_zakat/store'
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

        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($this->pengaturan->exists_tahun($tahun)) {
            $this->session->set_flashdata('error', 'Pengaturan untuk tahun tersebut sudah ada.');
            return $this->create();
        }

        $payload = $this->_build_payload();
        $this->pengaturan->insert($payload);
        $this->session->set_flashdata('success', 'Pengaturan zakat berhasil ditambahkan.');
        redirect('pengaturan_zakat');
    }

    public function edit($id = NULL)
    {
        $row = $this->pengaturan->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Pengaturan Zakat',
            'content_view' => 'pengaturan_zakat/form',
            'form_action' => 'pengaturan_zakat/update/' . (int) $row->id,
            'row' => $row
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->pengaturan->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($this->pengaturan->exists_tahun($tahun, $id)) {
            $this->session->set_flashdata('error', 'Pengaturan untuk tahun tersebut sudah ada.');
            return $this->edit($id);
        }

        $payload = $this->_build_payload();
        $this->pengaturan->update($id, $payload);
        $this->session->set_flashdata('success', 'Pengaturan zakat berhasil diperbarui.');
        redirect('pengaturan_zakat');
    }

    private function _build_payload()
    {
        return array(
            'tahun' => (int) $this->input->post('tahun', TRUE),
            'fitrah_per_jiwa_kg' => (float) $this->input->post('fitrah_per_jiwa_kg', TRUE),
            'fitrah_per_jiwa_rupiah' => (float) $this->input->post('fitrah_per_jiwa_rupiah', TRUE),
            'harga_beras_per_kg' => (float) $this->input->post('harga_beras_per_kg', TRUE),
            'nilai_emas_per_gram' => (float) $this->input->post('nilai_emas_per_gram', TRUE)
        );
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required|integer|greater_than_equal_to[2000]|less_than_equal_to[2100]');
        $this->form_validation->set_rules('fitrah_per_jiwa_kg', 'Fitrah per Jiwa (Kg)', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('fitrah_per_jiwa_rupiah', 'Fitrah per Jiwa (Rp)', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('harga_beras_per_kg', 'Harga Beras per Kg (Rp)', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('nilai_emas_per_gram', 'Nilai Emas per Gram (Rp)', 'trim|required|numeric|greater_than_equal_to[0]');
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
