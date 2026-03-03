<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zakat_mal_model extends CI_Model
{
	protected $table = 'zakat_mal';

	public function get_all()
	{
		return $this->db
			->select('zm.*, m.nama AS nama_muzakki')
			->from($this->table . ' zm')
			->join('muzakki m', 'm.id = zm.muzakki_id', 'inner')
			->order_by('zm.id', 'DESC')
			->get()
			->result();
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
