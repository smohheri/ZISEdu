<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	$baseTitle = isset($page_title) && $page_title !== '' ? $page_title : 'Dashboard';
	$documentTitle = (stripos($baseTitle, 'ZISEdu') !== FALSE) ? $baseTitle : $baseTitle . ' - ZISEdu';

	$CI =& get_instance();
	$CI->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi_head');
	$headLembaga = $CI->pengaturan_aplikasi_head->get_first();

	$faviconUrl = '';
	if (!empty($headLembaga->logo_path)) {
		$relativeLogoPath = ltrim($headLembaga->logo_path, '/\\');
		$logoFullPath = FCPATH . $relativeLogoPath;
		if (is_file($logoFullPath)) {
			$faviconUrl = base_url($relativeLogoPath) . '?v=' . filemtime($logoFullPath);
		} else {
			$faviconUrl = base_url($relativeLogoPath);
		}
	}
	?>
	<title><?php echo $documentTitle; ?></title>

	<?php if ($faviconUrl !== ''): ?>
		<link rel="icon" href="<?php echo $faviconUrl; ?>" type="image/png">
		<link rel="shortcut icon" href="<?php echo $faviconUrl; ?>" type="image/png">
	<?php endif; ?>

	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/plugins/select2/css/select2.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/dist/css/adminlte.min.css'); ?>">
	<!-- jQuery -->
	<script src="<?php echo base_url('asset/adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">