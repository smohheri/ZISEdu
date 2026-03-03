<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_zakat_model extends CI_Model
{
    protected $table = 'pengaturan_zakat';

    private function _apply_search($search = '')
    {
        $search = trim((string) $search);
        if ($search === '') {
            return;
        }

        $this->db->group_start()
            ->like('tahun', $search)
            ->or_like('fitrah_per_jiwa_kg', $search)
            ->or_like('fitrah_per_jiwa_rupiah', $search)
            ->or_like('harga_beras_per_kg', $search)
            ->or_like('nilai_emas_per_gram', $search)
            ->group_end();
    }

    public function get_all()
    {
        return $this->db
            ->order_by('tahun', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function count_all()
    {
        return (int) $this->db->count_all($this->table);
    }

    public function get_latest()
    {
        return $this->db
            ->order_by('tahun', 'DESC')
            ->limit(1)
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
            ->order_by('tahun', 'DESC')
            ->limit((int) $limit, (int) $offset)
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

    public function exists_tahun($tahun, $excludeId = NULL)
    {
        $this->db->from($this->table)->where('tahun', (int) $tahun);
        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results() > 0;
    }
}
