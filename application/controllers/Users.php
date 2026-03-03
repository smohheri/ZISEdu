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
		$search = trim((string) $this->input->get('q', TRUE));
		$per_page = 10;
		$total_filtered = $this->users->count_filtered($search);
		$paging = zisedu_build_paging(array(
			'base_url' => site_url('users'),
			'total_rows' => $total_filtered,
			'per_page' => $per_page,
			'page_query_string' => TRUE,
			'query_string_segment' => 'page',
			'reuse_query_string' => TRUE
		));

		$data = array(
			'page_title' => 'Users',
			'content_view' => 'users/index',
			'rows' => $this->users->get_paginated($paging['limit'], $paging['offset'], $search),
			'stats' => $this->users->get_statistics(),
			'paging' => $paging,
			'paging_links' => $paging['links'],
			'search' => $search
		);

		$this->load->view('layouts/adminlte', $data);
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
