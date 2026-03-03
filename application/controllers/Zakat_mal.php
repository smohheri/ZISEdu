<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zakat_mal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Zakat_mal_model', 'zakat_mal');
        $this->_require_login();
    }

    public function index()
    {
        $data = array(
            'page_title' => 'Zakat Mal',
            'content_view' => 'zakat_mal/index',
            'rows' => $this->zakat_mal->get_all()
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function create()
    {
        $data = array(
            'page_title' => 'Tambah Zakat Mal',
            'content_view' => 'zakat_mal/form',
            'form_action' => 'zakat_mal/store',
            'muzakki_options' => $this->zakat_mal->get_muzakki_options(),
            'jenis_harta_options' => $this->zakat_mal->get_jenis_harta_options(),
            'auto_nomor' => $this->zakat_mal->generate_next_nomor(),
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

        $nomor = trim((string) $this->input->post('nomor_transaksi', TRUE));
        if ($nomor === '') {
            $nomor = $this->zakat_mal->generate_next_nomor($this->input->post('tanggal_hitung', TRUE));
        }

        if ($this->zakat_mal->exists_nomor($nomor)) {
            $nomor = $this->zakat_mal->generate_next_nomor($this->input->post('tanggal_hitung', TRUE));
            if ($this->zakat_mal->exists_nomor($nomor)) {
                $this->session->set_flashdata('error', 'Gagal membuat nomor transaksi otomatis.');
                return $this->create();
            }
        }

        $payload = $this->_build_payload($nomor);
        $this->_apply_auto_calculation($payload);
        $detailRows = $this->_collect_detail_rows();

        $this->db->trans_begin();
        $this->zakat_mal->insert($payload);
        $zakatMalId = (int) $this->db->insert_id();
        $this->zakat_mal->replace_detail_rows($zakatMalId, $detailRows);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan transaksi zakat mal.');
            return $this->create();
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Transaksi zakat mal berhasil ditambahkan.');
        redirect('zakat_mal');
    }

    public function edit($id = NULL)
    {
        $row = $this->zakat_mal->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Zakat Mal',
            'content_view' => 'zakat_mal/form',
            'form_action' => 'zakat_mal/update/' . (int) $row->id,
            'row' => $row,
            'muzakki_options' => $this->zakat_mal->get_muzakki_options(),
            'jenis_harta_options' => $this->zakat_mal->get_jenis_harta_options(),
            'detail_rows' => $this->zakat_mal->get_detail_rows($row->id)
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

        $row = $this->zakat_mal->get_by_id($id);
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

        if ($this->zakat_mal->exists_nomor($nomor, $id)) {
            $this->session->set_flashdata('error', 'Nomor transaksi sudah digunakan.');
            return $this->edit($id);
        }

        $payload = $this->_build_payload($nomor, FALSE);
        $this->_apply_auto_calculation($payload);
        $detailRows = $this->_collect_detail_rows();

        $this->db->trans_begin();
        $this->zakat_mal->update($id, $payload);
        $this->zakat_mal->replace_detail_rows($id, $detailRows);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal memperbarui transaksi zakat mal.');
            return $this->edit($id);
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Transaksi zakat mal berhasil diperbarui.');
        redirect('zakat_mal');
    }

    public function delete($id = NULL)
    {
        $row = $this->zakat_mal->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->zakat_mal->delete($id);
        $this->session->set_flashdata('success', 'Transaksi zakat mal berhasil dihapus.');
        redirect('zakat_mal');
    }

    public function kwitansi($id = NULL)
    {
        $row = $this->zakat_mal->get_receipt_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();

        $tanggalKwitansi = !empty($row->tanggal_bayar)
            ? $row->tanggal_bayar
            : (!empty($row->tanggal_hitung) ? $row->tanggal_hitung : date('Y-m-d'));
        $noKwitansi = 'KW/MAL/' . date('Ym', strtotime($tanggalKwitansi)) . '/' . str_pad((string) ((int) $row->id), 4, '0', STR_PAD_LEFT);

        $detailRows = $this->zakat_mal->get_detail_rows((int) $row->id);
        $jenisHarta = $this->zakat_mal->get_jenis_harta_options();

        $data = array(
            'page_title' => 'Kwitansi Penerimaan Zakat Mal',
            'row' => $row,
            'no_kwitansi' => $noKwitansi,
            'lembaga' => $lembaga,
            'detail_rows' => $detailRows,
            'jenis_harta_options' => $jenisHarta
        );

        $this->load->view('zakat_mal/kwitansi', $data);
    }

    private function _build_payload($nomor, $includeCreatedBy = TRUE)
    {
        $payload = array(
            'nomor_transaksi' => $nomor,
            'muzakki_id' => (int) $this->input->post('muzakki_id', TRUE),
            'tanggal_hitung' => (string) $this->input->post('tanggal_hitung', TRUE),
            'tanggal_bayar' => $this->_null_if_empty($this->input->post('tanggal_bayar', TRUE)),
            'tahun_masehi' => (int) $this->input->post('tahun_masehi', TRUE),
            'total_harta' => (float) $this->input->post('total_harta', TRUE),
            'total_hutang_jatuh_tempo' => (float) $this->input->post('total_hutang_jatuh_tempo', TRUE),
            'harta_bersih' => (float) $this->input->post('harta_bersih', TRUE),
            'nilai_nishab' => (float) $this->input->post('nilai_nishab', TRUE),
            'persentase_zakat' => (float) $this->input->post('persentase_zakat', TRUE),
            'total_zakat' => (float) $this->input->post('total_zakat', TRUE),
            'metode_bayar' => (string) $this->input->post('metode_bayar', TRUE),
            'keterangan' => $this->_null_if_empty($this->input->post('keterangan', TRUE)),
            'status' => (string) $this->input->post('status', TRUE)
        );

        if ($includeCreatedBy) {
            $payload['created_by'] = (int) $this->session->userdata('user_id');
        }

        return $payload;
    }

    private function _apply_auto_calculation(array &$payload)
    {
        $totalHarta = max(0, (float) $payload['total_harta']);
        $totalHutang = max(0, (float) $payload['total_hutang_jatuh_tempo']);
        $nilaiNishab = max(0, (float) $payload['nilai_nishab']);
        $persentase = max(0, (float) $payload['persentase_zakat']);

        $hartaBersih = max(0, $totalHarta - $totalHutang);
        $totalZakat = ($hartaBersih >= $nilaiNishab)
            ? round(($hartaBersih * $persentase) / 100, 2)
            : 0;

        $payload['harta_bersih'] = $hartaBersih;
        $payload['total_zakat'] = $totalZakat;
    }

    private function _collect_detail_rows()
    {
        $details = (array) $this->input->post('detail');
        $rows = array();

        foreach ($details as $d) {
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

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

    private function _set_form_rules()
    {
        $this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|max_length[40]');
        $this->form_validation->set_rules('muzakki_id', 'Muzakki', 'trim|required|integer');
        $this->form_validation->set_rules('tanggal_hitung', 'Tanggal Hitung', 'trim|required');
        $this->form_validation->set_rules('tahun_masehi', 'Tahun Masehi', 'trim|required|integer');
        $this->form_validation->set_rules('total_harta', 'Total Harta', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('total_hutang_jatuh_tempo', 'Total Hutang Jatuh Tempo', 'trim|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('harta_bersih', 'Harta Bersih', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('nilai_nishab', 'Nilai Nishab', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('persentase_zakat', 'Persentase Zakat', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('total_zakat', 'Total Zakat', 'trim|required|numeric|greater_than_equal_to[0]');
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
