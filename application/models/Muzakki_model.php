<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muzakki_model extends CI_Model
{
    protected $table = 'muzakki';

    private function _apply_search($search = '')
    {
        $search = trim((string) $search);
        if ($search === '') {
            return;
        }

        $this->db->group_start()
            ->like('kode_muzakki', $search)
            ->or_like('nama', $search)
            ->or_like('jenis_muzakki', $search)
            ->or_like('nik', $search)
            ->or_like('no_hp', $search)
            ->or_like('alamat', $search)
            ->group_end();
    }

    public function generate_next_kode($year = NULL)
    {
        $year = $year !== NULL ? (int) $year : (int) date('Y');
        $prefix = 'MZK-' . $year . '-';

        $last = $this->db
            ->select('kode_muzakki')
            ->from($this->table)
            ->like('kode_muzakki', $prefix, 'after')
            ->order_by('kode_muzakki', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $nextNumber = 1;
        if ($last && isset($last->kode_muzakki)) {
            $parts = explode('-', $last->kode_muzakki);
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
            'total' => (int) $this->db->count_all($this->table),
            'individu' => 0,
            'lembaga' => 0,
            'kepala_keluarga' => 0,
            'punya_nik' => 0,
            'punya_no_hp' => 0
        );

        $jenis_rows = $this->db
            ->select('jenis_muzakki, COUNT(*) AS jumlah', FALSE)
            ->from($this->table)
            ->group_by('jenis_muzakki')
            ->get()
            ->result();
        foreach ($jenis_rows as $item) {
            $key = (string) $item->jenis_muzakki;
            if (isset($stats[$key])) {
                $stats[$key] = (int) $item->jumlah;
            }
        }

        $stats['punya_nik'] = (int) $this->db
            ->from($this->table)
            ->where('nik IS NOT NULL', NULL, FALSE)
            ->where("TRIM(nik) !=", '')
            ->count_all_results();

        $stats['punya_no_hp'] = (int) $this->db
            ->from($this->table)
            ->where('no_hp IS NOT NULL', NULL, FALSE)
            ->where("TRIM(no_hp) !=", '')
            ->count_all_results();

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
        $this->db->from($this->table)->where('kode_muzakki', $kode);
        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results() > 0;
    }

    public function get_tanggungan($muzakkiId)
    {
        return $this->db
            ->where('muzakki_id', (int) $muzakkiId)
            ->order_by('id', 'ASC')
            ->get('tanggungan_fitrah')
            ->result();
    }

    public function replace_tanggungan($muzakkiId, array $rows)
    {
        $this->db->where('muzakki_id', (int) $muzakkiId)->delete('tanggungan_fitrah');

        if (empty($rows)) {
            return TRUE;
        }

        foreach ($rows as &$row) {
            $row['muzakki_id'] = (int) $muzakkiId;
        }
        unset($row);

        return $this->db->insert_batch('tanggungan_fitrah', $rows);
    }
}
