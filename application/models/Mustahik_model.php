<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mustahik_model extends CI_Model
{
    protected $table = 'mustahik';

    private function _apply_search($search = '')
    {
        $search = trim((string) $search);
        if ($search === '') {
            return;
        }

        $this->db->group_start()
            ->like('kode_mustahik', $search)
            ->or_like('nama', $search)
            ->or_like('kategori_asnaf', $search)
            ->or_like('no_hp', $search)
            ->or_like('nik', $search)
            ->group_end();
    }

    public function generate_next_kode($year = NULL)
    {
        $year = $year !== NULL ? (int) $year : (int) date('Y');
        $prefix = 'MST-' . $year . '-';

        $last = $this->db
            ->select('kode_mustahik')
            ->from($this->table)
            ->like('kode_mustahik', $prefix, 'after')
            ->order_by('kode_mustahik', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $nextNumber = 1;
        if ($last && isset($last->kode_mustahik)) {
            $parts = explode('-', $last->kode_mustahik);
            $suffix = end($parts);
            if (is_numeric($suffix)) {
                $nextNumber = ((int) $suffix) + 1;
            }
        }

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function get_all()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
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
            'aktif' => 0,
            'nonaktif' => 0,
            'fakir' => 0,
            'miskin' => 0,
            'amil' => 0
        );

        $status_rows = $this->db
            ->select('aktif, COUNT(*) AS jumlah', FALSE)
            ->from($this->table)
            ->group_by('aktif')
            ->get()
            ->result();
        foreach ($status_rows as $item) {
            if ((int) $item->aktif === 1) {
                $stats['aktif'] = (int) $item->jumlah;
            } else {
                $stats['nonaktif'] = (int) $item->jumlah;
            }
        }

        $asnaf_rows = $this->db
            ->select('kategori_asnaf, COUNT(*) AS jumlah', FALSE)
            ->from($this->table)
            ->group_by('kategori_asnaf')
            ->get()
            ->result();
        foreach ($asnaf_rows as $item) {
            $key = (string) $item->kategori_asnaf;
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

    public function exists_kode($kode, $excludeId = NULL)
    {
        $this->db->from($this->table)->where('kode_mustahik', $kode);
        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results() > 0;
    }
}
