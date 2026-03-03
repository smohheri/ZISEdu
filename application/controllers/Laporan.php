<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_model', 'laporan');
        $this->_require_login();
    }

    public function index()
    {
        $filters = $this->_resolve_filters();
        $reportData = $this->_build_report_data($filters['start_date'], $filters['end_date']);

        $data = array(
            'page_title' => 'Laporan',
            'content_view' => 'laporan/index',
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'ringkasan' => $reportData['ringkasan'],
            'rows_fitrah' => $reportData['rows_fitrah'],
            'rows_mal' => $reportData['rows_mal'],
            'rows_penyaluran' => $reportData['rows_penyaluran'],
            'rows_mustahik' => $reportData['rows_mustahik']
        );

        $this->load->view('layouts/adminlte', $data);
    }

    public function export_pdf()
    {
        $filters = $this->_resolve_filters();
        $reportData = $this->_build_report_data($filters['start_date'], $filters['end_date']);

        $this->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi');
        $lembaga = $this->pengaturan_aplikasi->get_first();

        $logoImageSrc = NULL;
        if ($lembaga && !empty($lembaga->logo_path)) {
            $logoFullPath = FCPATH . ltrim($lembaga->logo_path, '/\\');
            if (is_file($logoFullPath)) {
                $logoImageSrc = 'file:///' . str_replace('\\', '/', $logoFullPath);
            }
        }

        $kopImageSrc = NULL;
        if ($lembaga && !empty($lembaga->kop_path)) {
            $kopFullPath = FCPATH . ltrim($lembaga->kop_path, '/\\');
            if (is_file($kopFullPath)) {
                $kopImageSrc = 'file:///' . str_replace('\\', '/', $kopFullPath);
            }
        }

        $viewData = array(
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'ringkasan' => $reportData['ringkasan'],
            'rows_fitrah' => $reportData['rows_fitrah'],
            'rows_mal' => $reportData['rows_mal'],
            'rows_penyaluran' => $reportData['rows_penyaluran'],
            'rows_mustahik' => $reportData['rows_mustahik'],
            'lembaga' => $lembaga,
            'logo_image_src' => $logoImageSrc,
            'kop_image_src' => $kopImageSrc
        );

        $vendorAutoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($vendorAutoload)) {
            show_error('Autoload Composer tidak ditemukan. Pastikan dependency mPDF sudah terpasang.');
        }

        require_once $vendorAutoload;

        $html = $this->load->view('laporan/pdf', $viewData, TRUE);
        $mpdf = new \Mpdf\Mpdf(array(
            'format' => 'A4-L',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 8
        ));

        $mpdf->SetTitle('Laporan Zakat ' . $filters['start_date'] . ' s.d. ' . $filters['end_date']);
        $mpdf->WriteHTML($html);

        $filename = 'laporan-zakat-' . $filters['start_date'] . '-sd-' . $filters['end_date'] . '.pdf';
        $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
    }

    private function _resolve_filters()
    {
        $startDate = trim((string) $this->input->get('start_date', TRUE));
        $endDate = trim((string) $this->input->get('end_date', TRUE));

        if ($startDate === '') {
            $startDate = date('Y-m-01');
        }

        if ($endDate === '') {
            $endDate = date('Y-m-d');
        }

        if ($startDate > $endDate) {
            $tmp = $startDate;
            $startDate = $endDate;
            $endDate = $tmp;
        }

        return array(
            'start_date' => $startDate,
            'end_date' => $endDate
        );
    }

    private function _build_report_data($startDate, $endDate)
    {
        return array(
            'ringkasan' => $this->laporan->get_ringkasan($startDate, $endDate),
            'rows_fitrah' => $this->laporan->get_laporan_fitrah($startDate, $endDate),
            'rows_mal' => $this->laporan->get_laporan_mal($startDate, $endDate),
            'rows_penyaluran' => $this->laporan->get_laporan_penyaluran($startDate, $endDate),
            'rows_mustahik' => $this->laporan->get_laporan_mustahik_penerima($startDate, $endDate),
            'rows_infaq_shodaqoh' => $this->laporan->get_laporan_infaq_shodaqoh($startDate, $endDate)
        );
    }

    private function _require_login()
    {
        if ($this->session->userdata('is_logged_in') !== TRUE) {
            redirect('login');
        }
    }
}
