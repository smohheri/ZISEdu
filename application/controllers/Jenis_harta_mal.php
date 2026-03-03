<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_harta_mal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jenis_harta_mal_model', 'jenis_harta');
        $this->_require_login();
    }

    public function index()
    {
        $data = array(
            'page_title' => 'Jenis Harta Mal',
            'content_view' => 'jenis_harta_mal/index',
            'rows' => $this->jenis_harta->get_all()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Jenis Harta Mal',
            'content_view' => 'jenis_harta_mal/form',
            'form_action' => 'jenis_harta_mal/store'
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

        $kode = trim((string) $this->input->post('kode_jenis', TRUE));
        if ($this->jenis_harta->exists_kode($kode)) {
            $this->session->set_flashdata('error', 'Kode jenis sudah digunakan.');
            return $this->create();
        }

        $this->jenis_harta->insert($this->_build_payload($kode));
        $this->session->set_flashdata('success', 'Jenis harta mal berhasil ditambahkan.');
        redirect('jenis_harta_mal');
    }

    public function edit($id = NULL)
    {
        $row = $this->jenis_harta->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Jenis Harta Mal',
            'content_view' => 'jenis_harta_mal/form',
            'form_action' => 'jenis_harta_mal/update/' . (int) $row->id,
            'row' => $row
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->jenis_harta->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $kode = trim((string) $this->input->post('kode_jenis', TRUE));
        if ($this->jenis_harta->exists_kode($kode, $id)) {
            $this->session->set_flashdata('error', 'Kode jenis sudah digunakan.');
            return $this->edit($id);
        }

        $this->jenis_harta->update($id, $this->_build_payload($kode));
        $this->session->set_flashdata('success', 'Jenis harta mal berhasil diperbarui.');
        redirect('jenis_harta_mal');
    }

    public function delete($id = NULL)
    {
        $row = $this->jenis_harta->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $ok = $this->jenis_harta->delete($id);
        if (!$ok) {
            $this->session->set_flashdata('error', 'Data gagal dihapus. Kemungkinan sudah dipakai di detail zakat mal.');
            redirect('jenis_harta_mal');
        }

        $this->session->set_flashdata('success', 'Jenis harta mal berhasil dihapus.');
        redirect('jenis_harta_mal');
    }

    private function _build_payload($kode)
    {
        return array(
            'kode_jenis' => $kode,
            'nama_jenis' => trim((string) $this->input->post('nama_jenis', TRUE)),
            'tarif_persen' => (float) $this->input->post('tarif_persen', TRUE),
            'butuh_haul' => (int) $this->input->post('butuh_haul', TRUE),
            'keterangan' => $this->_null_if_empty($this->input->post('keterangan', TRUE)),
            'aktif' => (int) $this->input->post('aktif', TRUE)
        );
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('kode_jenis', 'Kode Jenis', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('nama_jenis', 'Nama Jenis', 'trim|required|max_length[120]');
        $this->form_validation->set_rules('tarif_persen', 'Tarif', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('butuh_haul', 'Butuh Haul', 'trim|required|in_list[0,1]');
        $this->form_validation->set_rules('aktif', 'Status', 'trim|required|in_list[0,1]');
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
