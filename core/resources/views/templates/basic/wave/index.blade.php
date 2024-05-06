@extends($activeTemplate . 'layouts.frontend')
@section('content')
	@push('style-lib')
		<link href="{{ asset('assets/global/css/wave.css') }}" rel="stylesheet">
	@endpush
	<div class="wave-container">

		<div class="control-navigation">
			<div class="control-tab ct-1 text-white" data-nav-control-title="Favorites"
				data-nav-control-icon='<i class="la la-star"></i>'>

				<div class="icon">
					<i class="la la-star"></i>
				</div>
				<span>Favorites</span>
			</div>

			<div class="control-tab ct-2 text-white" data-nav-control-title="Currencies"
				data-nav-control-icon='<i class="la la-coins"></i>'>

				<div class="icon">
					<i class="la la-coins"></i>
				</div>
				<span>Currencies</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Stocks"
				data-nav-control-icon='<i class="la la-chart-bar"></i>'>

				<div class="icon">
					<i class="la la-chart-bar"></i>
				</div>
				<span>Stocks</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Commodities"
				data-nav-control-icon='<i class="la la-charging-station"></i>'>

				<div class="icon">
					<i class="la la-charging-station"></i>
				</div>
				<span>Commodities</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Cryptos"
				data-nav-control-icon='<i class="la la-bitcoin"></i>'>
				<div class="icon">
					<i class="la la-bitcoin"></i>
				</div>
				<span>Cryptos</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Indicies"
				data-nav-control-icon='<i class="la la-charging-station"></i>'>
				<div class="icon">
					<i class="la la-charging-station"></i>
				</div>
				<span>Indicies</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Cannabis"
				data-nav-control-icon='<i class="las la-leaf"></i>'>
				<div class="icon">
					<i class="las la-leaf"></i>
				</div>
				<span>Cannabis</span>
			</div>


		</div>

		<div class="control-after-display">
			<div class="close-display-btn">&times;</div>

			<div class="after-display-content">
				<div class="title">
				</div>

				<div class="asset-list">
					<div class="head">
						<div>Symbol</div>
						<div>Change</div>
					</div>

					<div class="asset-content">
						{{-- <div class="asset-pair-item">

							<div class="asset-pair-info">
								<div class="img-pair"></div>
								<div class="img-pair"></div>
								<div class="pair-name"> EURUSD</div>
							</div>

							<div class="asset-pair-rate">
								<div class="item-status">closed</div>
								<div class="item-rate">2333.23</div>
							</div>

							<div class="asset-fav">
								<i class="las la-star"></i>
							</div>
						</div> --}}
					</div>


					</table>
				</div>
			</div>
		</div>

		<div class="trading-chart-display">
			<div id="tradingview-container">

			</div>
		</div>

		<div class="trading-form-display">
			<div class="current-symbol-info">
				<div class="SymbolImg"></div>
				<div class="usdSymbolImg"></div>
				<p class="pair-name">EURUSD</p>
			</div>

			<form class="trade-form" name="trade-form">

				<div class="group">
					<label for="lot">Volume in Lot</label>
					<select id="lot" name="lot">
						<option value="0.1">0.1</option>
						<option value="0.2">0.2</option>
						<option value="0.3">0.3</option>
						<option value="0.4">0.4</option>
						<option value="0.5">0.5</option>
						<option value="0.6">0.6</option>
						<option value="0.7">0.7</option>
						<option value="0.8">0.8</option>
						<option value="0.9">0.9</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">9</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
				</div>

				<div class="group">
					<p>Pips Value: <b><span class="pips-value">$ 1</span></b></p>
					<p>Required Margin: <b><span class="required-margin">$ 120</span></b></p>
				</div>

				<div class="button-group">
					<button class="set-sell" type="submit">
						<i class="la la-arrow-down"></i>Sell
						<p>0.2234</p>
					</button>

					<button class="set-buy" type="submit">
						<i class="la la-arrow-up"></i>Buy
						<p>1.2234</p>
					</button>
				</div>

				<div class="trade-button-group">
					<button class="trade-btn">@lang('Trade')</button>
				</div>

				<div class="advanced-accordion">

					<div class="accordion-btn"><i class="las la-caret-down"></i> Advanced</div>

					<div class="accordion-content">
						<div class="stop-loss-container">
							<p>Stop Loss</p>
							<div class="stop-loss-content">
								<p>Potential Loss</p>
								<div class="potentials-value potential-loss-value text-center text-white">1.2001</div>
								<div class="potential-action-buttons potential-loss-action-buttons">
									<button>+</button>
									<button>-</button>
								</div>
							</div>
						</div>

						<div class="take-profit-container">
							<p>Take Profit</p>
							<div class="take-profit-content">
								<p>Potential Profit</p>
								<div class="potentials-value potential-profit-value text-center text-white">1.2334</div>
								<div class="potential-action-buttons potential-profit-action-buttons">
									<button>+</button>
									<button>-</button>
								</div>
							</div>
						</div>

						<div class="open-trade-container">
							<p>Open Trade When Rate Is</p>
							<div class="open-trade-content">
								<p> Open Time</p>
								<div class="potentials-value potential-open-value text-center text-white">1.2001</div>
								<div class="potential-action-buttons potential-open-action-buttons">
									<button>+</button>
									<button>-</button>
								</div>
							</div>
						</div>
					</div>


				</div>

			</form>
		</div>

	</div>

	@push('script')
		<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
		<script>
			"use strict";
			new TradingView.widget({
				"width": "100%",
				"height": 525,
				"symbol": "EURUSD",
				"interval": "1",
				"timezone": "Etc/UTC",
				"theme": "dark",
				"backgroundColor": "rgba(9, 22, 25, 1)",
				"style": "1",
				"locale": "en",
				"enable_publishing": false,
				"hide_side_toolbar": true,
				"hide_top_toolbar": true,
				"details": false,
				"container_id": "tradingview-container"
			});

			let cryptos = {!! json_encode($cryptos) !!};
			let stocks = {!! json_encode($stocks) !!};
			let forexs = {!! json_encode($forexs) !!};
			let commodities = {!! json_encode($commodites) !!}
		</script>
		<script src="{{ asset('assets/global/js/wave.js') }}"></script>
	@endpush
@endsection
