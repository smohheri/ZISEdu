<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zakat_fitrah_model extends CI_Model
{
	protected $table = 'zakat_fitrah';

	private function _base_query()
	{
		return $this->db
			->select('zf.*, m.nama AS nama_muzakki')
			->from($this->table . ' zf')
			->join('muzakki m', 'm.id = zf.muzakki_id', 'inner');
	}

	private function _apply_search($search = '')
	{
		$search = trim((string) $search);
		if ($search === '') {
			return;
		}

		$this->db->group_start()
			->like('zf.nomor_transaksi', $search)
			->or_like('m.nama', $search)
			->or_like('zf.metode_tunaikan', $search)
			->or_like('zf.status', $search)
			->group_end();
	}

	public function get_muzakki_info($muzakkiId)
	{
		$muzakki = $this->db
			->select('id, jenis_muzakki')
			->from('muzakki')
			->where('id', (int) $muzakkiId)
			->get()
			->row();

		if (!$muzakki) {
			return NULL;
		}

		$jumlahTanggunganAktif = (int) $this->db
			->from('tanggungan_fitrah')
			->where('muzakki_id', (int) $muzakkiId)
			->where('aktif_dihitung', 1)
			->count_all_results();

		$jumlahJiwaOtomatis = 1;
		if ($muzakki->jenis_muzakki === 'kepala_keluarga') {
			$jumlahJiwaOtomatis = $jumlahTanggunganAktif + 1;
		}

		return array(
			'id' => (int) $muzakki->id,
			'jenis_muzakki' => (string) $muzakki->jenis_muzakki,
			'jumlah_tanggungan_aktif' => $jumlahTanggunganAktif,
			'jumlah_jiwa_otomatis' => $jumlahJiwaOtomatis
		);
	}

	public function get_all()
	{
		return $this->_base_query()
			->order_by('zf.id', 'DESC')
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
			->order_by('zf.id', 'DESC')
			->limit((int) $limit, (int) $offset)
			->get()
			->result();
	}

	public function get_statistics()
	{
		$stats = array(
			'total' => $this->count_all(),
			'uang' => 0,
			'beras' => 0,
			'lunas' => 0,
			'draft' => 0,
			'batal' => 0
		);

		$metode_rows = $this->db
			->select('metode_tunaikan, COUNT(*) AS jumlah', FALSE)
			->from($this->table)
			->group_by('metode_tunaikan')
			->get()
			->result();
		foreach ($metode_rows as $item) {
			$key = (string) $item->metode_tunaikan;
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
			->select('zf.*, m.kode_muzakki, m.nama AS nama_muzakki')
			->from($this->table . ' zf')
			->join('muzakki m', 'm.id = zf.muzakki_id', 'left')
			->where('zf.id', (int) $id)
			->get()
			->row();
	}

	public function get_tanggungan_aktif($muzakkiId)
	{
		return $this->db
			->select('nama_anggota, hubungan_keluarga')
			->from('tanggungan_fitrah')
			->where('muzakki_id', (int) $muzakkiId)
			->where('aktif_dihitung', 1)
			->order_by('id', 'ASC')
			->get()
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

	public function generate_next_nomor($date = NULL)
	{
		$date = $date ?: date('Y-m-d');
		$dateToken = date('Y', strtotime($date));
		$prefix = 'ZF-' . $dateToken . '-';

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
		return $this->_base_query()
			->where('zf.batch_id', $batch_id)
			->get()
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

