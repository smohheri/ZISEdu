<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infaq_shodaqoh extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Infaq_shodaqoh_model', 'infaq_shodaqoh');
		$this->_require_login();
	}

	public function index()
	{
		$search = trim((string) $this->input->get('q', TRUE));
		$per_page = 10;
		$total_filtered = $this->infaq_shodaqoh->count_filtered($search);
		$paging = zisedu_build_paging(array(
			'base_url' => site_url('infaq_shodaqoh'),
			'total_rows' => $total_filtered,
			'per_page' => $per_page,
			'page_query_string' => TRUE,
			'query_string_segment' => 'page',
			'reuse_query_string' => TRUE
		));

		$data = array(
			'page_title' => 'Infaq & Shodaqoh',
			'content_view' => 'infaq_shodaqoh/index',
			'rows' => $this->infaq_shodaqoh->get_paginated($paging['limit'], $paging['offset'], $search),
			'stats' => $this->infaq_shodaqoh->get_statistics(),
			'paging' => $paging,
			'paging_links' => $paging['links'],
			'search' => $search
		);

		$this->load->view('layouts/adminlte', $data);
	}

    public function create()
    {
        $this->load->model('Muzakki_model');
        $data = array(
            'page_title' => 'Tambah Transaksi Infaq & Shodaqoh',
            'content_view' => 'infaq_shodaqoh/form',
            'form_action' => 'infaq_shodaqoh/store',
            'auto_nomor' => $this->infaq_shodaqoh->generate_next_nomor(),
            'muzakki_options' => $this->Muzakki_model->get_all()
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
			$nomor = $this->infaq_shodaqoh->generate_next_nomor($this->input->post('tanggal_transaksi', TRUE));
		}

		if ($this->infaq_shodaqoh->exists_nomor($nomor)) {
			$nomor = $this->infaq_shodaqoh->generate_next_nomor($this->input->post('tanggal_transaksi', TRUE));
			if ($this->infaq_shodaqoh->exists_nomor($nomor)) {
				$this->session->set_flashdata('error', 'Gagal membuat nomor transaksi otomatis.');
				return $this->create();
			}
		}

		$payload = $this->_build_payload($nomor);
		$this->infaq_shodaqoh->insert($payload);
		$infaqId = (int) $this->db->insert_id();

		if ($infaqId > 0 && $this->db->field_exists('no_kwitansi', 'infaq_shodaqoh')) {
			$this->infaq_shodaqoh->update($infaqId, array(
				'no_kwitansi' => $this->_generate_no_kwitansi($infaqId, $payload['tanggal_transaksi'])
			));
		}

		$this->session->set_flashdata('success', 'Transaksi infaq/shodaqoh berhasil ditambahkan.');
		redirect('infaq_shodaqoh');
	}

	public function edit($id = NULL)
	{
		$row = $this->infaq_shodaqoh->get_by_id($id);
		if (!$row) {
			show_404();
		}

        $this->load->model('Muzakki_model');
        $data = array(
            'page_title' => 'Edit Transaksi Infaq & Shodaqoh',
            'content_view' => 'infaq_shodaqoh/form',
            'form_action' => 'infaq_shodaqoh/update/' . (int) $row->id,
            'row' => $row,
            'muzakki_options' => $this->Muzakki_model->get_all()
        );

		$this->load->view('layouts/adminlte', $data);
	}

	public function update($id = NULL)
	{
		if ($this->input->method(TRUE) !== 'POST') {
			show_404();
		}

		$row = $this->infaq_shodaqoh->get_by_id($id);
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

		if ($this->infaq_shodaqoh->exists_nomor($nomor, $id)) {
			$this->session->set_flashdata('error', 'Nomor transaksi sudah digunakan.');
			return $this->edit($id);
		}

		$payload = $this->_build_payload($nomor, FALSE);
		$this->infaq_shodaqoh->update($id, $payload);

		$this->session->set_flashdata('success', 'Transaksi infaq/shodaqoh berhasil diperbarui.');
		redirect('infaq_shodaqoh');
	}

	public function delete($id = NULL)
	{
		$row = $this->infaq_shodaqoh->get_by_id($id);
		if (!$row) {
			show_404();
		}

		$this->infaq_shodaqoh->delete($id);
		$this->session->set_flashdata('success', 'Transaksi infaq/shodaqoh berhasil dihapus.');
		redirect('infaq_shodaqoh');
	}

    public function kwitansi($id = NULL)
    {
        $row = $this->infaq_shodaqoh->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();

        $noKwitansi = $this->_resolve_no_kwitansi($row);
        $namaPenerima = trim((string) $this->session->userdata('nama_lengkap'));
        if ($namaPenerima === '') {
            $namaPenerima = trim((string) $this->session->userdata('username'));
        }

        $data = array(
            'page_title' => 'Kwitansi Penerimaan Infaq / Shodaqoh',
            'row' => $row,
            'no_kwitansi' => $noKwitansi,
            'lembaga' => $lembaga,
            'nama_penerima' => $namaPenerima
        );

        $this->load->view('infaq_shodaqoh/kwitansi', $data);
    }

    public function export_pdf($id = NULL)
    {
        $row = $this->infaq_shodaqoh->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();

        $noKwitansi = $this->_resolve_no_kwitansi($row);

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
            'nama_penerima' => $namaPenerima,
            'logo_image_src' => $logoImageSrc
        );

        $vendorAutoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($vendorAutoload)) {
            show_error('Autoload Composer tidak ditemukan. Pastikan dependency mPDF sudah terpasang.');
        }

        require_once $vendorAutoload;

        $html = $this->load->view('infaq_shodaqoh/kwitansi_pdf', $viewData, TRUE);
        $mpdf = new \Mpdf\Mpdf(array(
            'format' => array(210, 139),
            'margin_left' => 6,
            'margin_right' => 6,
            'margin_top' => 6,
            'margin_bottom' => 4,
            'autoPageBreak' => false
        ));
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->SetTitle('Kwitansi Infaq/Shodaqoh - ' . $row->nomor_transaksi);
        $mpdf->WriteHTML($html);

        $filename = 'kwitansi-infaq-shodaqoh-' . (int) $row->id . '.pdf';
        $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
    }

	private function _build_payload($nomor, $includeCreatedBy = TRUE)
	{
		$payload = array(
			'nomor_transaksi' => $nomor,
			'tanggal_transaksi' => (string) $this->input->post('tanggal_transaksi', TRUE),
			'jenis_dana' => (string) $this->input->post('jenis_dana', TRUE),
			'nama_donatur' => trim((string) $this->input->post('nama_donatur', TRUE)),
			'no_hp' => $this->_null_if_empty($this->input->post('no_hp', TRUE)),
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

	private function _generate_no_kwitansi($id, $tanggal)
	{
		$tanggal = !empty($tanggal) ? $tanggal : date('Y-m-d');
		return 'KW/IS/' . date('Y', strtotime($tanggal)) . '/' . str_pad((string) ((int) $id), 4, '0', STR_PAD_LEFT);
	}

    private function _resolve_no_kwitansi($row)
    {
        $existing = isset($row->no_kwitansi) ? trim((string) $row->no_kwitansi) : '';
        if ($existing !== '') {
            return $existing;
        }

        $generated = $this->_generate_no_kwitansi((int) $row->id, isset($row->tanggal_transaksi) ? $row->tanggal_transaksi : NULL);

        if ($this->db->field_exists('no_kwitansi', 'infaq_shodaqoh')) {
            $this->infaq_shodaqoh->update((int) $row->id, array('no_kwitansi' => $generated));
        }

        return $generated;
    }

    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return $value === '' ? NULL : $value;
    }

	private function _set_form_rules()
	{
		$this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|max_length[40]');
		$this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required');
		$this->form_validation->set_rules('jenis_dana', 'Jenis Dana', 'trim|required|in_list[infaq,shodaqoh,infaq_shodaqoh]');
		$this->form_validation->set_rules('nama_donatur', 'Nama Donatur', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('no_hp', 'No HP', 'trim|max_length[25]');
		$this->form_validation->set_rules('nominal_uang', 'Nominal Uang', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('metode_bayar', 'Metode Bayar', 'trim|required|in_list[tunai,transfer,qris,lainnya]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[draft,diterima,batal]');
	}

	private function _require_login()
	{
		if ($this->session->userdata('is_logged_in') !== TRUE) {
			redirect('login');
		}
	}
}
