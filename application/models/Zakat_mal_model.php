<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zakat_mal_model extends CI_Model
{
	protected $table = 'zakat_mal';

	private function _base_query()
	{
		return $this->db
			->select('zm.*, m.nama AS nama_muzakki')
			->from($this->table . ' zm')
			->join('muzakki m', 'm.id = zm.muzakki_id', 'inner');
	}

	private function _apply_search($search = '')
	{
		$search = trim((string) $search);
		if ($search === '') {
			return;
		}

		$this->db->group_start()
			->like('zm.nomor_transaksi', $search)
			->or_like('m.nama', $search)
			->or_like('zm.status', $search)
			->group_end();
	}

	public function get_all()
	{
		return $this->_base_query()
			->order_by('zm.id', 'DESC')
			->get()
			->result();
	}

	public function count_all()
	{
		return (int) $this->db->count_all($this->table);
	}

	public function count_filtered($search = '')
	{
		$this->_base_query();
		$this->_apply_search($search);
		return (int) $this->db->count_all_results();
	}

	public function get_paginated($limit, $offset, $search = '')
	{
		$this->_base_query();
		$this->_apply_search($search);
		return $this->db
			->order_by('zm.id', 'DESC')
			->limit((int) $limit, (int) $offset)
			->get()
			->result();
	}

	public function get_statistics()
	{
		$stats = array(
			'total' => $this->count_all(),
			'lunas' => 0,
			'draft' => 0,
			'batal' => 0,
			'total_zakat' => 0.0,
			'total_harta_bersih' => 0.0
		);

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

		$sum_row = $this->db
			->select('COALESCE(SUM(total_zakat),0) AS total_zakat, COALESCE(SUM(harta_bersih),0) AS total_harta_bersih', FALSE)
			->from($this->table)
			->get()
			->row();
		if ($sum_row) {
			$stats['total_zakat'] = (float) $sum_row->total_zakat;
			$stats['total_harta_bersih'] = (float) $sum_row->total_harta_bersih;
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

	public function get_receipt_by_id($id)
	{
		return $this->db
			->select('zm.*, m.kode_muzakki, m.nama AS nama_muzakki')
			->from($this->table . ' zm')
			->join('muzakki m', 'm.id = zm.muzakki_id', 'left')
			->where('zm.id', (int) $id)
			->get()
			->row();
	}

	public function get_detail_rows($zakatMalId)
	{
		return $this->db
			->where('zakat_mal_id', (int) $zakatMalId)
			->order_by('id', 'ASC')
			->get('zakat_mal_detail')
			->result();
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

	public function replace_detail_rows($zakatMalId, array $rows)
	{
		$this->db->where('zakat_mal_id', (int) $zakatMalId)->delete('zakat_mal_detail');

		if (empty($rows)) {
			return TRUE;
		}

		foreach ($rows as &$row) {
			$row['zakat_mal_id'] = (int) $zakatMalId;
		}
		unset($row);

		return $this->db->insert_batch('zakat_mal_detail', $rows);
	}

	public function exists_nomor($nomor, $excludeId = NULL)
	{
		$this->db->from($this->table)->where('nomor_transaksi', $nomor);
		if ($excludeId !== NULL) {
			$this->db->where('id !=', (int) $excludeId);
		}

		return $this->db->count_all_results() > 0;
	}

	public function get_muzakki_options()
	{
		$rows = $this->db
			->select('id, kode_muzakki, nama')
			->from('muzakki')
			->order_by('nama', 'ASC')
			->get()
			->result();

		$options = array();
		foreach ($rows as $r) {
			$options[$r->id] = $r->kode_muzakki . ' - ' . $r->nama;
		}

		return $options;
	}

	public function get_jenis_harta_options()
	{
		$rows = $this->db
			->select('id, kode_jenis, nama_jenis')
			->from('jenis_harta_mal')
			->where('aktif', 1)
			->order_by('nama_jenis', 'ASC')
			->get()
			->result();

		$options = array();
		foreach ($rows as $r) {
			$options[$r->id] = $r->kode_jenis . ' - ' . $r->nama_jenis;
		}

		return $options;
	}

	public function generate_next_nomor($date = NULL)
	{
		$date = $date ?: date('Y-m-d');
		$dateToken = date('Ymd', strtotime($date));
		$prefix = 'ZM-' . $dateToken . '-';

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
}
