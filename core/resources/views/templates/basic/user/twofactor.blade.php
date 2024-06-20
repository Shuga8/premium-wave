@extends($activeTemplate . 'layouts.master')
@section('content')
	<div class="row justify-content-center gy-4">
		<div class="col-xxl-9 col-lg-12">
			
		
		</div>
		<div class="col-xxl-3">
			<div class="dashboard-right">
		
				<div class="right-sidebar mt-3">
					<div class="right-sidebar__header skeleton mb-3">
						<h4 class="fs-18 mb-0">@lang('Deposit Money')</h4>
						<p class="fs-12 mt-0">@lang('Make deposits in a few steps')</p>
					</div>
					<div class="right-sidebar__deposit">
						<form class="skeleton deposit-form" action="{{ route('user.deposit.index') }}">
							<div class="form-group position-relative" id="currency_list_wrapper">
								<div class="input-group">
									<input class="form--control form-control" name="amount" type="number" step="any"
										placeholder="@lang('Amount')">
									<div class="input-group-text skeleton">

										<select class="" id="currency_list" name="currency" required>

											<option value="USD" selected>@lang('USD')</option>

										</select>
										{{-- <x-currency-list valueType="2" :action="route('user.currency.all')" logCurrency="true" /> --}}
									</div>
								</div>
							</div>
							<button class="deposit__button btn btn--base w-100" type="submit">
								<span class="icon-deposit"></span> @lang('Deposit')
							</button>
						</form>
					</div>
				</div>
				<div class="right-sidebar mt-3">
					<div class="right-sidebar__header skeleton mb-3">
						<h4 class="fs-18 mb-0">@lang('Withdraw Money')</h4>
						<p class="fs-12 mt-0">@lang('Withdrawal your balance with our world-class withdrawal process')</p>
						<a class="deposit__button btn btn--base w-100" type="submit">
							<span class="icon-withdraw"></span> @lang('Withdraw')
						</a>
					</div>
					{{-- <div class="right-sidebar__deposit">
						<form class="skeleton withdraw-form">
							<div class="form-group position-relative" id="withdraw_currency_list_wrapper">
								<div class="input-group">
									<input class="form--control form-control" name="amount" type="number" step="any"
										placeholder="@lang('Amount')">
									<div class="input-group-text skeleton">

										<span>USD</span>
										<x-currency-list id="withdraw_currency_list" valueType="2" :action="route('user.currency.all')"
											parent="withdraw_currency_list_wrapper" logCurrency="true" />
									</div>
								</div>
							</div>
							<button class="deposit__button btn btn--base w-100" type="submit">
								<span class="icon-withdraw"></span> @lang('Withdraw')
							</button>
						</form>
					</div> --}}
				</div>
			</div>
		</div>
	</div>
	<x-flexible-view :view="$activeTemplate . 'user.components.canvas.deposit'" />
	<x-flexible-view :view="$activeTemplate . 'user.components.canvas.withdraw'" :meta="['withdrawMethods' => $withdrawMethods]" />
@endsection

@push('script-lib')
	<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
	<link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('script')
	<script>
		"use strict";
		(function($) {

			$('.2fa-notice').on('click', '.delete-icon', function(e) {
				$(this).closest('.col-12').fadeOut('slow', function() {
					$(this).remove();
				});
			});

			let walletSkip = 3;

			$('.show-more-wallet').on('click', function(e) {
				let route = "{{ route('user.more.wallet', ':skip') }}";
				let $this = $(this);
				$.ajax({
					url: route.replace(':skip', walletSkip),
					type: "GET",
					dataType: 'json',
					cache: false,
					beforeSend: function() {
						$this.html(`
                        <span class="right-sidebar__button-icon">
                            <i class="las la-spinner la-spin"></i>
                        </span>`).attr('disabled', true);
					},
					complete: function(e) {
						setTimeout(() => {
							$this.html(`
                        <span class="right-sidebar__button-icon">
                            <i class="las la-chevron-circle-down"></i>
                        </span>@lang('Show More')`).attr('disabled', false);
							$('.wallet-list').removeClass('skeleton');
						}, 500);
					},
					success: function(resp) {
						if (resp.success && (resp.wallets && resp.wallets.length > 0)) {
							let html = "";
							$.each(resp.wallets, function(i, wallet) {
								html += `
                            <div class="right-sidebar__item wallet-list skeleton">
                                <div class="d-flex align-items-center">
                                    <span class="right-sidebar__item-icon">
                                        <img src="${wallet.currency.image_url}">
                                    </span>
                                    <h6 class="right-sidebar__item-name">
                                        ${wallet.currency.name}
                                        <span class="fs-11 d-block">
                                            ${wallet.currency.symbol}
                                        </span>
                                    </h6>
                                </div>

                                <h6 class="right-sidebar__item-number">${getAmount(wallet.balance)}</h6>
                            </div>
                            `
							});
							walletSkip += 3;
							$('.wallet-wrapper').append(html);
						} else {
							$this.remove();
						}

						$('.right-sidebar__menu').animate({
							scrollTop: $('.right-sidebar__menu')[0].scrollHeight + 150
						}, "slow");
					},
					error: function() {
						notify('error', "@lang('something went to wrong')");
						$this.remove();
					}
				});
			});

		})(jQuery);
	</script>
@endpush


@push('topContent')
	<h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush
