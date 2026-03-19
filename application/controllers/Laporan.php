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
        $sd = $filters['start_date'];
        $ed = $filters['end_date'];
        $per_page = 10;

        $pg_fitrah = zisedu_build_paging(array(
            'base_url' => site_url('laporan'),
            'total_rows' => $this->laporan->count_laporan_fitrah($sd, $ed),
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page_fitrah',
            'reuse_query_string' => TRUE
        ));

        $pg_mal = zisedu_build_paging(array(
            'base_url' => site_url('laporan'),
            'total_rows' => $this->laporan->count_laporan_mal($sd, $ed),
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page_mal',
            'reuse_query_string' => TRUE
        ));

        $pg_infaq = zisedu_build_paging(array(
            'base_url' => site_url('laporan'),
            'total_rows' => $this->laporan->count_laporan_infaq_shodaqoh($sd, $ed),
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page_infaq',
            'reuse_query_string' => TRUE
        ));

        $pg_penyaluran = zisedu_build_paging(array(
            'base_url' => site_url('laporan'),
            'total_rows' => $this->laporan->count_laporan_penyaluran($sd, $ed),
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page_penyaluran',
            'reuse_query_string' => TRUE
        ));

        $data = array(
            'page_title' => 'Laporan',
            'content_view' => 'laporan/index',
            'start_date' => $sd,
            'end_date' => $ed,
            'ringkasan' => $this->laporan->get_ringkasan($sd, $ed),
            'rows_fitrah' => $this->laporan->get_laporan_fitrah($sd, $ed, $pg_fitrah['limit'], $pg_fitrah['offset']),
            'rows_mal' => $this->laporan->get_laporan_mal($sd, $ed, $pg_mal['limit'], $pg_mal['offset']),
            'rows_infaq_shodaqoh' => $this->laporan->get_laporan_infaq_shodaqoh($sd, $ed, $pg_infaq['limit'], $pg_infaq['offset']),
            'rows_penyaluran' => $this->laporan->get_laporan_penyaluran($sd, $ed, $pg_penyaluran['limit'], $pg_penyaluran['offset']),
            'links_fitrah' => $pg_fitrah['links'],
            'links_mal' => $pg_mal['links'],
            'links_infaq' => $pg_infaq['links'],
            'links_penyaluran' => $pg_penyaluran['links']
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
            'rows_infaq_shodaqoh' => $reportData['rows_infaq_shodaqoh'],
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
