<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{
	protected $table = 'users';

	private function _apply_search($search = '')
	{
		$search = trim((string) $search);
		if ($search === '') {
			return;
		}

		$this->db->group_start();
		$this->db->like('nama_lengkap', $search);
		$this->db->or_like('username', $search);
		$this->db->or_like('email', $search);
		$this->db->or_like('role', $search);
		$this->db->group_end();
	}

	public function get_all()
	{
		return $this->db
			->order_by('id', 'DESC')
			->get($this->table)
			->result();
	}

	public function get_by_id($id)
	{
		return $this->db
			->where('id', (int) $id)
			->get($this->table)
			->row();
	}

	public function count_filtered($search = '')
	{
		$this->db->from($this->table);
		$this->_apply_search($search);
		return (int) $this->db->count_all_results();
	}

	public function get_paginated($limit, $offset, $search = '')
	{
		$this->db->from($this->table);
		$this->_apply_search($search);
		return $this->db
			->order_by('id', 'DESC')
			->limit((int) $limit, (int) $offset)
			->get()
			->result();
	}

	/**
	 * Get statistics for dashboard cards
	 */
	public function get_statistics()
	{
		$stats = array(
			'total' => 0,
			'super_admin' => 0,
			'amil' => 0,
			'operator' => 0,
			'aktif' => 0,
			'nonaktif' => 0
		);

		// Total
		$stats['total'] = $this->db->count_all($this->table);

		// Count by role
		$this->db->select('role, COUNT(*) as count');
		$this->db->group_by('role');
		$role_counts = $this->db->get($this->table)->result();
		foreach ($role_counts as $row) {
			if (isset($stats[$row->role])) {
				$stats[$row->role] = (int) $row->count;
			}
		}

		// Count by status
		$this->db->select('is_active, COUNT(*) as count');
		$this->db->group_by('is_active');
		$status_counts = $this->db->get($this->table)->result();
		foreach ($status_counts as $row) {
			if ((int) $row->is_active === 1) {
				$stats['aktif'] = (int) $row->count;
			} else {
				$stats['nonaktif'] = (int) $row->count;
			}
		}

		return $stats;
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function update($id, $data)
	{
		return $this->db
			->where('id', (int) $id)
			->update($this->table, $data);
	}

	public function delete($id)
	{
		return $this->db
			->where('id', (int) $id)
			->delete($this->table);
	}

	public function exists_username($username, $excludeId = NULL)
	{
		$this->db->from($this->table)->where('username', $username);
		if ($excludeId !== NULL) {
			$this->db->where('id !=', (int) $excludeId);
		}

		return $this->db->count_all_results() > 0;
	}

	public function exists_email($email, $excludeId = NULL)
	{
		if ($email === NULL || $email === '') {
			return FALSE;
		}

		$this->db->from($this->table)->where('email', $email);
		if ($excludeId !== NULL) {
			$this->db->where('id !=', (int) $excludeId);
		}

		return $this->db->count_all_results() > 0;
	}
}
