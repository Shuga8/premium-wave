<div class="offcanvas offcanvas-end p-5" id="deposit-canvas" aria-labelledby="offcanvasLabel" tabindex="-1">
	<div class="offcanvas-header">
		<h4 class="fs-18 offcanvas-title mb-0">
			@lang('Deposit Preview')
		</h4>
		<button class="btn-close text-reset" data-bs-dismiss="offcanvas" type="button" aria-label="Close">
			<i class="fa fa-times-circle"></i>
		</button>
	</div>
	<div class="offcanvas-body">

	</div>
</div>
@push('script')
	<script>
		(function($) {
			@if (!@$singleCurrency)
				$('.deposit-form').on('submit', function(e) {
						e.preventDefault();
						let currency = $(`.deposit-form select[name=currency]`).val();

						$(`select[name=wallet_type]`).val();


						let amount = $(`.deposit-form input[name=amount]`).val();

						if (!currency) {
							notify('error', "@lang('Currency field is required')");
							return false;
						}

						if (!amount) {
							notify('error', "@lang('Amount field is required')");
							return false;
						}
					@endif
				})

		})(jQuery);
	</script>
@endpush
