<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mustahik extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mustahik_model', 'mustahik');
        $this->_require_login();
    }

    public function index()
    {
        $search = trim((string) $this->input->get('q', TRUE));
        $per_page = 10;
        $total_filtered = $this->mustahik->count_filtered($search);
        $paging = zisedu_build_paging(array(
            'base_url' => site_url('mustahik'),
            'total_rows' => $total_filtered,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'reuse_query_string' => TRUE
        ));

        $data = array(
            'page_title' => 'Mustahik',
            'content_view' => 'mustahik/index',
            'rows' => $this->mustahik->get_paginated($paging['limit'], $paging['offset'], $search),
            'stats' => $this->mustahik->get_statistics(),
            'paging' => $paging,
            'paging_links' => $paging['links'],
            'search' => $search
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Mustahik',
            'content_view' => 'mustahik/form',
            'form_action' => 'mustahik/store',
            'auto_kode' => $this->mustahik->generate_next_kode()
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

        $kode = trim((string) $this->input->post('kode_mustahik', TRUE));
        if ($kode === '') {
            $kode = $this->mustahik->generate_next_kode();
        }

        if ($this->mustahik->exists_kode($kode)) {
            $kode = $this->mustahik->generate_next_kode();
            if ($this->mustahik->exists_kode($kode)) {
                $this->session->set_flashdata('error', 'Gagal membuat kode mustahik otomatis. Silakan ulangi.');
                return $this->create();
            }
        }

        $this->mustahik->insert($this->_build_payload($kode));
        $this->session->set_flashdata('success', 'Data mustahik berhasil ditambahkan.');
        redirect('mustahik');
    }

    public function edit($id = NULL)
    {
        $row = $this->mustahik->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Mustahik',
            'content_view' => 'mustahik/form',
            'form_action' => 'mustahik/update/' . (int) $row->id,
            'row' => $row
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->mustahik->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $kode = trim((string) $this->input->post('kode_mustahik', TRUE));
        if ($this->mustahik->exists_kode($kode, $id)) {
            $this->session->set_flashdata('error', 'Kode mustahik sudah digunakan.');
            return $this->edit($id);
        }

        $this->mustahik->update($id, $this->_build_payload($kode));
        $this->session->set_flashdata('success', 'Data mustahik berhasil diperbarui.');
        redirect('mustahik');
    }

    public function delete($id = NULL)
    {
        $row = $this->mustahik->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $ok = $this->mustahik->delete($id);
        if (!$ok) {
            $this->session->set_flashdata('error', 'Data mustahik gagal dihapus. Kemungkinan sudah dipakai di transaksi penyaluran.');
            redirect('mustahik');
        }

        $this->session->set_flashdata('success', 'Data mustahik berhasil dihapus.');
        redirect('mustahik');
    }

    private function _build_payload($kode)
    {
        return array(
            'kode_mustahik' => $kode,
            'nama' => trim((string) $this->input->post('nama', TRUE)),
            'nik' => $this->_null_if_empty($this->input->post('nik', TRUE)),
            'kategori_asnaf' => (string) $this->input->post('kategori_asnaf', TRUE),
            'no_hp' => $this->_null_if_empty($this->input->post('no_hp', TRUE)),
            'alamat' => $this->_null_if_empty($this->input->post('alamat', TRUE)),
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
        $this->form_validation->set_rules('kode_mustahik', 'Kode Mustahik', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|max_length[30]');
        $this->form_validation->set_rules('kategori_asnaf', 'Kategori Asnaf', 'trim|required|in_list[fakir,miskin,amil,muallaf,riqab,gharimin,fisabilillah,ibnu_sabil]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'trim|max_length[25]');
        $this->form_validation->set_rules('aktif', 'Status', 'trim|required|in_list[0,1]');
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
