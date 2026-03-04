<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="<?php echo base_url('asset/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/adminlte/dist/js/adminlte.min.js'); ?>"></script>
<script>
	(function () {
		const currencyFieldNames = [
			'fitrah_per_jiwa_rupiah',
			'harga_beras_per_kg',
			'nilai_emas_per_gram',
			'nominal_uang',
			'total_uang',
			'total_harta',
			'total_hutang_jatuh_tempo',
			'harta_bersih',
			'nilai_nishab',
			'total_zakat'
		];

		function formatIdr(value) {
			const number = Number(value);
			if (!isFinite(number)) {
				return 'Rp 0';
			}

			const hasDecimal = Math.abs(number % 1) > 0;
			return 'Rp ' + new Intl.NumberFormat('id-ID', {
				minimumFractionDigits: hasDecimal ? 2 : 0,
				maximumFractionDigits: 2
			}).format(number);
		}

		function isCurrencyInput(input) {
			if (!input || input.tagName !== 'INPUT') {
				return false;
			}

			if (input.type !== 'number') {
				return false;
			}

			const name = (input.getAttribute('name') || '').toLowerCase();
			return currencyFieldNames.some(function (field) {
				return name === field || name.indexOf(field + '[') === 0;
			});
		}

		function ensureRpPrefix(input) {
			if (!isCurrencyInput(input) || input.dataset.rpApplied === '1') {
				return;
			}

			const parent = input.parentElement;
			if (!parent) {
				return;
			}

			if (parent.classList.contains('input-group')) {
				const hasPrepend = parent.querySelector('.input-group-prepend .input-group-text[data-currency="idr"]');
				if (!hasPrepend) {
					const prepend = document.createElement('div');
					prepend.className = 'input-group-prepend';

					const text = document.createElement('span');
					text.className = 'input-group-text';
					text.dataset.currency = 'idr';
					text.textContent = 'Rp';

					prepend.appendChild(text);
					parent.insertBefore(prepend, parent.firstChild);
				}

				input.dataset.rpApplied = '1';
			} else {
				const wrapper = document.createElement('div');
				wrapper.className = 'input-group';

				const prepend = document.createElement('div');
				prepend.className = 'input-group-prepend';

				const text = document.createElement('span');
				text.className = 'input-group-text';
				text.dataset.currency = 'idr';
				text.textContent = 'Rp';

				prepend.appendChild(text);

				parent.insertBefore(wrapper, input);
				wrapper.appendChild(prepend);
				wrapper.appendChild(input);

				input.dataset.rpApplied = '1';
			}

			const group = input.closest('.input-group') || input.parentElement;
			if (!group) {
				return;
			}

			let preview = group.parentElement.querySelector('.currency-preview[data-for="' + (input.id || input.name) + '"]');
			if (!preview) {
				preview = document.createElement('small');
				preview.className = 'form-text text-muted currency-preview';
				preview.dataset.for = input.id || input.name;
				group.parentElement.appendChild(preview);
			}

			function updatePreview() {
				preview.textContent = formatIdr(input.value || 0);
			}

			input.addEventListener('input', updatePreview);
			input.addEventListener('change', updatePreview);
			input.addEventListener('keyup', updatePreview);
			updatePreview();
		}

		function applyCurrencyPrefix() {
			const inputs = document.querySelectorAll('input[type="number"]');
			inputs.forEach(ensureRpPrefix);
		}

		document.addEventListener('DOMContentLoaded', applyCurrencyPrefix);
	})();
</script>