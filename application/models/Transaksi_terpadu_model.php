<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_terpadu_model extends CI_Model
{
    protected $table = 'transaksi_terpadu';

    private function _apply_search($search = '')
    {
        $search = trim((string) $search);
        if ($search === '') {
            return;
        }

        $this->db->group_start()
            ->like('t.nomor_transaksi', $search)
            ->or_like('m.nama', $search)
            ->group_end();
    }

    public function count_all()
    {
        return (int) $this->db->count_all($this->table);
    }

    public function count_filtered($search = '')
    {
        $this->db->from($this->table . ' t');
        $this->db->join('muzakki m', 'm.id = t.muzakki_id', 'left');
        $this->_apply_search($search);
        return (int) $this->db->count_all_results();
    }

    public function get_paginated($limit, $offset, $search = '')
    {
        $this->db->select('t.*, m.nama as nama_muzakki');
        $this->db->from($this->table . ' t');
        $this->db->join('muzakki m', 'm.id = t.muzakki_id', 'left');
        $this->_apply_search($search);
        return $this->db
            ->order_by('t.id', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('t.*, m.nama as nama_muzakki')
            ->from($this->table . ' t')
            ->join('muzakki m', 'm.id = t.muzakki_id', 'left')
            ->where('t.id', (int) $id)
            ->get()
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    public function get_stats()
    {
        return $this->db->query("
            SELECT 
                COUNT(t.id) as total_transaksi,
                COUNT(DISTINCT t.muzakki_id) as total_muzakki,
                SUM(COALESCE(f.nominal_uang, 0) + COALESCE(m.total_zakat, 0) + COALESCE(i.nominal_uang, 0)) as total_nominal,
                COUNT(f.id) as count_fitrah,
                SUM(COALESCE(f.nominal_uang, 0)) as total_fitrah,
                SUM(COALESCE(f.beras_kg, 0)) as total_beras_fitrah,
                COUNT(m.id) as count_mal,
                SUM(COALESCE(m.total_zakat, 0)) as total_mal,
                COUNT(i.id) as count_infaq,
                SUM(COALESCE(i.nominal_uang, 0)) as total_infaq
            FROM " . $this->table . " t 
            LEFT JOIN zakat_fitrah f ON f.id = t.fitrah_id
            LEFT JOIN zakat_mal m ON m.id = t.mal_id
            LEFT JOIN infaq_shodaqoh i ON i.id = t.infaq_id
        ")->row();
    }
}
