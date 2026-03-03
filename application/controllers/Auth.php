<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function index()
	{
		if ($this->session->userdata('is_logged_in') === TRUE) {
			redirect('welcome');
		}

		$data = array(
			'page_title' => 'Login - ZISEdu'
		);

		$this->load->view('auth/login', $data);
	}

	public function login()
	{
		if ($this->input->method(TRUE) !== 'POST') {
			redirect('login');
			return;
		}

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$username = $this->input->post('username', TRUE);
			$password = (string) $this->input->post('password', FALSE);

			$user = $this->db
				->group_start()
				->where('username', $username)
				->or_where('email', $username)
				->group_end()
				->where('is_active', 1)
				->get('users')
				->row();

			if ($user && $this->_verify_password_compat($password, (string) $user->password_hash)) {
				if ($this->_should_upgrade_password_hash((string) $user->password_hash)) {
					$this->db->where('id', $user->id)->update('users', array(
						'password_hash' => password_hash($password, PASSWORD_DEFAULT)
					));
				}

				$this->session->set_userdata(array(
					'user_id' => (int) $user->id,
					'nama_lengkap' => $user->nama_lengkap,
					'username' => $user->username,
					'role' => $user->role,
					'is_logged_in' => TRUE
				));

				$this->db->where('id', $user->id)->update('users', array('last_login' => date('Y-m-d H:i:s')));
				redirect('welcome');
				return;
			}

			$this->session->set_flashdata('error', 'Username/email atau password salah.');
			redirect('login');
			return;
		}

		$this->session->set_flashdata('error', trim(strip_tags(validation_errors(' ', ' '))));
		redirect('login');
		return;
	}

	private function _verify_password_compat($plainPassword, $storedHash)
	{
		if ($storedHash === '') {
			return FALSE;
		}

		if (password_verify($plainPassword, $storedHash)) {
			return TRUE;
		}

		if (hash_equals($storedHash, $plainPassword)) {
			return TRUE;
		}

		if (strlen($storedHash) === 32 && ctype_xdigit($storedHash) && hash_equals(strtolower($storedHash), md5($plainPassword))) {
			return TRUE;
		}

		if (strlen($storedHash) === 40 && ctype_xdigit($storedHash) && hash_equals(strtolower($storedHash), sha1($plainPassword))) {
			return TRUE;
		}

		return FALSE;
	}

	private function _should_upgrade_password_hash($storedHash)
	{
		$info = password_get_info($storedHash);
		if (!isset($info['algo']) || $info['algo'] === 0) {
			return TRUE;
		}

		return password_needs_rehash($storedHash, PASSWORD_DEFAULT);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
