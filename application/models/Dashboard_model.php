<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function get_monthly_chart_data($months = 6)
    {
        $months = max(1, (int) $months);

        $labels = array();
        $masuk = array();
        $keluar = array();
        $fitrahUang = array();
        $fitrahBeras = array();

        for ($i = $months - 1; $i >= 0; $i--) {
            $target = strtotime(date('Y-m-01') . " -{$i} month");
            $year = date('Y', $target);
            $month = date('m', $target);

            $startDate = $year . '-' . $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));

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
                ->where('tanggal_bayar >=', $startDate)
                ->where('tanggal_bayar <=', $endDate)
                ->get()
                ->row();

            $penyaluran = $this->db
                ->select('COALESCE(SUM(total_uang),0) AS total_uang')
                ->from('penyaluran')
                ->where('status', 'disalurkan')
                ->where('tanggal_penyaluran >=', $startDate)
                ->where('tanggal_penyaluran <=', $endDate)
                ->get()
                ->row();

            $labels[] = date('M Y', $target);
            $masuk[] = (float) ($fitrah ? $fitrah->total_uang : 0) + (float) ($mal ? $mal->total_zakat : 0);
            $keluar[] = (float) ($penyaluran ? $penyaluran->total_uang : 0);
            $fitrahUang[] = (float) ($fitrah ? $fitrah->total_uang : 0);
            $fitrahBeras[] = (float) ($fitrah ? $fitrah->total_beras : 0);
        }

        return array(
            'labels' => $labels,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'fitrah_uang' => $fitrahUang,
            'fitrah_beras' => $fitrahBeras
        );
    }

    public function get_summary()
    {
        $totalMuzakki = (int) $this->db->count_all('muzakki');
        $totalMustahik = (int) $this->db->count_all('mustahik');

        $fitrah = $this->db
            ->select('COALESCE(SUM(nominal_uang),0) AS total_uang, COALESCE(SUM(beras_kg),0) AS total_beras')
            ->from('zakat_fitrah')
            ->where('status', 'lunas')
            ->get()
            ->row();

        $mal = $this->db
            ->select('COALESCE(SUM(total_zakat),0) AS total_zakat_mal')
            ->from('zakat_mal')
            ->where('status', 'lunas')
            ->get()
            ->row();

        $penyaluran = $this->db
            ->select('COALESCE(SUM(total_uang),0) AS total_uang, COALESCE(SUM(total_beras_kg),0) AS total_beras')
            ->from('penyaluran')
            ->where('status', 'disalurkan')
            ->get()
            ->row();

        $totalZakatMasuk = (float) ($fitrah ? $fitrah->total_uang : 0) + (float) ($mal ? $mal->total_zakat_mal : 0);

        return array(
            'total_muzakki' => $totalMuzakki,
            'total_mustahik' => $totalMustahik,
            'total_zakat_masuk' => $totalZakatMasuk,
            'total_fitrah_beras' => (float) ($fitrah ? $fitrah->total_beras : 0),
            'total_penyaluran_uang' => (float) ($penyaluran ? $penyaluran->total_uang : 0),
            'total_penyaluran_beras' => (float) ($penyaluran ? $penyaluran->total_beras : 0)
        );
    }

    public function get_recent_fitrah($limit = 5)
    {
        return $this->db
            ->select('zf.nomor_transaksi, zf.tanggal_bayar, zf.metode_tunaikan, zf.nominal_uang, zf.beras_kg, m.nama AS nama_muzakki')
            ->from('zakat_fitrah zf')
            ->join('muzakki m', 'm.id = zf.muzakki_id', 'left')
            ->order_by('zf.id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function get_recent_penyaluran($limit = 5)
    {
        return $this->db
            ->select('nomor_penyaluran, tanggal_penyaluran, jenis_sumber, total_uang, total_beras_kg, status')
            ->from('penyaluran')
            ->order_by('id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }
}
