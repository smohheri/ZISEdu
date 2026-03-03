<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyaluran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Penyaluran_model', 'penyaluran');
        $this->_require_login();
    }

    public function index()
    {
        $data = array(
            'page_title' => 'Penyaluran',
            'content_view' => 'penyaluran/index',
            'rows' => $this->penyaluran->get_all()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Penyaluran',
            'content_view' => 'penyaluran/form',
            'form_action' => 'penyaluran/store',
            'mustahik_options' => $this->penyaluran->get_mustahik_options(),
            'auto_nomor' => $this->penyaluran->generate_next_nomor(),
            'detail_rows' => array()
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

        $nomor = trim((string) $this->input->post('nomor_penyaluran', TRUE));
        if ($nomor === '') {
            $nomor = $this->penyaluran->generate_next_nomor($this->input->post('tanggal_penyaluran', TRUE));
        }

        if ($this->penyaluran->exists_nomor($nomor)) {
            $nomor = $this->penyaluran->generate_next_nomor($this->input->post('tanggal_penyaluran', TRUE));
            if ($this->penyaluran->exists_nomor($nomor)) {
                $this->session->set_flashdata('error', 'Gagal membuat nomor penyaluran otomatis.');
                return $this->create();
            }
        }

        $payload = $this->_build_payload($nomor);
        $detailRows = $this->_collect_detail_rows();

        $this->db->trans_begin();
        $this->penyaluran->insert($payload);
        $penyaluranId = (int) $this->db->insert_id();
        $this->penyaluran->replace_detail_rows($penyaluranId, $detailRows);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan data penyaluran.');
            return $this->create();
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Data penyaluran berhasil ditambahkan.');
        redirect('penyaluran');
    }

    public function edit($id = NULL)
    {
        $row = $this->penyaluran->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Penyaluran',
            'content_view' => 'penyaluran/form',
            'form_action' => 'penyaluran/update/' . (int) $row->id,
            'row' => $row,
            'mustahik_options' => $this->penyaluran->get_mustahik_options(),
            'detail_rows' => $this->penyaluran->get_detail_rows($row->id)
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function detail($id = NULL)
    {
        return $this->edit($id);
    }

    public function update($id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $row = $this->penyaluran->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->_set_form_rules();
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $nomor = trim((string) $this->input->post('nomor_penyaluran', TRUE));
        if ($nomor === '') {
            $this->session->set_flashdata('error', 'Nomor penyaluran wajib terisi.');
            return $this->edit($id);
        }

        if ($this->penyaluran->exists_nomor($nomor, $id)) {
            $this->session->set_flashdata('error', 'Nomor penyaluran sudah digunakan.');
            return $this->edit($id);
        }

        $payload = $this->_build_payload($nomor, FALSE);
        $detailRows = $this->_collect_detail_rows();

        $this->db->trans_begin();
        $this->penyaluran->update($id, $payload);
        $this->penyaluran->replace_detail_rows($id, $detailRows);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal memperbarui data penyaluran.');
            return $this->edit($id);
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Data penyaluran berhasil diperbarui.');
        redirect('penyaluran');
    }

    public function delete($id = NULL)
    {
        $row = $this->penyaluran->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->penyaluran->delete($id);
        $this->session->set_flashdata('success', 'Data penyaluran berhasil dihapus.');
        redirect('penyaluran');
    }

    private function _build_payload($nomor, $includeCreatedBy = TRUE)
    {
        $payload = array(
            'nomor_penyaluran' => $nomor,
            'tanggal_penyaluran' => (string) $this->input->post('tanggal_penyaluran', TRUE),
            'jenis_sumber' => (string) $this->input->post('jenis_sumber', TRUE),
            'total_uang' => (float) $this->input->post('total_uang', TRUE),
            'total_beras_kg' => (float) $this->input->post('total_beras_kg', TRUE),
            'keterangan' => $this->_null_if_empty($this->input->post('keterangan', TRUE)),
            'status' => (string) $this->input->post('status', TRUE)
        );

        if ($includeCreatedBy) {
            $payload['created_by'] = (int) $this->session->userdata('user_id');
        }

        return $payload;
    }

    private function _collect_detail_rows()
    {
        $details = (array) $this->input->post('detail');
        $rows = array();

        foreach ($details as $d) {
            $mustahikId = isset($d['mustahik_id']) ? (int) $d['mustahik_id'] : 0;
            $bentuk = isset($d['bentuk_bantuan']) ? (string) $d['bentuk_bantuan'] : '';
            $nominal = isset($d['nominal_uang']) ? (float) $d['nominal_uang'] : 0;
            $beras = isset($d['beras_kg']) ? (float) $d['beras_kg'] : 0;
            $ket = isset($d['keterangan']) ? trim((string) $d['keterangan']) : '';

            if ($mustahikId <= 0 && $bentuk === '' && $nominal <= 0 && $beras <= 0 && $ket === '') {
                continue;
            }

            if ($mustahikId <= 0 || !in_array($bentuk, array('uang', 'beras', 'paket'), TRUE)) {
                continue;
            }

            $rows[] = array(
                'mustahik_id' => $mustahikId,
                'bentuk_bantuan' => $bentuk,
                'nominal_uang' => max(0, $nominal),
                'beras_kg' => max(0, $beras),
                'keterangan' => $ket !== '' ? $ket : NULL
            );
        }

        return $rows;
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('nomor_penyaluran', 'Nomor Penyaluran', 'trim|max_length[40]');
        $this->form_validation->set_rules('tanggal_penyaluran', 'Tanggal Penyaluran', 'trim|required');
        $this->form_validation->set_rules('jenis_sumber', 'Jenis Sumber', 'trim|required|in_list[fitrah,mal,gabungan]');
        $this->form_validation->set_rules('total_uang', 'Total Uang', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('total_beras_kg', 'Total Beras', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[draft,disalurkan,batal]');
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
