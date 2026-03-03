<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyaluran_model extends CI_Model
{
    protected $table = 'penyaluran';

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

    public function get_detail_rows($penyaluranId)
    {
        return $this->db
            ->select('pd.*, m.nama AS nama_mustahik')
            ->from('penyaluran_detail pd')
            ->join('mustahik m', 'm.id = pd.mustahik_id', 'left')
            ->where('pd.penyaluran_id', (int) $penyaluranId)
            ->order_by('pd.id', 'ASC')
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

    public function replace_detail_rows($penyaluranId, array $rows)
    {
        $this->db->where('penyaluran_id', (int) $penyaluranId)->delete('penyaluran_detail');

        if (empty($rows)) {
            return TRUE;
        }

        foreach ($rows as &$row) {
            $row['penyaluran_id'] = (int) $penyaluranId;
        }
        unset($row);

        return $this->db->insert_batch('penyaluran_detail', $rows);
    }

    public function exists_nomor($nomor, $excludeId = NULL)
    {
        $this->db->from($this->table)->where('nomor_penyaluran', $nomor);
        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results() > 0;
    }

    public function get_mustahik_options()
    {
        $rows = $this->db
            ->select('id, kode_mustahik, nama')
            ->from('mustahik')
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get()
            ->result();

        $options = array();
        foreach ($rows as $r) {
            $options[$r->id] = $r->kode_mustahik . ' - ' . $r->nama;
        }

        return $options;
    }

    public function generate_next_nomor($date = NULL)
    {
        $date = $date ?: date('Y-m-d');
        $dateToken = date('Ymd', strtotime($date));
        $prefix = 'PS-' . $dateToken . '-';

        $last = $this->db
            ->select('nomor_penyaluran')
            ->from($this->table)
            ->like('nomor_penyaluran', $prefix, 'after')
            ->order_by('nomor_penyaluran', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $next = 1;
        if ($last && isset($last->nomor_penyaluran)) {
            $parts = explode('-', $last->nomor_penyaluran);
            $suffix = end($parts);
            if (is_numeric($suffix)) {
                $next = ((int) $suffix) + 1;
            }
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
