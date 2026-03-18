<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infaq_shodaqoh_model extends CI_Model
{
	protected $table = 'infaq_shodaqoh';

	private function _apply_search($search = '')
	{
		$search = trim((string) $search);
		if ($search === '') {
			return;
		}

		$this->db->group_start()
			->like('nomor_transaksi', $search)
			->or_like('nama_donatur', $search)
			->or_like('jenis_dana', $search)
			->or_like('status', $search)
			->group_end();
	}

	public function count_all()
	{
		return (int) $this->db->count_all($this->table);
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

	public function get_statistics()
	{
		$stats = array(
			'total' => $this->count_all(),
			'infaq' => 0,
			'shodaqoh' => 0,
			'draft' => 0,
			'diterima' => 0,
			'batal' => 0,
			'total_nominal' => 0.0
		);

		$jenis_rows = $this->db
			->select('jenis_dana, COUNT(*) AS jumlah', FALSE)
			->from($this->table)
			->group_by('jenis_dana')
			->get()
			->result();
		foreach ($jenis_rows as $item) {
			$key = (string) $item->jenis_dana;
			if (isset($stats[$key])) {
				$stats[$key] = (int) $item->jumlah;
			}
		}

		$status_rows = $this->db
			->select('status, COUNT(*) AS jumlah', FALSE)
			->from($this->table)
			->group_by('status')
			->get()
			->result();
		foreach ($status_rows as $item) {
			$key = (string) $item->status;
			if (isset($stats[$key])) {
				$stats[$key] = (int) $item->jumlah;
			}
		}

		$sum = $this->db
			->select('COALESCE(SUM(nominal_uang),0) AS total_nominal', FALSE)
			->from($this->table)
			->where('status', 'diterima')
			->get()
			->row();
		if ($sum) {
			$stats['total_nominal'] = (float) $sum->total_nominal;
		}

		return $stats;
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

	public function delete($id)
	{
		return $this->db
			->where('id', (int) $id)
			->delete($this->table);
	}

	public function exists_nomor($nomor, $excludeId = NULL)
	{
		$this->db->from($this->table)->where('nomor_transaksi', $nomor);
		if ($excludeId !== NULL) {
			$this->db->where('id !=', (int) $excludeId);
		}

		return $this->db->count_all_results() > 0;
	}

	public function generate_next_nomor($date = NULL)
	{
		$date = $date ?: date('Y-m-d');
		$dateToken = date('Y', strtotime($date));
		$prefix = 'IS-' . $dateToken . '-';

		$last = $this->db
			->select('nomor_transaksi')
			->from($this->table)
			->like('nomor_transaksi', $prefix, 'after')
			->order_by('nomor_transaksi', 'DESC')
			->limit(1)
			->get()
			->row();

		$next = 1;
		if ($last && isset($last->nomor_transaksi)) {
			$parts = explode('-', $last->nomor_transaksi);
			$suffix = end($parts);
			if (is_numeric($suffix)) {
				$next = ((int) $suffix) + 1;
			}
		}

		return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
	}

	public function get_by_batch($batch_id)
	{
		return $this->db
			->where('batch_id', $batch_id)
			->get($this->table)
			->row();
	}

	public function generate_next_kwitansi($date = NULL)
	{
		$date = $date ?: date('Y-m-d');
		$dateToken = date('Y', strtotime($date));
		$prefix = 'KW-' . $dateToken . '-';

		$last = $this->db
			->select('no_kwitansi')
			->from($this->table)
			->like('no_kwitansi', $prefix, 'after')
			->order_by('no_kwitansi', 'DESC')
			->limit(1)
			->get()
			->row();

		$next = 1;
		if ($last && isset($last->no_kwitansi)) {
			$parts = explode('-', $last->no_kwitansi);
			$suffix = end($parts);
			if (is_numeric($suffix)) {
				$next = ((int) $suffix) + 1;
			}
		}

		return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
	}

	public function update_no_kwitansi($id)
	{
		$no = $this->generate_next_kwitansi();
		return $this->db
			->where('id', (int) $id)
			->update($this->table, ['no_kwitansi' => $no]);
	}
}
