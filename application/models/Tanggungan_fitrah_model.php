<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tanggungan_fitrah_model extends CI_Model
{
    protected $table = 'tanggungan_fitrah';

    public function get_all()
    {
        return $this->db
            ->select('tf.*, m.nama AS nama_muzakki, m.jenis_muzakki')
            ->from($this->table . ' tf')
            ->join('muzakki m', 'm.id = tf.muzakki_id', 'inner')
            ->order_by('tf.id', 'DESC')
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
