<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tanggungan_fitrah extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tanggungan_fitrah_model', 'tanggungan');
        $this->_require_login();
    }

    public function index()
    {
        $data = array(
            'page_title' => 'Tanggungan Fitrah',
            'content_view' => 'tanggungan_fitrah/index',
            'rows' => $this->tanggungan->get_all()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $options = $this->tanggungan->get_kepala_keluarga_options();

        $data = array(
            'page_title' => 'Tambah Tanggungan Fitrah',
            'content_view' => 'tanggungan_fitrah/form',
            'form_action' => 'tanggungan_fitrah/store',
            'muzakki_options' => $options
        );

        if (empty($options)) {
            $this->session->set_flashdata('error', 'Belum ada muzakki dengan jenis kepala_keluarga. Silakan tambahkan dahulu di menu Muzakki.');
        }

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

        $muzakkiId = (int) $this->input->post('muzakki_id', TRUE);
        if (!$this->tanggungan->is_kepala_keluarga($muzakkiId)) {
            $this->session->set_flashdata('error', 'Tanggungan hanya bisa ditambahkan untuk muzakki jenis kepala_keluarga.');
            return $this->create();
        }

        $this->tanggungan->insert($this->_build_payload());
        $this->session->set_flashdata('success', 'Tanggungan fitrah berhasil ditambahkan.');
        redirect('tanggungan_fitrah');
    }

    public function edit($id = NULL)
    {
        $row = $this->tanggungan->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Tanggungan Fitrah',
            'content_view' => 'tanggungan_fitrah/form',
            'form_action' => 'tanggungan_fitrah/update/' . (int) $row->id,
            'row' => $row,
            'muzakki_options' => $this->tanggungan->get_kepala_keluarga_options()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->tanggungan->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $muzakkiId = (int) $this->input->post('muzakki_id', TRUE);
        if (!$this->tanggungan->is_kepala_keluarga($muzakkiId)) {
            $this->session->set_flashdata('error', 'Tanggungan hanya bisa ditambahkan untuk muzakki jenis kepala_keluarga.');
            return $this->edit($id);
        }

        $this->tanggungan->update($id, $this->_build_payload());
        $this->session->set_flashdata('success', 'Tanggungan fitrah berhasil diperbarui.');
        redirect('tanggungan_fitrah');
    }

    public function delete($id = NULL)
    {
        $row = $this->tanggungan->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->tanggungan->delete($id);
        $this->session->set_flashdata('success', 'Tanggungan fitrah berhasil dihapus.');
        redirect('tanggungan_fitrah');
    }

    private function _build_payload()
    {
        return array(
            'muzakki_id' => (int) $this->input->post('muzakki_id', TRUE),
            'nama_anggota' => trim((string) $this->input->post('nama_anggota', TRUE)),
            'hubungan_keluarga' => $this->_null_if_empty($this->input->post('hubungan_keluarga', TRUE)),
            'aktif_dihitung' => (int) $this->input->post('aktif_dihitung', TRUE)
        );
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('muzakki_id', 'Muzakki', 'trim|required|integer');
        $this->form_validation->set_rules('nama_anggota', 'Nama Anggota', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('hubungan_keluarga', 'Hubungan Keluarga', 'trim|max_length[50]');
        $this->form_validation->set_rules('aktif_dihitung', 'Aktif Dihitung', 'trim|required|in_list[0,1]');
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
