<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_aplikasi_model extends CI_Model
{
	protected $table = 'pengaturan_aplikasi';

	public function get_first()
	{
		return $this->db
			->order_by('id', 'ASC')
			->limit(1)
			->get($this->table)
			->row();
	}

	public function get_by_id($id)
	{
		return $this->db
			->where('id', (int) $id)
			->get($this->table)
			->row();
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
}
