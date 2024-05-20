@extends($activeTemplate . 'layouts.master')
@section('content')
	<div class="row justify-content-center gy-4">
		<div class="col-xxl-9 col-lg-12">
			<div class="row gy-3">
				@php
					$kycContent = getContent('kyc_content.content', true);
				@endphp
				@if ($user->kv == Status::KYC_UNVERIFIED)
					<div class="col-12">
						<div class="alert alert--danger skeleton" role="alert">
							<h5 class="alert-heading text--danger mb-2">@lang('KYC Verification Required')</h5>
							<p class="mb-0">
								{{ __(@$kycContent->data_values->unverified_content) }}
								<a class="text--base" href="{{ route('user.kyc.form') }}">@lang('Click here to verify')</a>
							</p>
						</div>
					</div>
				@endif
				@if ($user->kv == Status::KYC_PENDING)
					<div class="col-12">
						<div class="alert alert--warning flex-column justify-content-start align-items-start skeleton" role="alert">
							<h5 class="alert-heading text--warning mb-2">@lang('KYC Verification Pending')</h5>
							<p class="mb-0"> {{ __(@$kycContent->data_values->pending_content) }}
								<a class="text--base" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
							</p>
						</div>
					</div>
				@endif
				@if (!$user->ts)
					<div class="col-12">
						<div class="alert-item 2fa-notice skeleton">
							<span class="delete-icon skeleton" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
								<i class="las la-times"></i></span>
							<div class="alert flex-align alert--danger remove-2fa-notice" role="alert">
								<span class="alert__icon">
									<i class="fas fa-exclamation"></i>
								</span>
								<div class="alert__content">
									<span class="alert__title">
										@lang('To secure your account add 2FA verification').
										<a class="text--base text--small" href="{{ route('user.twofactor') }}">@lang('Enable')</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
			<div class="dashboard-card-wrapper">
				<div class="row gy-4 justify-content-center mb-3">
					<div class="col-xxl-3 col-sm-6">
						<div class="dashboard-card skeleton">
							<div class="d-flex justify-content-between align-items-center">
								<span class="dashboard-card__icon text--base">
									<i class="las la-spinner"></i>
								</span>
								<div class="dashboard-card__content">
									<a class="dashboard-card__coin-name mb-0" href="{{ route('user.order.open') }}">
										@lang('Open Order') </a>
									<h6 class="dashboard-card__coin-title"> {{ getAmount($widget['open_order']) }} </h6>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-sm-6">
						<div class="dashboard-card skeleton">
							<div class="d-flex justify-content-between align-items-center">
								<span class="dashboard-card__icon text--success">
									<i class="las la-check-circle"></i>
								</span>
								<div class="dashboard-card__content">
									<a class="dashboard-card__coin-name mb-0" href="{{ route('user.order.completed') }}">
										@lang('Completed Order') </a>
									<h6 class="dashboard-card__coin-title"> {{ getAmount($widget['completed_order']) }}
									</h6>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-sm-6">
						<div class="dashboard-card skeleton">
							<div class="d-flex justify-content-between align-items-center">
								<span class="dashboard-card__icon text--danger">
									<i class="las la-times-circle"></i>
								</span>
								<div class="dashboard-card__content">
									<a class="dashboard-card__coin-name mb-0" href="{{ route('user.order.canceled') }}">
										@lang('Canceled Order') </a>
									<h6 class="dashboard-card__coin-title"> {{ getAmount($widget['canceled_order']) }}
									</h6>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-sm-6">
						<div class="dashboard-card skeleton">
							<div class="d-flex justify-content-between align-items-center">
								<span class="dashboard-card__icon text--base">
									<span class="icon-trade fs-50"></span>
								</span>
								<div class="dashboard-card__content">
									<a class="dashboard-card__coin-name mb-0" href="{{ route('user.trade.history') }}">@lang('Total Trade') </a>
									<h6 class="dashboard-card__coin-title"> {{ getAmount($widget['total_trade']) }} </h6>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="row gy-4 justify-content-center mb-3">
					<div class="col-lg-6">
						<div class="transection h-100">
							<h5 class="transection__title skeleton"> @lang('Recent Order') </h5>
							@forelse ($recentOrders as $recentOrder)
								<div class="transection__item skeleton">
									<div class="d-flex align-items-center flex-wrap">
										<div class="transection__date">
											<h6 class="transection__date-number text-white">
												{{ showDateTime($recentOrder->created_at, 'd') }}
											</h6>
											<span class="transection__date-text">
												{{ __(strtoupper(showDateTime($recentOrder->created_at, 'M'))) }}
											</span>
										</div>
										<div class="transection__content">
											<h6 class="transection__content-title">
												@php echo $recentOrder->orderSideBadge; @endphp
											</h6>
											<p class="transection__content-desc">
												@lang('Placed an order in the ')
												{{ @$recentOrder->pair->symbol }} @lang('pair to')
												{{ __(strtolower(strip_tags($recentOrder->orderSideBadge))) }}
												{{ showAmount($recentOrder->amount) }}
												{{ @$recentOrder->pair->coin->symbol }}
											</p>
										</div>
									</div>
									@php echo $recentOrder->statusBadge; @endphp
								</div>
							@empty
								<div class="transection__item justify-content-center skeleton p-5">
									<div class="empty-thumb text-center">
										<img src="{{ asset('assets/images/extra_images/empty.png') }}" />
										<p class="fs-14">@lang('No order found')</p>
									</div>
								</div>
							@endforelse
						</div>
					</div>
					<div class="col-lg-6">
						<div class="transection h-100">
							<h5 class="transection__title skeleton"> @lang('Recent Transactions') </h5>
							@forelse ($recentTransactions as $recentTransaction)
								<div class="transection__item skeleton">
									<div class="d-flex align-items-center flex-wrap">
										<div class="transection__date">
											<h6 class="transection__date-number text-white">
												{{ showDateTime($recentTransaction->created_at, 'd') }}
											</h6>
											<span class="transection__date-text">
												{{ __(strtoupper(showDateTime($recentTransaction->created_at, 'M'))) }}
											</span>
										</div>
										<div class="transection__content">
											<h6 class="transection__content-title">
												{{ __(ucwords(keyToTitle($recentTransaction->remark))) }}
											</h6>
											<p class="transection__content-desc">
												{{ __($recentTransaction->details) }}
											</p>
										</div>
									</div>
									@if ($recentTransaction->trx_type == '+')
										<span class="badge badge--success">
											@lang('Plus')
										</span>
									@else
										<span class="badge badge--danger">
											@lang('Minus')
										</span>
									@endif

								</div>
							@empty
								<div class="transection__item justify-content-center skeleton p-5">
									<div class="empty-thumb text-center">
										<img src="{{ asset('assets/images/extra_images/empty.png') }}" />
										<p class="fs-14">@lang('No transactions found')</p>
									</div>
								</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-3">
			<div class="dashboard-right">
				<div class="right-sidebar">
					<div class="right-sidebar__header skeleton mb-3">
						<div class="d-flex flex-between flex-wrap">
							<div>
								<h4 class="fs-18 mb-0">@lang('Wallet Overview')</h4>
								<p class="fs-12 mt-0">@lang('Available wallet balance including the converted total balance')</p>
							</div>
							<span class="toggle-dashboard-right dashboard--popup-close"><i class="las la-times"></i></span>
						</div>
					</div>
					<div class="skeleton mb-3 text-center">
						<h3 class="right-sidebar__number mb-0 pb-0">


							{{ $general->cur_sym }}
							@forelse ($wallets as $wallet)
								@if (@$wallet->currency->symbol == 'USD')
									{{ showAmount($wallet->balance) }}
								@endif
							@empty
							@endforelse
						</h3>
						<span class="fs-14 mt-0">@lang('Estimated Total Balance')</span>
					</div>
					<div class="right-sidebar__menu">
						<div class="wallet-wrapper">
							@forelse ($wallets as $wallet)
								@if (@$wallet->currency->symbol == 'USD')
									<div class="right-sidebar__item wallet-list skeleton flex-wrap">
										<div class="d-flex align-items-center">
											<span class="right-sidebar__item-icon">
												<img src="{{ @$wallet->currency->image_url }}">
											</span>
											<h6 class="right-sidebar__item-name">
												{{ strLimit(@$wallet->currency->name, 10) }}
												<span class="fs-11 d-block">
													{{ @$wallet->currency->symbol }}
												</span>
											</h6>
										</div>
										<h6 class="right-sidebar__item-number"> {{ showAmount($wallet->balance) }} </h6>
									</div>
								@endif
							@empty
							@endforelse
						</div>
						{{-- <button class="w-100 show-more-wallet right-sidebar__button skeleton mt-2" type="button">
							<span class="right-sidebar__button-icon">
								<i class="las la-chevron-circle-down"></i>@lang('Show More')
							</span>
						</button> --}}
					</div>
				</div>
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
