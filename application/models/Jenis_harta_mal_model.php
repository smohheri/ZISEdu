<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_harta_mal_model extends CI_Model
{
    protected $table = 'jenis_harta_mal';

    private function _apply_search($search = '')
    {
        $search = trim((string) $search);
        if ($search === '') {
            return;
        }

        $this->db->group_start()
            ->like('kode_jenis', $search)
            ->or_like('nama_jenis', $search)
            ->or_like('tarif_persen', $search)
            ->or_like('keterangan', $search)
            ->group_end();
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
            'butuh_haul' => 0,
            'tanpa_haul' => 0
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

        $haul_rows = $this->db
            ->select('butuh_haul, COUNT(*) AS jumlah', FALSE)
            ->from($this->table)
            ->group_by('butuh_haul')
            ->get()
            ->result();
        foreach ($haul_rows as $item) {
            if ((int) $item->butuh_haul === 1) {
                $stats['butuh_haul'] = (int) $item->jumlah;
            } else {
                $stats['tanpa_haul'] = (int) $item->jumlah;
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
        $this->db->from($this->table)->where('kode_jenis', $kode);
        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results() > 0;
    }
}
