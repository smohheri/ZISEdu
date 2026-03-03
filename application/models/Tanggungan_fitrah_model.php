<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tanggungan_fitrah_model extends CI_Model
{
    protected $table = 'tanggungan_fitrah';

    private function _base_query()
    {
        return $this->db
            ->select('tf.*, m.nama AS nama_muzakki, m.jenis_muzakki')
            ->from($this->table . ' tf')
            ->join('muzakki m', 'm.id = tf.muzakki_id', 'inner');
    }

    private function _apply_search($search = '')
    {
        $search = trim((string) $search);
        if ($search === '') {
            return;
        }

        $this->db->group_start()
            ->like('m.nama', $search)
            ->or_like('tf.nama_anggota', $search)
            ->or_like('tf.hubungan_keluarga', $search)
            ->group_end();
    }

    public function get_all()
    {
        return $this->_base_query()
            ->order_by('tf.id', 'DESC')
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
            ->order_by('tf.id', 'DESC')
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
            'kepala_keluarga' => 0
        );

        $status_rows = $this->db
            ->select('aktif_dihitung, COUNT(*) AS jumlah', FALSE)
            ->from($this->table)
            ->group_by('aktif_dihitung')
            ->get()
            ->result();
        foreach ($status_rows as $item) {
            if ((int) $item->aktif_dihitung === 1) {
                $stats['aktif'] = (int) $item->jumlah;
            } else {
                $stats['nonaktif'] = (int) $item->jumlah;
            }
        }

        $stats['kepala_keluarga'] = (int) $this->db
            ->from($this->table . ' tf')
            ->join('muzakki m', 'm.id = tf.muzakki_id', 'inner')
            ->where('m.jenis_muzakki', 'kepala_keluarga')
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

    public function get_kepala_keluarga_options()
    {
        $rows = $this->db
            ->select('id, kode_muzakki, nama')
            ->from('muzakki')
            ->where('jenis_muzakki', 'kepala_keluarga')
            ->order_by('nama', 'ASC')
            ->get()
            ->result();

        $options = array();
        foreach ($rows as $r) {
            $options[$r->id] = $r->kode_muzakki . ' - ' . $r->nama;
        }

        return $options;
    }

    public function is_kepala_keluarga($muzakkiId)
    {
        return $this->db
            ->from('muzakki')
            ->where('id', (int) $muzakkiId)
            ->where('jenis_muzakki', 'kepala_keluarga')
            ->count_all_results() > 0;
    }
}
