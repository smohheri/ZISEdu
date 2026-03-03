<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model', 'users');
		$this->_require_login();
	}

	public function index()
	{
		$data = array(
			'page_title' => 'Users',
			'content_view' => 'users/index',
			'rows' => $this->users->get_all(),
			'stats' => $this->users->get_statistics()
		);

		$this->load->view('layouts/adminlte', $data);
	}

	/**
	 * AJAX handler for DataTables
	 */
	public function ajax_list()
	{
		// Check if AJAX request
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$search = $this->input->post('search', TRUE);
		$search = $search ? $search['value'] : '';

		$start = (int) $this->input->post('start', TRUE);
		$length = (int) $this->input->post('length', TRUE);
		$length = $length > 0 ? $length : 10;

		$order_column = $this->input->post('order', TRUE);
		$order_column = isset($order_column[0]['column']) ? (int) $order_column[0]['column'] : 0;
		$order_dir = isset($order_column[0]['dir']) ? $order_column[0]['dir'] : 'DESC';

		// Map column index to column name
		$columns = array('id', 'nama_lengkap', 'username', 'email', 'role', 'is_active', 'last_login');
		$order_column_name = isset($columns[$order_column]) ? $columns[$order_column] : 'id';

		$result = $this->users->get_datatables($search, $start, $length, $order_column_name, $order_dir);

		$data = array();
		foreach ($result['data'] as $row) {
			$data[] = array(
				'id' => $row->id,
				'nama_lengkap' => html_escape($row->nama_lengkap),
				'username' => html_escape($row->username),
				'email' => html_escape($row->email),
				'role' => html_escape($row->role),
				'is_active' => (int) $row->is_active,
				'last_login' => $row->last_login ? html_escape($row->last_login) : '-'
			);
		}

		echo json_encode(array(
			'draw' => (int) $this->input->post('draw', TRUE),
			'recordsTotal' => $result['total'],
			'recordsFiltered' => $result['total_filtered'],
			'data' => $data,
			'csrf_hash' => $this->security->get_csrf_hash()
		));
	}

	/**
	 * Get statistics for cards
	 */
	public function ajax_stats()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$stats = $this->users->get_statistics();
		echo json_encode($stats);
	}

	public function create()
	{
		$data = array(
			'page_title' => 'Tambah User',
			'content_view' => 'users/form',
			'form_action' => 'users/store'
		);

		$this->load->view('layouts/adminlte', $data);
	}

	public function store()
	{
		if ($this->input->method(TRUE) !== 'POST') {
			show_404();
		}

		$this->_set_form_rules();
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			return $this->create();
		}

		$username = trim((string) $this->input->post('username', TRUE));
		$email = trim((string) $this->input->post('email', TRUE));

		if ($this->users->exists_username($username)) {
			$this->session->set_flashdata('error', 'Username sudah digunakan.');
			return $this->create();
		}

		if ($this->users->exists_email($email)) {
			$this->session->set_flashdata('error', 'Email sudah digunakan.');
			return $this->create();
		}

		$payload = array(
			'nama_lengkap' => trim((string) $this->input->post('nama_lengkap', TRUE)),
			'username' => $username,
			'email' => $email !== '' ? $email : NULL,
			'password_hash' => password_hash((string) $this->input->post('password', FALSE), PASSWORD_DEFAULT),
			'role' => (string) $this->input->post('role', TRUE),
			'is_active' => (int) $this->input->post('is_active', TRUE)
		);

		$this->users->insert($payload);
		$this->session->set_flashdata('success', 'User berhasil ditambahkan.');
		redirect('users');
	}

	public function edit($id = NULL)
	{
		$row = $this->users->get_by_id($id);
		if (!$row) {
			show_404();
		}

		$data = array(
			'page_title' => 'Edit User',
			'content_view' => 'users/form',
			'form_action' => 'users/update/' . (int) $row->id,
			'row' => $row
		);

		$this->load->view('layouts/adminlte', $data);
	}

	public function update($id = NULL)
	{
		if ($this->input->method(TRUE) !== 'POST') {
			show_404();
		}

		$row = $this->users->get_by_id($id);
		if (!$row) {
			show_404();
		}

		$this->_set_form_rules();

		if ($this->form_validation->run() === FALSE) {
			return $this->edit($id);
		}

		$username = trim((string) $this->input->post('username', TRUE));
		$email = trim((string) $this->input->post('email', TRUE));

		if ($this->users->exists_username($username, $id)) {
			$this->session->set_flashdata('error', 'Username sudah digunakan.');
			return $this->edit($id);
		}

		if ($this->users->exists_email($email, $id)) {
			$this->session->set_flashdata('error', 'Email sudah digunakan.');
			return $this->edit($id);
		}

		$payload = array(
			'nama_lengkap' => trim((string) $this->input->post('nama_lengkap', TRUE)),
			'username' => $username,
			'email' => $email !== '' ? $email : NULL,
			'role' => (string) $this->input->post('role', TRUE),
			'is_active' => (int) $this->input->post('is_active', TRUE)
		);

		$password = (string) $this->input->post('password', FALSE);
		if ($password !== '') {
			if (strlen($password) < 6) {
				$this->session->set_flashdata('error', 'Password minimal 6 karakter.');
				return $this->edit($id);
			}
			$payload['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
		}

		$this->users->update($id, $payload);
		$this->session->set_flashdata('success', 'User berhasil diperbarui.');
		redirect('users');
	}

	public function delete($id = NULL)
	{
		$row = $this->users->get_by_id($id);
		if (!$row) {
			show_404();
		}

		$currentUserId = (int) $this->session->userdata('user_id');
		if ($currentUserId === (int) $row->id) {
			$this->session->set_flashdata('error', 'User yang sedang login tidak bisa dihapus.');
			redirect('users');
		}

		$this->users->delete($id);
		$this->session->set_flashdata('success', 'User berhasil dihapus.');
		redirect('users');
	}

	private function _set_form_rules()
	{
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('role', 'Role', 'trim|required|in_list[super_admin,amil,operator]');
		$this->form_validation->set_rules('is_active', 'Status', 'trim|required|in_list[0,1]');
	}

	private function _require_login()
	{
		if ($this->session->userdata('is_logged_in') !== TRUE) {
			redirect('login');
		}
	}
}
