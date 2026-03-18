<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open('transaksi_unified/store'); ?>
<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">Transaksi Terpadu - Gabungan Zakat Fitrah, Mal & Infaq Shodaqoh</h3>
	</div>
	<div class="card-body">
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
		<?php endif; ?>
		<?php if (validation_errors()): ?>
			<div class="alert alert-warning"><?php echo validation_errors(); ?></div>
		<?php endif; ?>

		<!-- Shared Muzakki -->
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card bg-light">
					<div class="card-header bg-info text-white">
						<h5 class="mb-0"><i class="fas fa-user"></i> Muzakki (Dibagikan untuk Zakat Fitrah & Mal)</h5>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label>Muzakki <span class="text-danger">*</span></label>
							<select name="muzakki_id" id="muzakki_id" class="form-control" required>
								<option value="">-- Pilih Muzakki --</option>
								<?php if (isset($muzakki_options) && is_array($muzakki_options)): ?>
									<?php foreach ($muzakki_options as $id => $nama): ?>
										<option value="<?php echo $id; ?>"><?php echo html_escape($nama); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Type Selection -->
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card bg-light">
					<div class="card-header bg-primary text-white">
						<h5 class="mb-0"><i class="fas fa-list-ul"></i> Pilih Jenis Transaksi</h5>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="include_fitrah"
										id="include_fitrah" value="1">
									<label class="form-check-label" for="include_fitrah">
										<i class="fas fa-users text-success"></i> Zakat Fitrah
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="include_mal" id="include_mal"
										value="1">
									<label class="form-check-label" for="include_mal">
										<i class="fas fa-coins text-warning"></i> Zakat Mal
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="include_infaq"
										id="include_infaq" value="1">
									<label class="form-check-label" for="include_infaq">
										<i class="fas fa-hand-holding-heart text-info"></i> Infaq Shodaqoh
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Shared Date/Year Section -->
		<div id="shared-section" class="form-section mb-4">
			<div class="card bg-light">
				<div class="card-header bg-secondary text-white">
					<h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Data Umum (Digunakan oleh Zakat Fitrah & Mal)
					</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-lg-4 col-md-4 form-group mb-0">
							<label>Tahun Masehi <small class="d-block text-muted">(Fitrah & Mal)</small></label>
							<input type="number" name="tahun_masehi" id="shared_tahun_masehi" class="form-control"
								value="<?php echo date('Y'); ?>">
						</div>
						<div class="col-12 col-lg-4 col-md-4 form-group mb-0">
							<label>Tahun Hijriah <small class="d-block text-muted">(Auto)</small></label>
							<input type="text" name="tahun_hijriah_shared" id="shared_tahun_hijriah"
								class="form-control" readonly>
						</div>
						<div class="col-12 col-lg-4 col-md-4 form-group mb-0">
							<label>Tanggal Bayar/Hitung <small class="d-block text-muted">(Fitrah & Mal)</small></label>
							<input type="date" name="shared_tanggal_bayar" id="shared_tanggal_bayar"
								class="form-control" value="<?php echo date('Y-m-d'); ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-lg-6 col-md-6 form-group mb-0">
							<label>Status Default</label>
							<select name="shared_status" id="shared_status" class="form-control">
								<option value="lunas">Lunas</option>
								<option value="draft">Draft</option>
								<option value="batal">Batal</option>
							</select>
						</div>
						<div class="col-12 col-lg-6 col-md-6 form-group mb-0">
							<label>Metode Bayar</label>
							<select name="shared_metode_bayar" id="shared_metode_bayar" class="form-control">
								<option value="tunai">Tunai</option>
								<option value="transfer">Transfer</option>
								<option value="qris">QRIS</option>
								<option value="lainnya">Lainnya</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Zakat Fitrah Section -->
		<div id="fitrah-section" class="form-section d-none mb-4">
			<div class="card bg-light border-success">
				<div class="card-header bg-success text-white">
					<h5 class="mb-0"><i class="fas fa-users"></i> Zakat Fitrah</h5>
				</div>
				<div class="card-body">
					<input type="hidden" name="tanggal_transaksi_fitrah" value="<?php echo date('Y-m-d'); ?>">
					<input type="hidden" name="metode_bayar_fitrah" id="fitrah_metode_bayar">
					<input type="hidden" name="status_fitrah" id="fitrah_status">
					<div class="row">
						<div class="col-md-4 form-group">
							<label>Tahun Hijriah <small>(Auto)</small></label>
							<input type="text" name="tahun_hijriah_fitrah" class="form-control" readonly>
						</div>
						<div class="col-md-4 form-group">
							<label>Jumlah Jiwa <span class="text-danger">*</span></label>
							<input type="number" name="jumlah_jiwa_fitrah" id="jumlah_jiwa_fitrah" class="form-control"
								min="1">
						</div>
						<div class="col-md-4 form-group">
							<label>Metode Tunaikan</label>
							<select name="metode_tunaikan_fitrah" class="form-control">
								<option value="uang">Uang</option>
								<option value="beras">Beras</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 form-group">
							<label>Beras (Kg)</label>
							<input type="number" step="0.01" name="beras_kg_fitrah" class="form-control" min="0">
						</div>
						<div class="col-md-6 form-group">
							<label>Nominal Uang <span class="text-danger">*</span></label>
							<input type="number" step="0.01" name="nominal_uang_fitrah" class="form-control" min="0">
						</div>
					</div>

					<div class="form-group">
						<label>Keterangan</label>
						<textarea name="keterangan_fitrah" rows="2" class="form-control"></textarea>
					</div>
				</div>
			</div>
		</div>

		<!-- Zakat Mal Section -->
		<div id="mal-section" class="form-section d-none mb-4">
			<div class="card bg-light border-warning">
				<div class="card-header bg-warning text-dark">
					<h5 class="mb-0"><i class="fas fa-coins"></i> Zakat Mal</h5>
				</div>
				<div class="card-body">
					<input type="hidden" name="tanggal_transaksi_mal" value="<?php echo date('Y-m-d'); ?>">
					<input type="hidden" name="metode_bayar_mal" id="mal_metode_bayar">
					<input type="hidden" name="tahun_masehi_mal" id="mal_tahun_masehi">
					<input type="hidden" name="tanggal_bayar_mal" id="mal_tanggal_bayar">
					<div class="row">
						<div class="col-12 col-md-6 form-group">
							<label>Tanggal Hitung <small class="text-muted">(dari Umum)</small></label>
							<input type="date" name="tanggal_hitung_mal" id="mal_tanggal_hitung" class="form-control"
								readonly value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-12 col-md-6 form-group">
							<label>Mode Perhitungan</label>
							<select name="mode_perhitungan_mal" id="mode_perhitungan_mal" class="form-control">
								<option value="otomatis">Otomatis</option>
								<option value="manual">Manual</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-md-3 form-group">
							<label>Total Zakat <span class="text-danger">*</span></label>
							<input type="number" step="0.01" name="total_zakat_mal" id="total_zakat_mal"
								class="form-control">
						</div>
						<div class="col-12 col-md-3 form-group">
							<label>Total Harta</label>
							<input type="number" step="0.01" name="total_harta_mal" id="total_harta_mal"
								class="form-control">
						</div>
						<div class="col-12 col-md-3 form-group">
							<label>Total Hutang J.T.</label>
							<input type="number" step="0.01" name="total_hutang_jatuh_tempo_mal"
								id="total_hutang_jatuh_tempo_mal" class="form-control">
						</div>
						<div class="col-12 col-md-3 form-group">
							<label>Harta Bersih</label>
							<input type="number" step="0.01" name="harta_bersih_mal" id="harta_bersih_mal"
								class="form-control" readonly>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-3 form-group">
							<label>Nilai Nishab</label>
							<input type="number" step="0.01" name="nilai_nishab_mal" id="nilai_nishab_mal"
								class="form-control">
						</div>
						<div class="col-12 col-md-3 form-group">
							<label>Persentase Zakat (%)</label>
							<input type="number" step="0.01" name="persentase_zakat_mal" id="persentase_zakat_mal"
								class="form-control" value="2.5">
						</div>
						<div class="col-12 col-md-6 form-group">
							<label>Keterangan</label>
							<textarea name="keterangan_mal" rows="2" class="form-control"></textarea>
						</div>
					</div>

					<!-- Detail Aset Table -->
					<h6>Detail Aset Harta</h6>
					<div class="table-responsive">
						<table class="table table-bordered table-sm">
							<thead>
								<tr>
									<th>Jenis Harta</th>
									<th>Nilai Harta</th>
									<th>Haul (bln)</th>
									<th>Ket.</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select name="detail_mal[0][jenis_harta_id]" class="form-control">
											<option value="">Pilih</option>
											<?php if (isset($jenis_harta_options)): ?>
												<?php foreach ($jenis_harta_options as $id => $nama): ?>
													<option value="<?php echo $id; ?>"><?php echo html_escape($nama); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</td>
									<td><input type="number" step="0.01" name="detail_mal[0][nilai_harta]"
											class="form-control" min="0"></td>
									<td><input type="number" name="detail_mal[0][nilai_haul_bulan]"
											class="form-control"></td>
									<td><input type="text" name="detail_mal[0][keterangan]" class="form-control"></td>
									<td><button type="button" class="btn btn-sm btn-danger remove-detail">X</button>
									</td>
								</tr>
							</tbody>
						</table>
						<button type="button" id="add-detail-mal" class="btn btn-sm btn-success">Tambah Baris</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Infaq Shodaqoh Section -->
		<div id="infaq-section" class="form-section d-none mb-4">
			<div class="card bg-light border-info">
				<div class="card-header bg-info text-white">
					<h5 class="mb-0"><i class="fas fa-hand-holding-heart"></i> Infaq Shodaqoh</h5>
				</div>
				<div class="card-body">
					<input type="hidden" name="tanggal_transaksi_infaq" value="<?php echo date('Y-m-d'); ?>">
					<div class="row">
						<div class="col-md-6 form-group">
							<label>Jenis Dana</label>
							<select name="jenis_dana_infaq" class="form-control">
								<option value="infaq">Infaq</option>
								<option value="shodaqoh">Shodaqoh</option>
								<option value="infaq_shodaqoh">Infaq & Shodaqoh</option>
							</select>
						</div>
						<div class="col-md-6 form-group">
							<label>No HP (Donatur)</label>
							<input type="text" name="no_hp_infaq" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 form-group">
							<label>Nominal Uang <span class="text-danger">*</span></label>
							<input type="number" step="0.01" name="nominal_uang_infaq" class="form-control" min="0">
						</div>
						<div class="col-md-4 form-group">
							<label>Metode Bayar</label>
							<select name="metode_bayar_infaq" class="form-control">
								<option value="tunai">Tunai</option>
								<option value="transfer">Transfer</option>
								<option value="qris">QRIS</option>
								<option value="lainnya">Lainnya</option>
							</select>
						</div>
						<div class="col-md-4 form-group">
							<label>Status</label>
							<select name="status_infaq" class="form-control">
								<option value="diterima">Diterima</option>
								<option value="draft">Draft</option>
								<option value="batal">Batal</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea name="keterangan_infaq" rows="2" class="form-control"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit" class="btn btn-primary btn-lg">
			<i class="fas fa-save"></i> Simpan & Cetak Kwitansi Gabungan
		</button>
		<button type="button" id="btn-fallback-index" class="btn btn-warning">Kirim (paksa index.php)</button>
		<a href="<?php echo site_url('dashboard'); ?>" class="btn btn-secondary">Kembali</a>
	</div>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function () {
		// Select2 init
		$('.select2').select2({
			theme: 'bootstrap4',
			placeholder: '-- Pilih --',
			allowClear: true
		});

		// Muzakki auto-fill for jumlah_jiwa_fitrah
		function loadMuzakkiInfo() {
			const muzakkiEl = $('#muzakki_id');
			const jumlahJiwaEl = $('#jumlah_jiwa_fitrah');
			const namaDonaturInfaqEl = document.getElementById('nama_donatur_infaq');
			const muzakkiId = muzakkiEl.val();

			if (!muzakkiId) {
				jumlahJiwaEl.val('').prop('readonly', false);
				if (namaDonaturInfaqEl) namaDonaturInfaqEl.value = '';
				return;
			}

			const baseInfoUrl = '<?php echo site_url('transaksi_unified/muzakki_info/'); ?>';

			fetch(baseInfoUrl + encodeURIComponent(muzakkiId))
				.then(response => {
					if (!response.ok) {
						throw new Error('Gagal mengambil data muzakki');
					}
					return response.json();
				})
				.then(data => {
					if (data.error) {
						alert('Error: ' + data.error);
						jumlahJiwaEl.val('').prop('readonly', false);
						if (namaDonaturInfaqEl) namaDonaturInfaqEl.value = '';
						return;
					}
					if (data.jenis_muzakki && data.jenis_muzakki !== 'individu') {
						jumlahJiwaEl.val(parseInt(data.jumlah_jiwa_otomatis || 1, 10)).prop('readonly', true);
					} else {
						jumlahJiwaEl.val('').prop('readonly', false);
					}
					if (namaDonaturInfaqEl) namaDonaturInfaqEl.value = data.nama || '';
				})
				.catch(error => {
					console.error('Error:', error);
					alert('Gagal load data muzakki: ' + error.message);
					jumlahJiwaEl.val('').prop('readonly', false);
					if (namaDonaturInfaqEl) namaDonaturInfaqEl.value = '';
				});
		}

		// Bind events (native + jQuery for Select2)
		$('#muzakki_id').on('change', loadMuzakkiInfo);
		if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
			$('#muzakki_id').on('select2:select select2:clear', loadMuzakkiInfo);
		}

		// Shared fields sync to sections
		function syncSharedFields() {
			const tahun = $('#shared_tahun_masehi').val();
			const tanggal = $('#shared_tanggal_bayar').val();
			const metode = $('#shared_metode_bayar').val();
			const status = $('#shared_status').val();

			// Sync to Fitrah
			$('#fitrah_metode_bayar').val(metode);
			$('#fitrah_status').val(status);
			$('input[name="tahun_masehi_fitrah"]').val(tahun);
			$('input[name="tanggal_bayar_fitrah"]').val(tanggal);

			// Sync to Mal  
			$('#mal_metode_bayar').val(metode);

			$('#mal_tahun_masehi').val(tahun);
			$('#mal_tanggal_bayar').val(tanggal);
			$('#mal_tanggal_hitung').val(tanggal);
		}

		// Watch shared changes
		$('#shared_tahun_masehi, #shared_tanggal_bayar, #shared_metode_bayar, #shared_status').on('change', syncSharedFields);

		// Checkbox toggle sections (single form)
		$('input[name^=include_]').change(function () {
			var sectionId = $(this).attr('id').replace('include_', '') + '-section';
			var $section = $('#' + sectionId);
			if ($(this).is(':checked')) {
				$section.removeClass('d-none');
				$section.find('input[required], select[required]').prop('required', true);
				syncSharedFields();
				// Scroll to section
				$('html, body').animate({
					scrollTop: $section.offset().top - 100
				}, 500);
			} else {
				$section.addClass('d-none');
				$section.find('input:not([readonly]), select, textarea').val('').prop('required', false);
				// Reset calculations if hiding mal
				if ($(this).attr('id') === 'include_mal') {
					const malInputs = {
						totalHarta: document.querySelector('#total_harta_mal'),
						hutang: document.querySelector('#total_hutang_jatuh_tempo_mal'),
						hartaBersih: document.querySelector('#harta_bersih_mal'),
						nishab: document.querySelector('#nilai_nishab_mal'),
						persentase: document.querySelector('#persentase_zakat_mal'),
						totalZakat: document.querySelector('#total_zakat_mal')
					};
					Object.values(malInputs).forEach(input => input ? input.value = '' : null);
				}
			}
		}).trigger('change');

		// Initial sync
		syncSharedFields();

		// Shared hijriah auto-calc + sync to Fitrah
		const sharedTahun = document.querySelector('#shared_tahun_masehi');
		const sharedHijriah = document.querySelector('#shared_tahun_hijriah');
		const hijriahFitrah = document.querySelector('input[name="tahun_hijriah_fitrah"]');
		function updateHijriah() {
			if (sharedTahun && sharedHijriah) {
				const masehi = parseInt(sharedTahun.value);
				if (!isNaN(masehi)) {
					const hijriah = Math.floor((masehi - 622) * 33 / 32);
					sharedHijriah.value = hijriah + ' H';
					if (hijriahFitrah) hijriahFitrah.value = hijriah + ' H';
				} else {
					sharedHijriah.value = '';
					if (hijriahFitrah) hijriahFitrah.value = '';
				}
			}
		}
		sharedTahun?.addEventListener('input', updateHijriah);

		updateHijriah();

		// Mal calc JS (copy from individual)
		const malInputs = {
			totalHarta: document.querySelector('#total_harta_mal'),
			hutang: document.querySelector('#total_hutang_jatuh_tempo_mal'),
			hartaBersih: document.querySelector('#harta_bersih_mal'),
			nishab: document.querySelector('#nilai_nishab_mal'),
			persentase: document.querySelector('#persentase_zakat_mal'),
			totalZakat: document.querySelector('#total_zakat_mal'),
			mode: document.querySelector('#mode_perhitungan_mal')
		};

		function recalcMal() {
			const harta = parseFloat(malInputs.totalHarta?.value || 0);
			const hutang = parseFloat(malInputs.hutang?.value || 0);
			const bersih = Math.max(0, harta - hutang);
			if (malInputs.hartaBersih) malInputs.hartaBersih.value = bersih.toFixed(2);

			const nishab = parseFloat(malInputs.nishab?.value || 0);
			const pct = parseFloat(malInputs.persentase?.value || 0);
			const zakat = bersih >= nishab ? (bersih * pct / 100) : 0;
			if (malInputs.totalZakat) malInputs.totalZakat.value = zakat.toFixed(2);
		}

		if (malInputs.mode) {
			malInputs.mode.addEventListener('change', function () {
				if (this.value === 'manual') {
					if (malInputs.totalZakat) malInputs.totalZakat.readOnly = false;
				} else {
					recalcMal();
					if (malInputs.totalZakat) malInputs.totalZakat.readOnly = true;
				}
			});
		}

		[malInputs.totalHarta, malInputs.hutang, malInputs.nishab, malInputs.persentase].forEach(el => {
			if (el) el.addEventListener('input', recalcMal);
		});

		// Mal detail add row
		let malDetailIdx = 1;
		$('#add-detail-mal').click(function () {
			const row = `
			<tr>
				<td>
					<select name="detail_mal[${malDetailIdx}][jenis_harta_id]" class="form-control">
						<option value="">Pilih</option>
						<?php if (isset($jenis_harta_options)):
							foreach ($jenis_harta_options as $id => $nama): ?>
							<option value="<?php echo $id; ?>"><?php echo html_escape($nama); ?></option>
						<?php endforeach; endif; ?>
					</select>
				</td>
				<td><input type="number" step="0.01" name="detail_mal[${malDetailIdx}][nilai_harta]" class="form-control" min="0"></td>
				<td><input type="number" name="detail_mal[${malDetailIdx}][nilai_haul_bulan]" class="form-control"></td>
				<td><input type="text" name="detail_mal[${malDetailIdx}][keterangan]" class="form-control"></td>
				<td><button type="button" class="btn btn-sm btn-danger remove-detail">X</button></td>
			</tr>`;
			$('table tbody').append(row);
			malDetailIdx++;
			$('.select2').select2('destroy').select2({ theme: 'bootstrap4' });
		});

		$(document).on('click', '.remove-detail', function () {
			$(this).closest('tr').remove();
		});

		// Fallback button: force form to post via index.php when rewrite not available
		$('#btn-fallback-index').on('click', function () {
			var f = $('form').first();
			if (!f || f.length === 0) return;
			f.attr('action', '<?php echo site_url("index.php/transaksi_unified/store"); ?>');
			f.submit();
		});
	});
</script>
<style>
	.form-section {
		margin-bottom: 2rem;
	}

	.form-section .card {
		border-radius: 0.375rem;
		box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
	}

	.form-section.d-none {
		display: none !important;
	}
</style>