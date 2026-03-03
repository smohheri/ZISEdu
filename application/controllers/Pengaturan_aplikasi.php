<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_aplikasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $this->_require_login();
    }

    public function index()
    {
        $row = $this->pengaturan_aplikasi->get_first();

        $data = array(
            'page_title' => 'Pengaturan Aplikasi',
            'content_view' => 'pengaturan_aplikasi/index',
            'row' => $row
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function edit()
    {
        $row = $this->pengaturan_aplikasi->get_first();

        $data = array(
            'page_title' => 'Edit Pengaturan Aplikasi',
            'content_view' => 'pengaturan_aplikasi/form',
            'form_action' => 'pengaturan_aplikasi/update',
            'row' => $row
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function update()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit();
        }

        $row = $this->pengaturan_aplikasi->get_first();

        $payload = $this->_build_payload();

        if ($row) {
            $payload['logo_path'] = $this->_upload_file('logo_path', isset($row->logo_path) ? $row->logo_path : NULL);
            $payload['kop_path'] = $this->_upload_file('kop_path', isset($row->kop_path) ? $row->kop_path : NULL);
            $payload['stempel_path'] = $this->_upload_file('stempel_path', isset($row->stempel_path) ? $row->stempel_path : NULL);
            $this->pengaturan_aplikasi->update($row->id, $payload);
        } else {
            $payload['logo_path'] = $this->_upload_file('logo_path');
            $payload['kop_path'] = $this->_upload_file('kop_path');
            $payload['stempel_path'] = $this->_upload_file('stempel_path');
            $this->pengaturan_aplikasi->insert($payload);
        }

        $this->session->set_flashdata('success', 'Pengaturan aplikasi berhasil disimpan.');
        redirect('pengaturan_aplikasi');
    }

    private function _build_payload()
    {
        return array(
            'kode_lembaga' => trim((string) $this->input->post('kode_lembaga', TRUE)),
            'nama_lembaga' => trim((string) $this->input->post('nama_lembaga', TRUE)),
            'nama_pimpinan' => $this->_null_if_empty($this->input->post('nama_pimpinan', TRUE)),
            'nama_bendahara' => $this->_null_if_empty($this->input->post('nama_bendahara', TRUE)),
            'alamat' => $this->_null_if_empty($this->input->post('alamat', TRUE)),
            'kelurahan' => $this->_null_if_empty($this->input->post('kelurahan', TRUE)),
            'kecamatan' => $this->_null_if_empty($this->input->post('kecamatan', TRUE)),
            'kota_kabupaten' => $this->_null_if_empty($this->input->post('kota_kabupaten', TRUE)),
            'provinsi' => $this->_null_if_empty($this->input->post('provinsi', TRUE)),
            'kode_pos' => $this->_null_if_empty($this->input->post('kode_pos', TRUE)),
            'no_telp' => $this->_null_if_empty($this->input->post('no_telp', TRUE)),
            'no_hp' => $this->_null_if_empty($this->input->post('no_hp', TRUE)),
            'email' => $this->_null_if_empty($this->input->post('email', TRUE)),
            'website' => $this->_null_if_empty($this->input->post('website', TRUE)),
            'npwp' => $this->_null_if_empty($this->input->post('npwp', TRUE)),
            'rekening_atas_nama' => $this->_null_if_empty($this->input->post('rekening_atas_nama', TRUE)),
            'rekening_nomor' => $this->_null_if_empty($this->input->post('rekening_nomor', TRUE)),
            'rekening_bank' => $this->_null_if_empty($this->input->post('rekening_bank', TRUE)),
            'catatan_kuitansi' => $this->_null_if_empty($this->input->post('catatan_kuitansi', TRUE)),
            'aktif' => (int) $this->input->post('aktif', TRUE) === 1 ? 1 : 0
        );
    }

    private function _upload_file($field, $oldPath = NULL)
    {
        if (empty($_FILES[$field]['name'])) {
            return $oldPath;
        }

        $uploadDir = FCPATH . 'uploads/lembaga/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, TRUE);
        }

        $config = array(
            'upload_path' => $uploadDir,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => TRUE
        );

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors('', '')));
            redirect('pengaturan_aplikasi/edit');
            return $oldPath;
        }

        $uploaded = $this->upload->data();
        $newPath = 'uploads/lembaga/' . $uploaded['file_name'];

        if (!empty($oldPath)) {
            $oldFullPath = FCPATH . ltrim($oldPath, '/');
            if (is_file($oldFullPath)) {
                @unlink($oldFullPath);
            }
        }

        return $newPath;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('kode_lembaga', 'Kode Lembaga', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('nama_lembaga', 'Nama Lembaga', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[120]');
        $this->form_validation->set_rules('website', 'Website', 'trim|max_length[120]');
        $this->form_validation->set_rules('aktif', 'Status Aktif', 'trim|in_list[0,1]');
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
