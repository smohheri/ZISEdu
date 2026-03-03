<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mustahik_model extends CI_Model
{
    protected $table = 'mustahik';

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
