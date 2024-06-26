@extends($activeTemplate . 'layouts.frontend')
@section('content')
	@push('style-lib')
		<link href="{{ asset('assets/global/css/wave.css') }}" rel="stylesheet">
	@endpush
	<style>
		.weekend-closed-trade-info {
			display: none;
			padding: 7px;
			text-align: center;
			color: red;
			font-weight: 800;
			background: pink;
		}
	</style>

	<div class="preloader">
		<div class="loader-p"></div>
	</div>
	<div class="wave-container">

		<div class="control-navigation">
			{{-- <div class="control-tab ct-1 text-white" data-nav-control-title="Favorites"
				data-nav-control-icon='<i class="la la-star"></i>'>

				<div class="icon">
					<i class="la la-star"></i>
				</div>
				<span>Favorites</span>
			</div> --}}

			<div class="control-tab ct-2 text-white" data-nav-control-title="Currencies"
				data-nav-control-icon='<i class="la la-coins"></i>'>

				<div class="icon">
					<i class="la la-coins"></i>
				</div>
				<span>@lang('Currencies')</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Cryptos"
				data-nav-control-icon='<i class="la la-bitcoin"></i>'>
				<div class="icon">
					<i class="la la-bitcoin"></i>
				</div>
				<span>@lang('Cryptos')</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Stocks"
				data-nav-control-icon='<i class="la la-chart-bar"></i>'>

				<div class="icon">
					<i class="la la-chart-bar"></i>
				</div>
				<span>@lang('Stocks')</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Commodities"
				data-nav-control-icon='<i class="la la-charging-station"></i>'>

				<div class="icon">
					<i class="la la-charging-station"></i>
				</div>
				<span>@lang('Commodities')</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Indicies"
				data-nav-control-icon='<i class="la la-charging-station"></i>'>
				<div class="icon">
					<i class="la la-charging-station"></i>
				</div>
				<span>@lang('Indicies')</span>
			</div>

			<div class="control-tab text-white" data-nav-control-title="Blog" data-nav-control-icon='<i class="las la-leaf"></i>'>
				<div class="icon">
					<i class="las la-file"></i>
				</div>
				<span>@lang('Blog')</span>
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

			<div class="weekend-closed-trade-info">
				Market Closed
			</div>

			<div class="current-symbol-info">
				<div class="SymbolImg"><img src="{{ asset('assets/global/icons/AUD.png') }}" alt="" /></div>
				<div class="usdSymbolImg"><img src="{{ asset('assets/global/icons/USD.png') }}" alt=""></div>
				<p class="pair-name">AUDUSD</p>
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
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
				</div>

				<div class="group">
					<p>Pips Value: <b><span class="pips-value">$ 1</span></b></p>
					<p>Required Margin: <b><span class="required-margin">$ 53.87</span></b></p>
				</div>

				<div class="button-group">
					<button class="set-sell" type="submit">
						<i class="la la-arrow-down"></i>Sell
						<p class="set-sell-value"></p>
					</button>

					<button class="set-buy" type="submit">
						<i class="la la-arrow-up"></i>Buy
						<p class="set-buy-value"></p>
					</button>
				</div>

				<div class="trade-button-group">
					<button class="trade-btn">@lang('Trade')</button>
				</div>

				<div class="advanced-accordion">

					<div class="accordion-btn"><i class="las la-caret-down"></i> Advanced</div>

					<div class="accordion-content">

						<div class="stop-loss-container">
							<div class="access">
								<input id="stop_loss_check" name="stop_loss_check" type="checkbox">
								<p>Stop Loss</p>
							</div>
							<div class="stop-loss-content">
								<p>Potential Loss</p>

								<div class="potentials-value potential-loss-value text-center text-white"></div>
								<div class="potential-action-buttons potential-loss-action-buttons">
									<button class="potential-button potential-stop-loss-button increment">+</button>
									<button class="potential-button potential-stop-loss-button decrement">-</button>
								</div>
							</div>
						</div>

						<div class="take-profit-container">
							<div class="access">
								<input id="take_profit_check" name="take_profit_check" type="checkbox">
								<p>Take Profit</p>
							</div>
							<div class="take-profit-content">
								<p>Potential Profit</p>
								<div class="potentials-value potential-profit-value text-center text-white"></div>
								<div class="potential-action-buttons potential-profit-action-buttons">
									<button class="potential-button potential-take-profit-button increment">+</button>
									<button class="potential-button potential-take-profit-button decrement">-</button>
								</div>
							</div>
						</div>

						<div class="open-trade-container">
							<div class="access">
								<input id="open_rate_check" name="open_rate_check" type="checkbox">
								<p>Open Trade When Rate Is</p>
							</div>
							<div class="open-trade-content">
								<p> Open Rate</p>
								<div class="potentials-value potential-open-rate-value text-center text-white"></div>
								<div class="potential-action-buttons potential-open-action-buttons">
									<button class="potential-button potential-open-rate-button increment">+</button>
									<button class="potential-button potential-open-rate-button decrement">-</button>
								</div>
							</div>
						</div>
					</div>

				</div>

			</form>

			<div class="row">

				<!-- TradingView Widget BEGIN -->
				<div class="tradingview-widget-container">
					<div class="tradingview-widget-container__widget"></div>
					<div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow"
							target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
					<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js"
						async>
						{
							"interval": "1m",
							"width": "100%",
							"isTransparent": true,
							"height": "250",
							"symbol": "AUDUSD",
							"showIntervalTabs": true,
							"displayMode": "single",
							"locale": "en",
							"colorTheme": "dark"
						}
					</script>
				</div>
				<!-- TradingView Widget END -->
			</div>

			<div class="row">

				<div class="bot-col col-sm-6 gap-4">
					<div class="bot-trading bot-trading-1">
					</div>

					<p class="text-center" style="font-size: 10px">BOT LOTSIZE: 0.3</p>
				</div>

				<div class="bot-col col-sm-6">
					<div class="bot-trading bot-trading-2">
					</div>
					<p class="text-center" style="font-size: 10px">BOT LOTSIZE: 0.5</p>
				</div>

			</div>
			{{-- <button class="">Auto Trade</button> --}}
		</div>

	</div>

	<div class="wave-logs">

		<div class="logs">
			<ul class="nav nav-tabs" id="myTab" role="tablist">

				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="open-trade-tab" data-bs-toggle="tab" data-bs-target="#open-trade"
						type="button" role="tab" aria-controls="open-trade" aria-selected="true">Open Trades</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="pending-trade-tab" data-bs-toggle="tab" data-bs-target="#pending-trade"
						type="button" role="tab" aria-controls="pending-trade" aria-selected="false">Pending Trades</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
						role="tab" aria-controls="history" aria-selected="false">Trade History</button>
				</li>

			</ul>

			<div class="tab-content" id="myTabContent">

				<div class="tab-pane fade show active" id="open-trade" role="tabpanel" aria-labelledby="open-trade-tab">
					<x-flexible-view :view="$activeTemplate . 'wave.open-trades'" />
				</div>

				<div class="tab-pane fade" id="pending-trade" role="tabpanel" aria-labelledby="pending-trade-tab">
					<x-flexible-view :view="$activeTemplate . 'wave.pending-trades'" />
				</div>

				<div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
					<x-flexible-view :view="$activeTemplate . 'wave.trade-history'" />
				</div>

			</div>
		</div>
	</div>

	@push('script')
		<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>

		</div>
		<!-- TradingView Widget END -->
		<script>
			"use strict";
			new TradingView.widget({
				"width": "100%",
				"height": 580,
				"symbol": "FX:AUDUSD",
				"interval": "1",
				"timezone": "Etc/UTC",
				"theme": "dark",
				"backgroundColor": "rgba(9, 22, 25, 1)",
				"style": "1",
				"locale": "en",
				"enable_publishing": false,
				"hide_side_toolbar": false,
				"hide_top_toolbar": false,
				"details": false,
				"container_id": "tradingview-container"
			});

			let cryptos = {!! json_encode($cryptos) !!};
			let stocks = {!! json_encode($stocks) !!};
			let forexs = {!! json_encode($forexs) !!};
			let commodities = {!! json_encode($commodites) !!}
			let token = "{{ csrf_token() }}";
		</script>
		<script src="{{ asset('assets/global/js/wave.js') }}"></script>
		<script src="{{ asset('assets/global/js/logs.js') }}" type="module"></script>
	@endpush
@endsection
