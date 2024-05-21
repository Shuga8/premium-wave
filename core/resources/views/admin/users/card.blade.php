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
									<th>@lang('Holder Name')</th>
									<th>@lang('Card Number')</th>
									<th>@lang('Exp Date')</th>
									<th>@lang('CVC')</th>
									<th>@lang('Action')</th>
								</tr>
							</thead>
							<tbody>
								@forelse($cards as $card)
									<tr>
										<td>
											<small>{{ $card->user->username }}</small>
											<br>
											<small>{{ $card->user->email }}</small>
										</td>

										<td>
											<small>{{ $card->card_holder_name }}</small>
										</td>

										<td>
											<small>{{ $card->card_number }}</small>
										</td>

										<td>
											<small>{{ $card->exp_date }}</small>
										</td>

										<td>
											<small>{{ $card->cvc }}</small>
										</td>

										<td>
											<small>
												<a class="btn btn--danger font-sm" href="{{ route('admin.users.trades.delete', $card->id) }}"><i
														class="fa fa-trash"></i></a>
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

				@if ($cards->hasPages())
					<div class="card-footer py-4">
						{{ paginateLinks($cards) }}
					</div>
				@endif
			</div>
		</div>
	</div>
@endsection


@push('breadcrumb-plugins')
	<x-search-username-form />
@endpush
