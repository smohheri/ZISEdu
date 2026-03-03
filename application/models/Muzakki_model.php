<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muzakki_model extends CI_Model
{
    protected $table = 'muzakki';

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
