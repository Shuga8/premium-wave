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
									<th>@lang('Amount')</th>
									<th>@lang('Currency')</th>
									<th>@lang('Crypto')</th>
									<th>@lang('Commodity')</th>
									<th>@lang('Stock')</th>
									<th>@lang('Action')</th>
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
											<small>
												<a class="btn btn--danger font-sm text-center" href="{{ route('admin.users.trades.delete', $binary->id) }}"><i
														class="las la-trash"></i></a>
											</small>
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
	</div>
@endsection


@push('breadcrumb-plugins')
	<x-search-username-form />
@endpush
