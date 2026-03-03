<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model', 'dashboard');
		if ($this->session->userdata('is_logged_in') !== TRUE) {
			redirect('login');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$summary = $this->dashboard->get_summary();

		$data = array(
			'page_title' => 'Dashboard',
			'content_view' => 'dashboard/index',
			'summary' => $summary,
			'monthly_chart' => $this->dashboard->get_monthly_chart_data(6),
			'recent_fitrah' => $this->dashboard->get_recent_fitrah(5),
			'recent_penyaluran' => $this->dashboard->get_recent_penyaluran(5),
			'recent_infaq_shodaqoh' => $this->dashboard->get_recent_infaq_shodaqoh(5)
		);

		$this->load->view('layouts/adminlte', $data);
	}
}
