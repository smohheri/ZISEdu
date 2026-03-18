<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_unified extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Zakat_fitrah_model', 'zakat_fitrah');
		$this->load->model('Zakat_mal_model', 'zakat_mal');
		$this->load->model('Infaq_shodaqoh_model', 'infaq_shodaqoh');
		$this->load->model('Muzakki_model', 'muzakki');
		$this->_require_login();
	}

	public function index()
	{
		$this->create();
	}

	public function create()
	{
		$data = array(
			'page_title' => 'Transaksi Terpadu - Gabungan Zakat & Infaq',
			'content_view' => 'transaksi_unified/form',
			'muzakki_options' => $this->zakat_fitrah->get_muzakki_options(),
			'jenis_harta_options' => $this->zakat_mal->get_jenis_harta_options(),
		);
		$this->load->view('layouts/adminlte', $data);
	}

	public function store()
	{
		if ($this->input->method() !== 'POST') {
			show_404();
		}

		$this->_set_form_rules();
		if ($this->form_validation->run() === FALSE) {
			return $this->create();
		}

		// Generate unique batch_id
		$batch_id = 'BATCH-' . date('YmdHis') . '-' . rand(1000, 9999);

		$transactions = array();
		$success_count = 0;

		// Handle Zakat Fitrah if selected
		if ($this->input->post('include_fitrah')) {
			$nomor = $this->zakat_fitrah->generate_next_nomor($this->input->post('tanggal_transaksi_fitrah'));
			$payload_fitrah = $this->_build_zakat_fitrah_payload($nomor, $batch_id);
			if ($this->zakat_fitrah->insert($payload_fitrah)) {
				$fitrah_id = $this->db->insert_id();
				$this->zakat_fitrah->update_no_kwitansi($fitrah_id); // Assume method exists or add
				$transactions['fitrah'] = array('id' => $fitrah_id, 'nomor' => $nomor, 'payload' => $payload_fitrah);
				$success_count++;
			}
		}

		// Handle Zakat Mal if selected
		if ($this->input->post('include_mal')) {
			$nomor = $this->zakat_mal->generate_next_nomor($this->input->post('tanggal_transaksi_mal'));
			$payload_mal = $this->_build_zakat_mal_payload($nomor, $batch_id);
			if ($this->zakat_mal->insert($payload_mal)) {
				$mal_id = $this->db->insert_id();
				$this->zakat_mal->update_no_kwitansi($mal_id);
				// Handle detail
				$details = array();
				foreach ($_POST['detail_mal'] ?? [] as $row) {
					if (!empty($row['jenis_harta_id']) && (float) $row['nilai_harta'] > 0) {
						$details[] = array(
							'jenis_harta_id' => (int) $row['jenis_harta_id'],
							'nilai_harta' => (float) $row['nilai_harta'],
							'nilai_haul_bulan' => (int) $row['nilai_haul_bulan'],
							'keterangan' => trim((string) $row['keterangan'])
						);
					}
				}
				$this->zakat_mal->replace_detail_rows($mal_id, $details);
				$transactions['mal'] = array('id' => $mal_id, 'nomor' => $nomor, 'payload' => $payload_mal);
				$success_count++;
			}
		}

		// Handle Infaq Shodaqoh if selected
		if ($this->input->post('include_infaq')) {
			$nomor = $this->infaq_shodaqoh->generate_next_nomor($this->input->post('tanggal_transaksi_infaq'));
			$payload_infaq = $this->_build_infaq_payload($nomor, $batch_id);
			if ($this->infaq_shodaqoh->insert($payload_infaq)) {
				$infaq_id = $this->db->insert_id();
				$this->infaq_shodaqoh->update_no_kwitansi($infaq_id);
				$transactions['infaq'] = array('id' => $infaq_id, 'nomor' => $nomor, 'payload' => $payload_infaq);
				$success_count++;
			}
		}

		if ($success_count > 0) {
			$this->session->set_flashdata('success', $success_count . ' transaksi berhasil disimpan.');
			redirect('transaksi_unified/kwitansi/' . $batch_id);
		} else {
			$this->session->set_flashdata('error', 'Gagal menyimpan transaksi.');
			redirect('transaksi_unified/create');
		}
	}

	public function kwitansi($batch_id = NULL)
	{
		if (empty($batch_id)) {
			show_404();
		}

		$this->load->model('Pengaturan_aplikasi_model', 'pengaturan');
		$lembaga = $this->pengaturan->get_first();

		// Fetch transactions for this batch
		$fitrah = $this->zakat_fitrah->get_by_batch($batch_id);
		$mal = $this->zakat_mal->get_by_batch($batch_id);
		$infaq = $this->infaq_shodaqoh->get_by_batch($batch_id);

		$data = array(
			'page_title' => 'Kwitansi Gabungan - ' . $batch_id,
			'batch_id' => $batch_id,
			'lembaga' => $lembaga,
			'fitrah' => $fitrah,
			'mal' => $mal,
			'infaq' => $infaq,
			'nama_penerima' => $this->session->userdata('nama_lengkap') ?: $this->session->userdata('username'),
		);

		$this->load->view('transaksi_unified/kwitansi', $data);
	}

	// Placeholder payload builders - will refine after model updates
	private function _build_zakat_fitrah_payload($nomor, $batch_id)
	{
		return array(
			'nomor_transaksi' => $nomor,
			'tanggal_bayar' => $this->input->post('shared_tanggal_bayar') ?: $this->input->post('tanggal_bayar_fitrah'),
			'tahun_masehi' => $this->input->post('tahun_masehi') ?: $this->input->post('tahun_masehi_fitrah'),
			'muzakki_id' => $this->input->post('muzakki_id'),
			'jumlah_jiwa' => $this->input->post('jumlah_jiwa_fitrah'),
			'metode_tunaikan' => $this->input->post('metode_tunaikan_fitrah'),
			'beras_kg' => $this->input->post('beras_kg_fitrah'),
			'nominal_uang' => $this->input->post('nominal_uang_fitrah'),
			'metode_bayar' => $this->input->post('shared_metode_bayar') ?: $this->input->post('metode_bayar_fitrah'),
			'status' => $this->input->post('shared_status') ?: $this->input->post('status_fitrah') ?: 'lunas',
			'keterangan' => $this->input->post('keterangan_fitrah'),
			'batch_id' => $batch_id,
			'created_by' => $this->session->userdata('user_id'),
		);
	}

	private function _build_zakat_mal_payload($nomor, $batch_id)
	{
		$payload = array(
			'nomor_transaksi' => $nomor,
			'tanggal_hitung' => $this->input->post('tanggal_hitung_mal'),
			'tanggal_bayar' => $this->input->post('shared_tanggal_bayar') ?: $this->input->post('tanggal_bayar_mal'),
			'muzakki_id' => $this->input->post('muzakki_id'),
			'tahun_masehi' => $this->input->post('tahun_masehi') ?: $this->input->post('tahun_masehi_mal'),
			'mode_perhitungan' => $this->input->post('mode_perhitungan_mal'),
			'metode_bayar' => $this->input->post('shared_metode_bayar') ?: $this->input->post('metode_bayar_mal'),
			'total_harta' => $this->input->post('total_harta_mal'),
			'total_hutang_jatuh_tempo' => $this->input->post('total_hutang_jatuh_tempo_mal'),
			'harta_bersih' => $this->input->post('harta_bersih_mal'),
			'nilai_nishab' => $this->input->post('nilai_nishab_mal'),
			'persentase_zakat' => $this->input->post('persentase_zakat_mal'),
			'total_zakat' => $this->input->post('total_zakat_mal'),
			'status' => $this->input->post('shared_status') ?: $this->input->post('status_mal') ?: 'lunas',
			'keterangan' => $this->input->post('keterangan_mal'),
			'batch_id' => $batch_id,
			'created_by' => $this->session->userdata('user_id'),
		);
		// Detail later in store after insert main
		return $payload;
	}

	private function _build_infaq_payload($nomor, $batch_id)
	{
		$muzakki_id = (int) $this->input->post('muzakki_id');
		$muzakki = $this->muzakki->get_by_id($muzakki_id);
		$muzakki_kode = $muzakki ? $muzakki->kode_muzakki : NULL;
		$nama_muzakki = $muzakki ? $muzakki->nama : '';

		return array(
			'nomor_transaksi' => $nomor,
			'tanggal_transaksi' => $this->input->post('tanggal_transaksi_infaq'),
			'jenis_dana' => $this->input->post('jenis_dana_infaq'),
			'muzakki_kode' => $muzakki_kode,
			'nama_donatur' => $nama_muzakki,
			'no_hp' => $this->input->post('no_hp_infaq'),
			'nominal_uang' => $this->input->post('nominal_uang_infaq'),
			'metode_bayar' => $this->input->post('metode_bayar_infaq'),
			'status' => $this->input->post('status_infaq') ?: 'diterima',
			'keterangan' => $this->input->post('keterangan_infaq'),
			'batch_id' => $batch_id,
			'created_by' => $this->session->userdata('user_id'),
		);
	}

	private function _set_form_rules()
	{
		$this->form_validation->set_rules('muzakki_id', 'Muzakki', 'trim|required|integer');
		$this->form_validation->set_rules('include_fitrah', 'Zakat Fitrah', 'integer');
		$this->form_validation->set_rules('include_mal', 'Zakat Mal', 'integer');
		$this->form_validation->set_rules('include_infaq', 'Infaq Shodaqoh', 'integer');

		if ($this->input->post('include_fitrah')) {
			$this->form_validation->set_rules('tanggal_bayar_fitrah', 'Tanggal Bayar Fitrah', 'required');
			$this->form_validation->set_rules('jumlah_jiwa_fitrah', 'Jumlah Jiwa Fitrah', 'required|numeric|greater_than[0]');
			$this->form_validation->set_rules('nominal_uang_fitrah', 'Nominal Uang Fitrah', 'numeric|greater_than_equal_to[0]');
		}

		if ($this->input->post('include_mal')) {
			$this->form_validation->set_rules('tanggal_hitung_mal', 'Tanggal Hitung Mal', 'required');
			$this->form_validation->set_rules('total_zakat_mal', 'Total Zakat Mal', 'required|numeric|greater_than[0]');
			$this->form_validation->set_rules('total_harta_mal', 'Total Harta Mal', 'numeric|greater_than_equal_to[0]');
		}

		if ($this->input->post('include_infaq')) {
			$this->form_validation->set_rules('nama_donatur_infaq', 'Nama Donatur Infaq', 'required|trim|max_length[255]');
			$this->form_validation->set_rules('nominal_uang_infaq', 'Nominal Uang Infaq', 'required|numeric|greater_than[0]');
		}
	}

	public function muzakki_info($muzakki_id = 0)
	{
		if ((int) $muzakki_id <= 0) {
			echo json_encode(array('error' => 'Invalid muzakki ID'));
			return;
		}

		$info = $this->zakat_fitrah->get_muzakki_info((int) $muzakki_id);
		if (!$info) {
			echo json_encode(array('error' => 'Muzakki not found'));
			return;
		}

		echo json_encode($info);
	}

	private function _require_login()
	{
		if (!$this->session->userdata('is_logged_in')) {
			redirect('login');
		}
	}
}
?>