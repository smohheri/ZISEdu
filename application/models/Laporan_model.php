<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    public function get_ringkasan($startDate, $endDate)
    {
        $fitrah = $this->db
            ->select('COALESCE(SUM(nominal_uang),0) AS total_uang, COALESCE(SUM(beras_kg),0) AS total_beras')
            ->from('zakat_fitrah')
            ->where('status', 'lunas')
            ->where('tanggal_bayar >=', $startDate)
            ->where('tanggal_bayar <=', $endDate)
            ->get()
            ->row();

        $mal = $this->db
            ->select('COALESCE(SUM(total_zakat),0) AS total_zakat')
            ->from('zakat_mal')
            ->where('status', 'lunas')
            ->group_start()
            ->where('tanggal_bayar >=', $startDate)
            ->where('tanggal_bayar <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('tanggal_bayar IS NULL', NULL, FALSE)
            ->where('tanggal_hitung >=', $startDate)
            ->where('tanggal_hitung <=', $endDate)
            ->where('status', 'lunas')
            ->group_end()
            ->get()
            ->row();

        $penyaluran = $this->db
            ->select('COALESCE(SUM(total_uang),0) AS total_uang, COALESCE(SUM(total_beras_kg),0) AS total_beras')
            ->from('penyaluran')
            ->where('status', 'disalurkan')
            ->where('tanggal_penyaluran >=', $startDate)
            ->where('tanggal_penyaluran <=', $endDate)
            ->get()
            ->row();

        $infaq = $this->db
            ->select('COALESCE(SUM(nominal_uang),0) AS total_uang')
            ->from('infaq_shodaqoh')
            ->where('status', 'diterima')
            ->where('tanggal_transaksi >=', $startDate)
            ->where('tanggal_transaksi <=', $endDate)
            ->get()
            ->row();

        return array(
            'fitrah_uang' => (float) ($fitrah ? $fitrah->total_uang : 0),
            'fitrah_beras' => (float) ($fitrah ? $fitrah->total_beras : 0),
            'mal_uang' => (float) ($mal ? $mal->total_zakat : 0),
            'penyaluran_uang' => (float) ($penyaluran ? $penyaluran->total_uang : 0),
            'penyaluran_beras' => (float) ($penyaluran ? $penyaluran->total_beras : 0),
            'infaq_shodaqoh_uang' => (float) ($infaq ? $infaq->total_uang : 0)
        );
    }

    public function count_laporan_fitrah($startDate, $endDate)
    {
        return $this->db
            ->where('tanggal_bayar >=', $startDate)
            ->where('tanggal_bayar <=', $endDate)
            ->count_all_results('zakat_fitrah');
    }

    public function get_laporan_fitrah($startDate, $endDate, $limit = NULL, $offset = 0)
    {
        $this->db
            ->select('zf.nomor_transaksi, zf.tanggal_bayar, zf.jumlah_jiwa, zf.metode_tunaikan, zf.beras_kg, zf.nominal_uang, zf.status, m.nama AS nama_muzakki')
            ->from('zakat_fitrah zf')
            ->join('muzakki m', 'm.id = zf.muzakki_id', 'left')
            ->where('zf.tanggal_bayar >=', $startDate)
            ->where('zf.tanggal_bayar <=', $endDate)
            ->order_by('zf.tanggal_bayar', 'DESC')
            ->order_by('zf.id', 'DESC');
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    public function count_laporan_mal($startDate, $endDate)
    {
        return $this->db
            ->group_start()
            ->where('tanggal_bayar >=', $startDate)
            ->where('tanggal_bayar <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('tanggal_bayar IS NULL', NULL, FALSE)
            ->where('tanggal_hitung >=', $startDate)
            ->where('tanggal_hitung <=', $endDate)
            ->group_end()
            ->count_all_results('zakat_mal');
    }

    public function get_laporan_mal($startDate, $endDate, $limit = NULL, $offset = 0)
    {
        $this->db
            ->select('zm.nomor_transaksi, zm.tanggal_hitung, zm.tanggal_bayar, zm.harta_bersih, zm.nilai_nishab, zm.total_zakat, zm.status, m.nama AS nama_muzakki')
            ->from('zakat_mal zm')
            ->join('muzakki m', 'm.id = zm.muzakki_id', 'left')
            ->group_start()
            ->where('zm.tanggal_bayar >=', $startDate)
            ->where('zm.tanggal_bayar <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('zm.tanggal_bayar IS NULL', NULL, FALSE)
            ->where('zm.tanggal_hitung >=', $startDate)
            ->where('zm.tanggal_hitung <=', $endDate)
            ->group_end()
            ->order_by('zm.tanggal_hitung', 'DESC')
            ->order_by('zm.id', 'DESC');
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    public function count_laporan_penyaluran($startDate, $endDate)
    {
        return $this->db
            ->where('tanggal_penyaluran >=', $startDate)
            ->where('tanggal_penyaluran <=', $endDate)
            ->count_all_results('penyaluran');
    }

    public function get_laporan_penyaluran($startDate, $endDate, $limit = NULL, $offset = 0)
    {
        $this->db
            ->select('p.nomor_penyaluran, p.tanggal_penyaluran, p.jenis_sumber, p.total_uang, p.total_beras_kg, p.status')
            ->from('penyaluran p')
            ->where('p.tanggal_penyaluran >=', $startDate)
            ->where('p.tanggal_penyaluran <=', $endDate)
            ->order_by('p.tanggal_penyaluran', 'DESC')
            ->order_by('p.id', 'DESC');
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    public function count_laporan_mustahik_penerima($startDate, $endDate)
    {
        return $this->db
            ->select('m.id')
            ->from('penyaluran_detail pd')
            ->join('penyaluran p', 'p.id = pd.penyaluran_id', 'inner')
            ->join('mustahik m', 'm.id = pd.mustahik_id', 'left')
            ->where('p.status', 'disalurkan')
            ->where('p.tanggal_penyaluran >=', $startDate)
            ->where('p.tanggal_penyaluran <=', $endDate)
            ->group_by('pd.mustahik_id')
            ->group_by('m.kode_mustahik')
            ->group_by('m.nama')
            ->get()
            ->num_rows();
    }

    public function get_laporan_mustahik_penerima($startDate, $endDate, $limit = NULL, $offset = 0)
    {
        $this->db
            ->select('m.kode_mustahik, m.nama AS nama_mustahik, COUNT(pd.id) AS jumlah_penerimaan, COALESCE(SUM(pd.nominal_uang),0) AS total_nominal_uang, COALESCE(SUM(pd.beras_kg),0) AS total_beras_kg', FALSE)
            ->from('penyaluran_detail pd')
            ->join('penyaluran p', 'p.id = pd.penyaluran_id', 'inner')
            ->join('mustahik m', 'm.id = pd.mustahik_id', 'left')
            ->where('p.status', 'disalurkan')
            ->where('p.tanggal_penyaluran >=', $startDate)
            ->where('p.tanggal_penyaluran <=', $endDate)
            ->group_by('pd.mustahik_id')
            ->group_by('m.kode_mustahik')
            ->group_by('m.nama')
            ->order_by('m.nama', 'ASC');
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    public function count_laporan_infaq_shodaqoh($startDate, $endDate)
    {
        return $this->db
            ->where('tanggal_transaksi >=', $startDate)
            ->where('tanggal_transaksi <=', $endDate)
            ->count_all_results('infaq_shodaqoh');
    }

    public function get_laporan_infaq_shodaqoh($startDate, $endDate, $limit = NULL, $offset = 0)
    {
        $this->db
            ->select('nomor_transaksi, tanggal_transaksi, jenis_dana, nama_donatur, nominal_uang, metode_bayar, status')
            ->from('infaq_shodaqoh')
            ->where('tanggal_transaksi >=', $startDate)
            ->where('tanggal_transaksi <=', $endDate)
            ->order_by('tanggal_transaksi', 'DESC')
            ->order_by('id', 'DESC');
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }
}
