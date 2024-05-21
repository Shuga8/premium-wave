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
									<th>@lang('Amount')</th>
									<th>@lang('Status')</th>
									<th>@lang('Action')</th>
								</tr>
							</thead>
							<tbody>
								@forelse($deposits as $deposit)
									<tr>
										<td>
											<small>{{ $deposit->user->username }}</small>
											<br>
											<small>{{ $deposit->user->email }}</small>
										</td>

										<td>
											<small>{{ $deposit->amount }}</small>
										</td>

										<td>
											<small>{{ $deposit->status }}</small>
										</td>

										<td>
											<small>
												<a class="btn btn--danger font-sm text-center" href="{{ route('admin.users.trades.delete', $deposit->id) }}"><i
														class="las la-trash"></i></a>
											</small>
											{{-- <br>
											<br>
											<small>
												<a class="btn btn--success font-sm text-center"
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

				@if ($deposits->hasPages())
					<div class="card-footer py-4">
						{{ paginateLinks($deposits) }}
					</div>
				@endif
			</div>
		</div>
	</div>
@endsection


@push('breadcrumb-plugins')
	<x-search-username-form />
@endpush
