<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
$CI->load->model('Pengaturan_aplikasi_model', 'pengaturan_aplikasi_sidebar');
$sidebarLembaga = $CI->pengaturan_aplikasi_sidebar->get_first();

$sidebarLogoUrl = '';
if (!empty($sidebarLembaga->logo_path)) {
	$sidebarLogoUrl = base_url(ltrim($sidebarLembaga->logo_path, '/\\'));
}

$sidebarBrandText = !empty($sidebarLembaga->nama_lembaga)
	? $sidebarLembaga->nama_lembaga
	: 'ZISEdu';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="<?php echo site_url(); ?>" class="brand-link">
		<?php if ($sidebarLogoUrl !== ''): ?>
			<img src="<?php echo $sidebarLogoUrl; ?>" alt="Logo" class="brand-image img-circle elevation-3"
				style="opacity: .9;">
		<?php else: ?>
			<i class="fas fa-school brand-image" style="margin-top: 8px;"></i>
		<?php endif; ?>
		<span class="brand-text font-weight-light"><?php echo html_escape($sidebarBrandText); ?></span>
	</a>

	<div class="sidebar">
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?php echo site_url('welcome'); ?>" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>

				<li class="nav-header">MASTER DATA</li>
				<li class="nav-item"><a href="<?php echo site_url('users'); ?>" class="nav-link"><i
							class="nav-icon fas fa-user-shield"></i>
						<p>Users</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('pengaturan_zakat'); ?>" class="nav-link"><i
							class="nav-icon fas fa-cogs"></i>
						<p>Pengaturan Zakat</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('pengaturan_aplikasi'); ?>" class="nav-link"><i
							class="nav-icon fas fa-building"></i>
						<p>Pengaturan Aplikasi</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('muzakki'); ?>" class="nav-link"><i
							class="nav-icon fas fa-users"></i>
						<p>Muzakki</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('tanggungan_fitrah'); ?>" class="nav-link"><i
							class="nav-icon fas fa-user-friends"></i>
						<p>Tanggungan Fitrah</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('mustahik'); ?>" class="nav-link"><i
							class="nav-icon fas fa-hands-helping"></i>
						<p>Mustahik</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('jenis_harta_mal'); ?>" class="nav-link"><i
							class="nav-icon fas fa-coins"></i>
						<p>Jenis Harta Mal</p>
					</a></li>

				<li class="nav-header">TRANSAKSI</li>
				<li class="nav-item"><a href="<?php echo site_url('zakat_fitrah'); ?>" class="nav-link"><i
							class="nav-icon fas fa-donate"></i>
						<p>Zakat Fitrah</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('zakat_mal'); ?>" class="nav-link"><i
							class="nav-icon fas fa-wallet"></i>
						<p>Zakat Mal</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('penyaluran'); ?>" class="nav-link"><i
							class="nav-icon fas fa-share-square"></i>
						<p>Penyaluran</p>
					</a></li>
				<li class="nav-item"><a href="<?php echo site_url('infaq_shodaqoh'); ?>" class="nav-link"><i
							class="nav-icon fas fa-hand-holding-usd"></i>
						<p>Infaq & Shodaqoh</p>
					</a></li>

				<li class="nav-header">LAPORAN</li>
				<li class="nav-item"><a href="<?php echo site_url('laporan'); ?>" class="nav-link"><i
							class="nav-icon fas fa-file-alt"></i>
						<p>Laporan</p>
					</a></li>

				<li class="nav-header">AKUN</li>
				<li class="nav-item"><a href="<?php echo site_url('logout'); ?>" class="nav-link"><i
							class="nav-icon fas fa-sign-out-alt"></i>
						<p>Logout</p>
					</a></li>
			</ul>
		</nav>
	</div>
</aside>
