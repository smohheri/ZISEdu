<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	$appName = 'ZISEdu';
	$baseTitle = isset($page_title) && $page_title !== '' ? $page_title : 'Login';
	$appVersion = config_item('app_version') ? config_item('app_version') : 'v1.0.0';

	$CI =& get_instance();
	$CI->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi_login');
	$loginLembaga = $CI->pengaturan_aplikasi_login->get_first();
	$namaLembagaLogin = !empty($loginLembaga->nama_lembaga)
		? trim((string) $loginLembaga->nama_lembaga)
		: 'ZISEdu';
	$documentTitle = (stripos($baseTitle, $appName) !== FALSE)
		? $baseTitle
		: $baseTitle . ' - ' . $appName;

	$faviconUrl = '';
	if (!empty($loginLembaga->logo_path)) {
		$relativeLogoPath = ltrim($loginLembaga->logo_path, '/\\');
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
	<link rel="stylesheet"
		href="<?php echo base_url('asset/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/adminlte/dist/css/adminlte.min.css'); ?>">
	<style>
		html,
		body {
			height: 100%;
		}

		.app-login-page {
			min-height: 100vh;
			background: radial-gradient(circle at 20% 10%, #f7f9fc 0%, #eef2f7 45%, #e9edf4 100%);
			padding: 0;
		}

		.app-login-wrapper {
			width: 100%;
			max-width: none;
			min-height: 100vh;
		}

		.app-login-wrapper>.row {
			min-height: 100vh;
			margin: 0;
		}

		.app-login-wrapper>.row>[class*="col-"] {
			padding-left: 0;
			padding-right: 0;
		}

		.app-info-panel {
			background: linear-gradient(135deg, #0d6efd, #0b5ed7);
			color: #fff;
			padding: 48px;
			height: 100%;
			display: flex;
			flex-direction: column;
			justify-content: center;
			position: relative;
			overflow: hidden;
		}

		.app-info-panel::before,
		.app-info-panel::after {
			content: "";
			position: absolute;
			border-radius: 50%;
			background: rgba(255, 255, 255, .12);
			pointer-events: none;
		}

		.app-info-panel::before {
			width: 240px;
			height: 240px;
			right: -70px;
			top: -70px;
		}

		.app-info-panel::after {
			width: 300px;
			height: 300px;
			left: -90px;
			bottom: -110px;
		}

		.app-info-panel h2 {
			font-weight: 700;
		}

		.app-info-panel ul {
			padding-left: 18px;
			margin-bottom: 0;
		}

		.app-feature-chips {
			display: flex;
			gap: 10px;
			flex-wrap: wrap;
			margin: 12px 0 16px;
		}

		.app-feature-chip {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 7px 12px;
			border-radius: 999px;
			font-size: 13px;
			font-weight: 600;
			background: rgba(255, 255, 255, .18);
			border: 1px solid rgba(255, 255, 255, .24);
			transition: all .2s ease;
		}

		.app-feature-chip:hover {
			background: rgba(255, 255, 255, .3);
			transform: translateY(-2px);
		}

		.app-illustration {
			margin-top: 28px;
			background: rgba(255, 255, 255, .12);
			border: 1px solid rgba(255, 255, 255, .25);
			border-radius: 14px;
			padding: 14px;
			animation: floatIn .8s ease;
		}

		.app-illustration svg {
			display: block;
			width: 100%;
			height: auto;
		}

		.login-side {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 32px;
			background: linear-gradient(180deg, #f8fafc 0%, #f2f5fa 100%);
		}

		.login-inner {
			width: 100%;
			max-width: 440px;
			animation: riseIn .6s ease;
		}

		.login-side .card {
			margin-bottom: 0;
			box-shadow: 0 16px 42px rgba(15, 23, 42, .10);
			border-radius: 14px;
			border: 1px solid #e9eef5;
			overflow: hidden;
		}

		.login-logo a {
			color: #1f2937;
			font-size: 2rem;
		}

		.login-side .login-logo {
			text-align: center;
		}

		.login-logo small {
			display: block;
			margin-top: 4px;
			font-size: 13px;
			font-weight: 600;
			letter-spacing: .2px;
			color: #6b7280;
		}

		.login-side .card-header {
			background: #ffffff;
			border-bottom: 1px solid #edf2f7;
			padding: 18px 24px;
		}

		.login-side .card-body {
			padding: 24px;
		}

		.login-side .login-box-msg {
			color: #6b7280;
			margin-bottom: 18px;
		}

		.login-side .input-group {
			margin-bottom: 14px;
		}

		.login-side .input-group .form-control,
		.login-side .input-group .input-group-text {
			height: 46px;
			border-color: #dfe6ef;
			background: #fff;
		}

		.login-side .input-group .form-control {
			border-right: 0;
			padding-left: 14px;
		}

		.login-side .input-group .input-group-text {
			color: #7b8794;
			border-left: 0;
		}

		.login-side .input-group .form-control:focus {
			border-color: #86b7fe;
			box-shadow: none;
		}

		.login-side .input-group .form-control:focus+.input-group-append .input-group-text {
			border-color: #86b7fe;
		}

		.login-side .btn-login {
			height: 40px;
			border-radius: 8px;
			font-weight: 600;
		}

		.login-side .icheck-primary label {
			font-size: 14px;
			color: #4b5563;
		}

		@keyframes riseIn {
			from {
				opacity: 0;
				transform: translateY(14px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes floatIn {
			from {
				opacity: 0;
				transform: translateY(8px) scale(.98);
			}

			to {
				opacity: 1;
				transform: translateY(0) scale(1);
			}
		}

		@media (prefers-reduced-motion: reduce) {
			* {
				animation: none !important;
				transition: none !important;
			}
		}

		@media (max-width: 767.98px) {
			.app-info-panel {
				padding: 28px 22px;
			}

			.app-feature-chip {
				font-size: 12px;
			}

			.login-side {
				padding: 22px;
			}

			.login-side .card-body,
			.login-side .card-header {
				padding-left: 18px;
				padding-right: 18px;
			}
		}
	</style>
</head>

<body class="hold-transition app-login-page">
	<div class="app-login-wrapper">
		<div class="row">
			<div class="col-md-6">
				<div class="app-info-panel">
					<h2 class="mb-3"><?php echo html_escape($appName); ?></h2>
					<p class="mb-3">Sistem informasi pengelolaan <strong>Zakat, Infaq Sodakoh, Elektronik
							Terpadu</strong> yang membantu lembaga mengelola data dan transaksi secara cepat, akurat,
						dan transparan.</p>
					<div class="app-feature-chips">
						<span class="app-feature-chip"><i class="fas fa-hand-holding-heart"></i> Zakat</span>
						<span class="app-feature-chip"><i class="fas fa-donate"></i> Infaq</span>
						<span class="app-feature-chip"><i class="fas fa-hands-helping"></i> Sodakoh</span>
						<span class="app-feature-chip"><i class="fas fa-laptop-code"></i> Elektronik Terpadu</span>
					</div>
					<h5 class="mb-2">Tujuan Aplikasi</h5>
					<ul>
						<li>Memudahkan pencatatan transaksi ZIS secara terpusat.</li>
						<li>Meningkatkan akurasi perhitungan dan pelaporan keuangan.</li>
						<li>Mendukung transparansi pengelolaan dana kepada pihak terkait.</li>
						<li>Mempercepat proses administrasi dari penerimaan hingga penyaluran.</li>
					</ul>

					<div class="app-illustration" aria-hidden="true">
						<svg viewBox="0 0 720 320" xmlns="http://www.w3.org/2000/svg" role="img">
							<defs>
								<linearGradient id="cardGrad" x1="0" y1="0" x2="1" y2="1">
									<stop offset="0%" stop-color="#ffffff" stop-opacity="0.95" />
									<stop offset="100%" stop-color="#e8f1ff" stop-opacity="0.95" />
								</linearGradient>
							</defs>
							<rect x="20" y="18" width="220" height="284" rx="16" fill="url(#cardGrad)" />
							<rect x="44" y="48" width="172" height="14" rx="7" fill="#94bfff" />
							<rect x="44" y="76" width="140" height="10" rx="5" fill="#c4dbff" />
							<rect x="44" y="104" width="172" height="10" rx="5" fill="#d8e8ff" />
							<rect x="44" y="128" width="110" height="10" rx="5" fill="#d8e8ff" />
							<circle cx="130" cy="222" r="46" fill="#0d6efd" />
							<path d="M112 222l12 12 24-24" fill="none" stroke="#fff" stroke-width="9"
								stroke-linecap="round" stroke-linejoin="round" />

							<rect x="276" y="42" width="424" height="240" rx="20" fill="#ffffff" fill-opacity="0.94" />
							<rect x="306" y="76" width="154" height="16" rx="8" fill="#0d6efd" fill-opacity="0.85" />
							<rect x="306" y="108" width="364" height="10" rx="5" fill="#d8e8ff" />
							<rect x="306" y="132" width="324" height="10" rx="5" fill="#d8e8ff" />
							<rect x="306" y="156" width="270" height="10" rx="5" fill="#d8e8ff" />
							<rect x="306" y="196" width="154" height="56" rx="12" fill="#20c997" fill-opacity="0.92" />
							<rect x="478" y="196" width="192" height="56" rx="12" fill="#f8f9fa" stroke="#dbe7ff" />
							<circle cx="640" cy="58" r="18" fill="#20c997" />
							<path d="M632 58l6 6 10-10" fill="none" stroke="#fff" stroke-width="4"
								stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</div>
				</div>
			</div>

			<div class="col-md-6 login-side">
				<div class="login-inner">
					<div class="login-logo mb-3">
						<a href="<?php echo site_url(); ?>"><?php echo html_escape($namaLembagaLogin); ?></a>
						<small>Panel Administrasi</small>
					</div>

					<div class="card card-outline card-primary">
						<div class="card-header text-center">
							<p class="h4 mb-0">Masuk Panel Admin</p>
						</div>
						<div class="card-body">
							<p class="login-box-msg">Silakan login untuk melanjutkan</p>

							<?php if ($this->session->flashdata('error')): ?>
								<div class="alert alert-danger" role="alert">
									<?php echo $this->session->flashdata('error'); ?>
								</div>
							<?php endif; ?>

							<?php if (validation_errors()): ?>
								<div class="alert alert-warning" role="alert">
									<?php echo validation_errors(); ?>
								</div>
							<?php endif; ?>

							<?php echo form_open('login/process'); ?>
							<div class="input-group mb-3">
								<input type="text" class="form-control" name="username" placeholder="Username"
									autocomplete="username" value="<?php echo set_value('username'); ?>" required>
								<div class="input-group-append">
									<div class="input-group-text">
										<span class="fas fa-user"></span>
									</div>
								</div>
							</div>

							<div class="input-group mb-3">
								<input type="password" class="form-control" name="password" placeholder="Password"
									autocomplete="current-password" required>
								<div class="input-group-append">
									<div class="input-group-text">
										<span class="fas fa-lock"></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8">
									<div class="icheck-primary">
										<input type="checkbox" id="remember" name="remember" value="1">
										<label for="remember">Ingat saya</label>
									</div>
								</div>
								<div class="col-4">
									<button type="submit" class="btn btn-primary btn-block btn-login">Masuk</button>
								</div>
							</div>
							<?php echo form_close(); ?>
							<div class="text-center mt-3 text-muted">
								<small>Versi <?php echo html_escape($appVersion); ?></small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="<?php echo base_url('asset/adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('asset/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?php echo base_url('asset/adminlte/dist/js/adminlte.min.js'); ?>"></script>
</body>

</html>