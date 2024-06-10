@extends('admin.layouts.app')

@section('panel')
	<div class="row justify-content-center">

		<div class="col-md-12">
			<div class="card b-radius--10">
				<div class="card-body p-0">
					<div class="table-responsive--sm table-responsive">
						<table class="table--light style--two table">
							<thead>
								<tr>
									<th>@lang('User')</th>
									<th>@lang('Stop Loss')</th>
									<th>@lang('Take Profit')</th>
									<th>@lang('Open Amount')</th>
									<th>@lang('Amount')</th>
									<th>@lang('Currency')</th>
									<th>@lang('Crypto')</th>
									<th>@lang('Commodity')</th>
									<th>@lang('Stock')</th>
									<th>@lang('Open Rate')</th>
									<th class="text-right">@lang('Action')</th>
								</tr>
							</thead>
							<tbody>
								@forelse($binaries as $binary)
									<tr>
										<td>
											<small>{{ $binary->user->username }}</small>
											<br>
											<small>{{ $binary->user->email }}</small>
										</td>

										<td>
											<small>{{ $binary->stop_loss }}</small>
										</td>

										<td>
											<small>{{ $binary->take_profit }}</small>
										</td>

										<td>
											<small>{{ $binary->open_amount }}</small>
										</td>

										<td>
											<small>{{ $binary->amount }}</small>
										</td>

										<td>
											<small>{{ $binary->currency }}</small>
										</td>

										<td>
											<small>{{ $binary->crypto }}</small>
										</td>


										<td>
											<small>{{ $binary->commodity }}</small>
										</td>

										<td>
											<small>{{ $binary->stock }}</small>
										</td>

										<td>
											<small>{{ $binary->open_at }}</small>
										</td>

										<td>
											<button class="btn btn-sm btn-outline--primary editBtn" data-binary='{!! json_encode($binary, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) !!}' type="button"><i
													class="la la-pencil"></i>@lang('Edit')</button>
											&nbsp;
											<small>
												<a class="btn btn-sm btn-outline--danger ms-1"
													href="{{ route('admin.users.trades.delete', $binary->id) }}"><i
														class="las la-trash"></i>@lang('Delete')</a>
											</small>
											{{-- <br>
											<br>
											<small>
												<a class="btn btn-sm btn-outline--primary editBtn"
													href="{{ route('admin.users.trades.rig', ['win', $binary->id]) }}">Rig Win</a>
											</small> --}}
										</td>
									</tr>
								@empty
									<tr>
										<td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>

				@if ($binaries->hasPages())
					<div class="card-footer py-4">
						{{ paginateLinks($binaries) }}
					</div>
				@endif
			</div>
		</div>

		<div class="modal fade" id="binaryModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">@lang('Edit Binary Trade')</h5>
						<button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
							<i class="las la-times"></i>
						</button>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="modal-body">
							<div class="form-group">
								<label>@lang('Type')</label>
								<input class="form-control" name="type" type="text" step="any" disabled>
							</div>

							<div class="form-group">
								<label>@lang('Symbol')</label>
								<input class="form-control" name="symbol" type="text" step="any" disabled>
							</div>

							<div class="form-group">
								<label>@lang('Open amount')</label>
								<input class="form-control" name="open_amount" type="number" step="any" required>
							</div>

							<div class="form-group">
								<label>@lang('Stop Loss')</label>
								<input class="form-control" name="stop_loss" type="number" step="any" required>
							</div>
							<div class="form-group">
								<label>@lang('Take Profit')</label>
								<input class="form-control" name="take_profit" type="number" step="any" required>
							</div>

							<div class="form-group">
								<label>@lang('Current Amount')</label>
								<input class="form-control" name="amount" type="number" step="any" required>
							</div>


							<div class="form-group open_at_field" hidden>
								<label>@lang('Open Trade When Rate Is')</label>
								<input class="form-control" name="open_at" type="number" step="any">
							</div>

							<button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection


@push('breadcrumb-plugins')
	<x-search-username-form />
@endpush

@push('script')
	<script>
		"use strict";
		(function($) {

			let modal = $('#binaryModal');

			$('.editBtn').on('click', function(e) {


				let action = `{{ route('admin.users.trades.edit', ':id') }}`;
				let data = JSON.parse(this.getAttribute("data-binary"));

				if (data.isCrypto) {
					modal.find("input[name=type]").val("Crypto Currency")
					modal.find("input[name=symbol]").val(data.crypto)
				} else if (data.isForex) {
					modal.find("input[name=type]").val("Foreign Currency")
					modal.find("input[name=symbol]").val(data.currency)
				} else if (data.isStock) {
					modal.find("input[name=type]").val("Stock Ticker")
					modal.find("input[name=symbol]").val(data.stock)
				} else if (data.isCommodity) {
					modal.find("input[name=type]").val("Commodity Ticker")
					modal.find("input[name=symbol]").val(data.commodity)
				}

				modal.find('form').prop('action', action.replace(':id', data.id))
				modal.find("input[name=stop_loss]").val(data.stop_loss)
				modal.find("input[name=take_profit]").val(data.take_profit)
				modal.find("input[name=amount]").val(data.amount)
				modal.find("input[name=open_amount]").val(data.open_amount)
				if (data.open_at_is_set) {
					document.querySelector(".open_at_field").removeAttribute("hidden");
					modal.find("input[name=open_at]").val(data.open_at)
				} else {
					document.querySelector(".open_at_field").setAttribute("hidden", true);
					modal.find("input[name=open_at]").val(data.open_at)
				}


				$(modal).modal('show');
			});
		})(jQuery);
	</script>
@endpush
