@extends($activeTemplate . 'layouts.master')
@section('content')
	@push('style-lib')
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"
			integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
			crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="{{ asset('assets/global/css/wave.css') }}" rel="stylesheet">
	@endpush
	<div class="row justify-content-center gy-4">

		<form class="col-md-5" action="{{ route('user.deposit.index') }}" method="GET">
			<div class="right-sidebar__header skeleton mb-3">
				<h4 class="fs-18 mb-0">@lang('Deposit Money')</h4>
				<p class="fs-12 mt-0">@lang('Make deposits in a few steps')</p>
			</div>
			<div class="input-group skeleton mb-3">
				<input class="form-control form-control-lg text-white" name="amount" type="text" aria-label="Amount"
					aria-describedby="basic-addon2" style="background: transparent;border: 1px solid #333;outline:none;"
					placeholder="amount ***">
				<span class="input-group-text" id="basic-addon2">USD</span>
			</div>

			<button class="skeleton btn btn-success text-white">Submit</button>
		</form>

	</div>
@endsection
