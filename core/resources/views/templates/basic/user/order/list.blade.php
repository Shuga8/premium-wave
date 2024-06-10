@extends($activeTemplate . 'layouts.master')
@section('content')
	<div class="row justify-content-end gy-3">
		<div class="col-12">
			<div class="dashboard-header-menu justify-content-between">
				<div class="div">
					<a class="dashboard-header-menu__link {{ menuActive('user.order.open') }}"
						href="{{ route('user.order.open') }}">@lang('Open')</a>
					<a class="dashboard-header-menu__link {{ menuActive('user.order.completed') }}"
						href="{{ route('user.order.completed') }}">@lang('Completed')</a>
					<a class="dashboard-header-menu__link {{ menuActive('user.order.canceled') }}"
						href="{{ route('user.order.canceled') }}">@lang('Pending')</a>
					<a class="dashboard-header-menu__link {{ menuActive('user.order.history') }}"
						href="{{ route('user.order.history') }}">@lang('History')</a>
				</div>
				{{-- <form class="d-flex gap-2 flex-wrap">
                <div class="flex-fill">
                    <div class="input-group">
                        <select name="order_type" class="form-control form--control submit-form-on-change form-select">
                            <option value="" selected disabled>@lang('Order Type')</option>
                            <option value="">@lang('All')</option>
                            <option value="{{ Status::ORDER_TYPE_LIMIT }}" @selected(request()->order_type == Status::ORDER_TYPE_LIMIT)>
                                @lang('Limit')
                            </option>
                            <option value="{{ Status::ORDER_TYPE_MARKET }}" @selected(request()->order_type ==Status::ORDER_TYPE_MARKET)>
                                @lang('Market')
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex-fill">
                    <select class="form-control form--control submit-form-on-change form-select" name="order_side">
                        <option value="" selected disabled>@lang('Order Side')</option>
                        <option value="">@lang('All')</option>
                        <option value="{{ Status::BUY_SIDE_ORDER }}" @selected(request()->order_side == Status::BUY_SIDE_ORDER)>
                            @lang('Buy')
                        </option>
                        <option value="{{ Status::SELL_SIDE_ORDER }}" @selected(request()->order_side == Status::SELL_SIDE_ORDER)>
                            @lang('Sell')
                        </option>
                    </select>
                </div>
                <div class="flex-fill">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form--control"
                            value="{{ request()->search }}" placeholder="@lang('Pair,coin,currency...')">
                        <button type="submit" class="input-group-text bg--primary text-white">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </div>
            </form> --}}
			</div>
		</div>
		<div class="col-lg-12">
			<div class="table-wrapper">
				<table class="table--responsive--lg table">

					<thead>
						<tr>

							<th>
								ORDER ID
								<br>
								CREATED TIME
							</th>
							<th>

								STOP LOSS
								<br>
								USED FUNDS
							</th>

							<th>


								TAKE PROFIT
								<br>
								PROFIT/LOSS
							</th>

							<th>
								CLOSING TIME
								<br>
								OPEN PRICE

							</th>

							<th>
								SYMBOL
								<br>
								CLOSED PRICE

							</th>
						</tr>
					</thead>
					<tbody>
						@forelse($orders as $order)
							@php
								$symbol;
								if ($order->isCrypto) {
								    $symbol = $order->crypto;
								} elseif ($order->isStock) {
								    $symbol = $order->stock;
								} elseif ($order->isCommodity) {
								    $symbol = $order->commodity;
								} elseif ($order->isForex) {
								    $symbol = $order->currency;
								}
								$profitLoss =
								    $order->price_is >= $order->take_profit && $order->price_is != null
								        ? abs($order->open_amount - $order->amount)
								        : abs($order->open_amount - $order->amount);
							@endphp
							<tr>
								<td>
									{{ $order->order_id }}
									<br>
									{{ $order->created_at }}
								</td>

								<td>
									{{ $order->stop_loss }}
									<br>
									{{ $order->open_amount }}
								</td>

								<td>
									{{ $order->take_profit }}
									<br>
									{{ $profitLoss }}
								</td>

								<td style="font-size: 10px;">
									{{ $order->updated_at }}
									<br>
									{{ $order->open_price }}
								</td>

								<td>
									{{ $symbol }}
									<br>
									{{ $order->price_is == null ?? $order->price_was }}
								</td>
							</tr>
						@empty
							@php echo userTableEmptyMessage('order') @endphp
						@endforelse
					</tbody>
				</table>
			</div>
			@if ($orders->hasPages())
				{{ paginateLinks($orders) }}
			@endif
		</div>
	</div>
	</div>
	<x-confirmation-modal isCustom="true" />
@endsection

@push('topContent')
	<h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush

@push('script')
	<script>
		"use strict";
		(function($) {

			$('table').on('click', '.order-update-form-remove', function(e) {
				$(`.order--rate`).removeClass('d-none');
				$(`.order--amount`).removeClass('d-none');
				$(this).closest('.order-update-form').remove();
			})

			let editColumn = null;

			$('table').on('click', '.amount-rate-update', function(e) {

				$('.order-update-form').remove();
				$(`.order--rate`).removeClass('d-none');
				$(`.order--amount`).removeClass('d-none');

				editColumn = $(this).closest('td');

				let order = $(this).attr('data-order');
				order = JSON.parse(order);
				let updateField = $(this).data('update-filed');
				let action = "{{ route('user.order.update', ':id') }}";

				let html = `<form class="order-update-form" action="${action.replace(':id', order.id)}">
                    <input type="hidden" name="update_filed" value="${updateField}">
                    <div class="input-group">
                        <span class="input-group-text">
                            ${updateField == 'amount' ? "@lang('Amount')" : "@lang('Rate')"}
                        </span>
                        <input type="text" class="form--control form-control" name="${updateField}"  value="${updateField == 'amount' ? getAmount(order.amount) : getAmount(order.rate)}">
                        <button type="submit" class="input-group-text">
                            <i class="fas fa-check text--success"></i>
                        </button>
                        <button type="button" class="input-group-text order-update-form-remove">
                            <i class="fas fa-times text--danger"></i>
                        </button>
                    </div>
                </form>`;
				editColumn.find('.order--amount-rate-wrapper').append(html);
			});

			$('table').on('submit', '.order-update-form', function(e) {
				e.preventDefault();

				let formData = new FormData($(this)[0]);
				let action = $(this).attr('action');
				let token = "{{ csrf_token() }}";
				let $this = $(this);

				$.ajax({
					headers: {
						'X-CSRF-TOKEN': token
					},
					url: action,
					method: "POST",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$($this).find('button[type=submit]').html(
							`<i class="fa fa-spinner fa-spin text--success"></i>`);
						$($this).find('button[type=button]').addClass('d-none');
						$($this).attr(`disabled`, true);
					},
					complete: function() {
						$($this).find('button[type=submit]').html(
							`<i class="fa fa-check text--success"></i>`);
						$($this).find('button[type=button]').removeClass('d-none');
						$($this).attr(`disabled`, false);
					},
					success: function(resp) {
						if (resp.success) {
							editColumn.find(`.order--rate`).removeClass('d-none');
							editColumn.find(`.order--amount`).removeClass('d-none');
							editColumn.find('.order-update-form').remove();

							let newOrder = editColumn.find('.amount-rate-update').data('order');
							if (resp.data.order_amount) {
								editColumn.find(`.order--amount-value`).text(getAmount(resp.data
									.order_amount));
								newOrder.amount = getAmount(resp.data.order_amount);
							}
							if (resp.data.order_rate) {
								editColumn.find(`.order--rate-value`).text(getAmount(resp.data
									.order_rate));
								newOrder.rate = getAmount(resp.data.order_rate);
							}
							editColumn.find('.amount-rate-update').attr('data-order', JSON.stringify(
								newOrder))
							notify('success', resp.message);
						} else {
							notify('error', resp.message);
						}
					},
				});
			});

		})(jQuery);
	</script>
@endpush
