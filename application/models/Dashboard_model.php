<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    private function _has_infaq_table()
    {
        return $this->db->table_exists('infaq_shodaqoh');
    }

    public function get_monthly_chart_data($months = 6)
    {
        $months = max(1, (int) $months);

        $labels = array();
        $masukZakat = array();
        $masukInfaqShodaqoh = array();
        $keluarPenyaluran = array();
        $saldoArusKas = array();
        $fitrahUang = array();
        $malUang = array();
        $infaqUang = array();

        $hasInfaqTable = $this->_has_infaq_table();

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

            $infaq = (object) array('total_uang' => 0);
            if ($hasInfaqTable) {
                $infaq = $this->db
                    ->select('COALESCE(SUM(nominal_uang),0) AS total_uang')
                    ->from('infaq_shodaqoh')
                    ->where('status', 'diterima')
                    ->where('tanggal_transaksi >=', $startDate)
                    ->where('tanggal_transaksi <=', $endDate)
                    ->get()
                    ->row();
            }

            $penyaluran = $this->db
                ->select('COALESCE(SUM(total_uang),0) AS total_uang')
                ->from('penyaluran')
                ->where('status', 'disalurkan')
                ->where('tanggal_penyaluran >=', $startDate)
                ->where('tanggal_penyaluran <=', $endDate)
                ->get()
                ->row();

            $nilaiFitrahUang = (float) ($fitrah ? $fitrah->total_uang : 0);
            $nilaiMal = (float) ($mal ? $mal->total_zakat : 0);
            $nilaiInfaq = (float) ($infaq ? $infaq->total_uang : 0);
            $nilaiKeluar = (float) ($penyaluran ? $penyaluran->total_uang : 0);

            $labels[] = date('M Y', $target);
            $masukZakat[] = $nilaiFitrahUang + $nilaiMal;
            $masukInfaqShodaqoh[] = $nilaiInfaq;
            $keluarPenyaluran[] = $nilaiKeluar;
            $saldoArusKas[] = ($nilaiFitrahUang + $nilaiMal + $nilaiInfaq) - $nilaiKeluar;
            $fitrahUang[] = $nilaiFitrahUang;
            $malUang[] = $nilaiMal;
            $infaqUang[] = $nilaiInfaq;
        }

        return array(
            'labels' => $labels,
            'masuk_zakat' => $masukZakat,
            'masuk_infaq_shodaqoh' => $masukInfaqShodaqoh,
            'keluar_penyaluran' => $keluarPenyaluran,
            'saldo_arus_kas' => $saldoArusKas,
            'fitrah_uang' => $fitrahUang,
            'mal_uang' => $malUang,
            'infaq_uang' => $infaqUang
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

        $infaq = (object) array(
            'total_uang' => 0,
            'total_infaq' => 0,
            'total_shodaqoh' => 0
        );
        if ($this->_has_infaq_table()) {
            $infaq = $this->db
                ->select(
                    'COALESCE(SUM(nominal_uang),0) AS total_uang,' .
                    "COALESCE(SUM(CASE WHEN jenis_dana = 'infaq' THEN nominal_uang ELSE 0 END),0) AS total_infaq," .
                    "COALESCE(SUM(CASE WHEN jenis_dana = 'shodaqoh' THEN nominal_uang ELSE 0 END),0) AS total_shodaqoh",
                    FALSE
                )
                ->from('infaq_shodaqoh')
                ->where('status', 'diterima')
                ->get()
                ->row();
        }

        $penyaluran = $this->db
            ->select('COALESCE(SUM(total_uang),0) AS total_uang, COALESCE(SUM(total_beras_kg),0) AS total_beras')
            ->from('penyaluran')
            ->where('status', 'disalurkan')
            ->get()
            ->row();

        $totalFitrahUang = (float) ($fitrah ? $fitrah->total_uang : 0);
        $totalMalUang = (float) ($mal ? $mal->total_zakat_mal : 0);
        $totalInfaqShodaqoh = (float) ($infaq ? $infaq->total_uang : 0);
        $totalMasukUang = $totalFitrahUang + $totalMalUang + $totalInfaqShodaqoh;
        $totalKeluarUang = (float) ($penyaluran ? $penyaluran->total_uang : 0);

        return array(
            'total_muzakki' => $totalMuzakki,
            'total_mustahik' => $totalMustahik,
            'total_zakat_masuk' => $totalFitrahUang + $totalMalUang,
            'total_masuk_uang' => $totalMasukUang,
            'total_saldo_uang' => $totalMasukUang - $totalKeluarUang,
            'total_fitrah_uang' => $totalFitrahUang,
            'total_mal_uang' => $totalMalUang,
            'total_infaq_shodaqoh_uang' => $totalInfaqShodaqoh,
            'total_infaq_uang' => (float) ($infaq ? $infaq->total_infaq : 0),
            'total_shodaqoh_uang' => (float) ($infaq ? $infaq->total_shodaqoh : 0),
            'total_fitrah_beras' => (float) ($fitrah ? $fitrah->total_beras : 0),
            'total_penyaluran_uang' => $totalKeluarUang,
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

    public function get_recent_infaq_shodaqoh($limit = 5)
    {
        if (!$this->_has_infaq_table()) {
            return array();
        }

        return $this->db
            ->select('nomor_transaksi, tanggal_transaksi, jenis_dana, nama_donatur, nominal_uang, metode_bayar, status')
            ->from('infaq_shodaqoh')
            ->order_by('id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }
}
