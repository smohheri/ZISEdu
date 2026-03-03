<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zakat_fitrah_model extends CI_Model
{
    protected $table = 'zakat_fitrah';

    public function get_muzakki_info($muzakkiId)
    {
        $muzakki = $this->db
            ->select('id, jenis_muzakki')
            ->from('muzakki')
            ->where('id', (int) $muzakkiId)
            ->get()
            ->row();

        if (!$muzakki) {
            return NULL;
        }

        $jumlahTanggunganAktif = (int) $this->db
            ->from('tanggungan_fitrah')
            ->where('muzakki_id', (int) $muzakkiId)
            ->where('aktif_dihitung', 1)
            ->count_all_results();

        $jumlahJiwaOtomatis = 1;
        if ($muzakki->jenis_muzakki === 'kepala_keluarga') {
            $jumlahJiwaOtomatis = $jumlahTanggunganAktif + 1;
        }

        return array(
            'id' => (int) $muzakki->id,
            'jenis_muzakki' => (string) $muzakki->jenis_muzakki,
            'jumlah_tanggungan_aktif' => $jumlahTanggunganAktif,
            'jumlah_jiwa_otomatis' => $jumlahJiwaOtomatis
        );
    }

    public function get_all()
    {
        return $this->db
            ->select('zf.*, m.nama AS nama_muzakki')
            ->from($this->table . ' zf')
            ->join('muzakki m', 'm.id = zf.muzakki_id', 'inner')
            ->order_by('zf.id', 'DESC')
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

    public function get_receipt_by_id($id)
    {
        return $this->db
            ->select('zf.*, m.kode_muzakki, m.nama AS nama_muzakki')
            ->from($this->table . ' zf')
            ->join('muzakki m', 'm.id = zf.muzakki_id', 'left')
            ->where('zf.id', (int) $id)
            ->get()
            ->row();
    }

    public function get_tanggungan_aktif($muzakkiId)
    {
        return $this->db
            ->select('nama_anggota, hubungan_keluarga')
            ->from('tanggungan_fitrah')
            ->where('muzakki_id', (int) $muzakkiId)
            ->where('aktif_dihitung', 1)
            ->order_by('id', 'ASC')
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

    public function exists_nomor($nomor, $excludeId = NULL)
    {
        $this->db->from($this->table)->where('nomor_transaksi', $nomor);
        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results() > 0;
    }

    public function get_muzakki_options()
    {
        $rows = $this->db
            ->select('id, kode_muzakki, nama')
            ->from('muzakki')
            ->order_by('nama', 'ASC')
            ->get()
            ->result();

        $options = array();
        foreach ($rows as $r) {
            $options[$r->id] = $r->kode_muzakki . ' - ' . $r->nama;
        }

        return $options;
    }

    public function generate_next_nomor($date = NULL)
    {
        $date = $date ?: date('Y-m-d');
        $dateToken = date('Ymd', strtotime($date));
        $prefix = 'ZF-' . $dateToken . '-';

        $last = $this->db
            ->select('nomor_transaksi')
            ->from($this->table)
            ->like('nomor_transaksi', $prefix, 'after')
            ->order_by('nomor_transaksi', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $next = 1;
        if ($last && isset($last->nomor_transaksi)) {
            $parts = explode('-', $last->nomor_transaksi);
            $suffix = end($parts);
            if (is_numeric($suffix)) {
                $next = ((int) $suffix) + 1;
            }
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
