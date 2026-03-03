<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muzakki extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Muzakki_model', 'muzakki');
        $this->_require_login();
    }

    public function index()
    {
        $search = trim((string) $this->input->get('q', TRUE));
        $per_page = 10;
        $total_filtered = $this->muzakki->count_filtered($search);
        $paging = zisedu_build_paging(array(
            'base_url' => site_url('muzakki'),
            'total_rows' => $total_filtered,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'reuse_query_string' => TRUE
        ));

        $data = array(
            'page_title' => 'Muzakki',
            'content_view' => 'muzakki/index',
            'rows' => $this->muzakki->get_paginated($paging['limit'], $paging['offset'], $search),
            'stats' => $this->muzakki->get_statistics(),
            'paging' => $paging,
            'paging_links' => $paging['links'],
            'search' => $search,
            'total_rows' => $this->muzakki->count_all()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Muzakki',
            'content_view' => 'muzakki/form',
            'form_action' => 'muzakki/store',
            'anggota_rows' => array(),
            'auto_kode' => $this->muzakki->generate_next_kode()
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

        $kode = trim((string) $this->input->post('kode_muzakki', TRUE));
        if ($kode === '') {
            $kode = $this->muzakki->generate_next_kode();
        }

        if ($this->muzakki->exists_kode($kode)) {
            $kode = $this->muzakki->generate_next_kode();

            if ($this->muzakki->exists_kode($kode)) {
                $this->session->set_flashdata('error', 'Gagal membuat kode muzakki otomatis. Silakan ulangi.');
                return $this->create();
            }
        }

        $payload = $this->_build_payload($kode);
        $anggotaRows = $this->_collect_anggota_rows();

        $this->db->trans_begin();
        $this->muzakki->insert($payload);
        $muzakkiId = (int) $this->db->insert_id();

        if ($payload['jenis_muzakki'] === 'kepala_keluarga') {
            $this->muzakki->replace_tanggungan($muzakkiId, $anggotaRows);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan data muzakki.');
            return $this->create();
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Data muzakki berhasil ditambahkan.');
        redirect('muzakki');
    }

    public function edit($id = NULL)
    {
        $row = $this->muzakki->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Muzakki',
            'content_view' => 'muzakki/form',
            'form_action' => 'muzakki/update/' . (int) $row->id,
            'row' => $row,
            'anggota_rows' => $this->muzakki->get_tanggungan($row->id)
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->muzakki->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $kode = trim((string) $this->input->post('kode_muzakki', TRUE));
        if ($this->muzakki->exists_kode($kode, $id)) {
            $this->session->set_flashdata('error', 'Kode muzakki sudah digunakan.');
            return $this->edit($id);
        }

        $payload = $this->_build_payload($kode);
        $anggotaRows = $this->_collect_anggota_rows();

        $this->db->trans_begin();
        $this->muzakki->update($id, $payload);

        if ($payload['jenis_muzakki'] === 'kepala_keluarga') {
            $this->muzakki->replace_tanggungan($id, $anggotaRows);
        } else {
            $this->muzakki->replace_tanggungan($id, array());
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal memperbarui data muzakki.');
            return $this->edit($id);
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Data muzakki berhasil diperbarui.');
        redirect('muzakki');
    }

    public function delete($id = NULL)
    {
        $row = $this->muzakki->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->muzakki->delete($id);
        $this->session->set_flashdata('success', 'Data muzakki berhasil dihapus.');
        redirect('muzakki');
    }

    private function _build_payload($kode)
    {
        return array(
            'kode_muzakki' => $kode,
            'jenis_muzakki' => (string) $this->input->post('jenis_muzakki', TRUE),
            'nama' => trim((string) $this->input->post('nama', TRUE)),
            'nik' => $this->_null_if_empty($this->input->post('nik', TRUE)),
            'npwp' => $this->_null_if_empty($this->input->post('npwp', TRUE)),
            'no_hp' => $this->_null_if_empty($this->input->post('no_hp', TRUE)),
            'email' => $this->_null_if_empty($this->input->post('email', TRUE)),
            'pekerjaan' => $this->_null_if_empty($this->input->post('pekerjaan', TRUE)),
            'alamat' => $this->_null_if_empty($this->input->post('alamat', TRUE)),
            'kelurahan' => $this->_null_if_empty($this->input->post('kelurahan', TRUE)),
            'kecamatan' => $this->_null_if_empty($this->input->post('kecamatan', TRUE)),
            'kota_kabupaten' => $this->_null_if_empty($this->input->post('kota_kabupaten', TRUE)),
            'provinsi' => $this->_null_if_empty($this->input->post('provinsi', TRUE)),
            'kode_pos' => $this->_null_if_empty($this->input->post('kode_pos', TRUE))
        );
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('kode_muzakki', 'Kode Muzakki', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('jenis_muzakki', 'Jenis Muzakki', 'trim|required|in_list[individu,lembaga,kepala_keluarga]');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[120]');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|max_length[30]');
        $this->form_validation->set_rules('npwp', 'NPWP', 'trim|max_length[30]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'trim|max_length[25]');
    }

    private function _collect_anggota_rows()
    {
        $namaArr = (array) $this->input->post('anggota_nama', TRUE);
        $hubArr = (array) $this->input->post('anggota_hubungan', TRUE);
        $aktifArr = (array) $this->input->post('anggota_aktif', TRUE);

        $rows = array();
        $max = max(count($namaArr), count($hubArr));
        for ($i = 0; $i < $max; $i++) {
            $nama = isset($namaArr[$i]) ? trim((string) $namaArr[$i]) : '';
            $hub = isset($hubArr[$i]) ? trim((string) $hubArr[$i]) : '';
            $aktif = isset($aktifArr[$i]) ? (int) $aktifArr[$i] : 1;

            if ($nama === '') {
                continue;
            }

            $rows[] = array(
                'nama_anggota' => $nama,
                'hubungan_keluarga' => $hub !== '' ? $hub : NULL,
                'aktif_dihitung' => ($aktif === 0) ? 0 : 1
            );
        }

        return $rows;
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
