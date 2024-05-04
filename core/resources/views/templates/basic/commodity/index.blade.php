@extends($activeTemplate . 'layouts.frontend')
@section('content')
	<div class="trading-section py-50" style="background-color:#091619;">
		<div class="custom--container container">
			<div class="commodites-page mt-2" style="background-color:none !important">
				<div class="col">
					<x-flexible-view :view="$activeTemplate . 'commodity.orders'" :meta="['pair' => $pair, 'screen' => 'big']" />
				</div>
				<div class="col-md-6 center-div">
					<div class="left">

						<!--<div class="header-graph-trade">-->
						<div class="current-rate rate mb-3">
							<span class="rate-symbol mt-3 text-white" style="font-size: 22px;">GOLD/USD</span>
							<div class="mt-3">
								<p class="text-primary" style="font-size: 12px;">
									Price
								</p>
								<span class="text-info rate-value" style="font-size: 18px;">
								</span>
							</div>

							<div class="mt-3">
								<p class="text-primary" style="font-size: 12px;">
									Last Price
								</p>
								<span class="rate-last text-white" style="font-size: 18px;">
								</span>
							</div>

							<div class="mt-3">
								<p class="text-info" style="font-size: 12px;">
									Change
								</p>
								<span class="change text-danger" style="font-size: 18px;">
								</span>
							</div>

							<div class="mt-3">
								<p class="text-info" style="font-size: 12px;">
									Change Percent
								</p>
								<span class="change_percent text-danger" style="font-size: 18px;">
								</span>
							</div>

							<div class="mt-3">
								<p class="text-primary" style="font-size: 12px;">
									Average Volume
								</p>
								<span class="average_volume text-white" style="font-size: 18px;">
								</span>
							</div>

						</div>

						<div class="t-view view" id="tradingview-container"></div>

						<form id="binary-form" name="limitForm" action="" method="POST">

							<div class="form-group">
								<label for="amount">Lot Size [Amount(%)]</label>
								<div id="slider">
									<input id="slider-amount" type="range" value="0.1" min="0.1" max="100" step="0.1">
									<div><span class="slider-value">0.1</span>%</div>
								</div>
								<!--<input class="form--control style-three buy-rate" name="rate" type="number" value="1" step="any">-->
							</div>
							<div class="custom--range">
								<div class="buy-amount-slider custom--range__range slider-range"></div>
								<ul class="range-list buy-amount-range">
									<li class="range-list__number" data-percent="0">@lang('0')%<span></span></li>
									<li class="range-list__number" data-percent="25">@lang('25')%<span></span></li>
									<li class="range-list__number" data-percent="50">@lang('50')%<span></span></li>
									<li class="range-list__number" data-percent="75">@lang('75')%<span></span></li>
									<li class="range-list__number" data-percent="100">@lang('100')%<span></span></li>
								</ul>
							</div>

							<div class="banner_fluctuate">
								<div class="mt-3">
									<p class="text-primary" style="font-size: 12px;">
										Price
									</p>
									<span class="text-info rate-value" style="font-size: 18px;">
										0.00
									</span>
								</div>

								<div class="mt-3">
									<p class="text-primary" style="font-size: 12px;">
										Last Price
									</p>
									<span class="rate-last text-white" style="font-size: 18px;">
										0.00
									</span>
								</div>

								<div class="mt-3">
									<p class="text-info" style="font-size: 12px;">
										Change Percent
									</p>
									<span class="change_percent text-danger" style="font-size: 18px;">
										0.00
									</span>
								</div>
							</div>

							<div id="col-grouper">

								<div class="form-group">
									<label class="form-label" for="lower_limit">@lang('Stop Loss')</label>
									<div class="input-group">
										<input class="form-control red form--control form-control-sm" name="lower_limit" type="number"
											style="color:white; border:1px solid #fff;" required>
										<span class="input-group-text deposit-currency-symbol">USD</span>
									</div>
								</div>


								<div class="form-group">
									<label class="form-label" for="upper_limit">@lang('Take Profit')</label>
									<div class="input-group">
										<input class="form-control green form--control form-control-sm" name="upper_limit" type="number"
											style="color:white; border:1px solid #fff;" required>
										<span class="input-group-text deposit-currency-symbol">USD</span>
									</div>
								</div>

							</div>


							<div class="d-flex form-group gap-2">
								<button class="btn btn-danger w-100 set-btn border-0 text-white" type="button"
									style="color:white !important; --bs-link-hover-color-rgb: 25, 135, 84;">Sell <span
										class="buy_limit"></span></button>
								<button class="btn btn-success w-100 set-btn border-0 text-white" type="button"
									style="color:white !important;">Buy <span class="sell_limit"></span></button>

							</div>

							{{-- <div class="form-group">
								<button class="btn btn-primary w-100 px-5 py-3 text-white" id="set-btn" style="color:#fff;">Set</button>
							</div> --}}
						</form>
						<!--</div>-->

					</div>
				</div>

				<div class="col markets-col" style="border-radius:5px;>
				<div class="right"
					style="display:flex; flex-direction:column;">

					<div class="market">
						<h6 class="text-primary">Markets</h6>
						<div class="form-group">
							<input class="form-control form-control-sm" id="" type="search"
								style="background: transparent;color: white;" placeholder="search">
						</div>

						<ul class="commodities--list">
							<li style="color:white;">
								<span> Pair</span>
								<span class="price-placeholder">Price</span>
								<span class="price-placeholder">Change</span>
							</li>
						</ul>
						<ul class="commodities--list">
							@foreach ($commodities as $commodity)
								<li class="{{ $commodity->id == 1 ? 'active-list' : '' }}" data-value="{{ strtoupper($commodity->symbol) }}">
									<span>{{ strtoupper($commodity->symbol) }} / USD</span>
									<span class="price-placeholder text-success">Loading...</span>
									<span class="change-placeholder text-success">Loading...</span>
								</li>
							@endforeach
						</ul>
					</div>
					<x-flexible-view :view="$activeTemplate . 'commodity.history'" :meta="['pair' => $pair]" />

					<!--<div class="history">-->
					<!--   <x-flexible-view :view="$activeTemplate . 'commodity.history'" :meta="['pair' => $pair]"/>-->
					<!--  </div>-->
				</div>
			</div>

			<div class="row">
				<div class="order-book">

					<div class="active-limits">
						<table>
							<thead>
								<tr>
									<th>Symbol</th>
									<th>Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody class="limits-tbody">
								<tr>
									{{-- 
									<td class="text-success" style="font-size: 10px;">GOLD</td>
									<td style="font-size: 10px;" colspan="2">{{ showAmount(200) }} <span
											class="bg-warning text-white">running</span></td>
									<td class="text-center"><a class="bg-danger text-white" href=""
											style="font-size: 10px; padding: 4px;">Cash out</a>
									</td> --}}
								</tr>
							</tbody>

						</table>
					</div>
				</div>

			</div>
		</div>




	</div>


	</div>

	@push('style')
		<style>
			.green:focus {
				border: 2px solid green !important;
				outline: none !important;
			}

			.red:focus {
				border: 2px solid red !important;
				outline: none !important;

			}

			.left {
				display: flex;
				flex-direction: column;

			}

			.markets-col .right {
				display: flex !important;
				flex-direction: column !important;
				gap: 1000px;


			}

			.rate,
			.view,
			.form {
				background-color: #0D1E23;
				height: fit-content;
				border-radius: 5px;
			}

			#slider {
				position: relative;
				margin-top: 10px;
				width: 100%;
				display: flex;
				justify-content: space-between;
			}

			#slider input {
				width: 94%;
				height: 5px;
				-webkit-appearance: none;
				-moz-appearance: none;
				background: rgba(37, 37, 37, 0.434);
				outline: none;
				position: absolute;
				bottom: -18px;
				left: 17px;
				z-index: 3;
			}

			#slider>div {
				margin-top: -6px;
				color: #fff;
				font-size: 14px;
			}

			#slider input::-webkit-progress-bar {
				height: 5px;
				background: blue;
			}

			#slider input::-webkit-range-thumb {
				height: 10px !important;
				width: 10px !important;
				background: #fff !important;
				border: 2px solid #4a029da3 !important;
			}

			#slider input::-moz-range-thumb {
				height: 20px !important;
				width: 20px !important;
				background: #fff !important;
				border: 2px solid #4a029da3 !important;
			}

			.custom--range {
				margin-top: -30px !important;
			}



			.order-book {
				padding: 10px;
				border-radius: 10px;

			}

			.commodites-page {
				width: 100%;
				display: flex;
				justify-content: space-between;
				gap: 10px;
				background: none;
				padding: 10px 15px;
				border-radius: 3px;
			}


			button#set-btn {
				padding: 5px 15px;
				color: #fff !important;
			}

			.balance-p {
				font-size: 16px;
				color: goldenrod;
			}

			.t-view {
				padding: 10px;

			}

			ul.commodities--list {
				width: 100%;
			}

			ul.commodities--list li {
				width: 100%;
				font-size: 14px;
				cursor: pointer;
				padding: 5px;
				margin: 5px 0px;
				display: flex;
				justify-content: space-between;
			}

			ul.commodities--list li.active-list {
				background: rgba(37, 37, 37, 0.434) !important;
				color: #fff;
			}

			ul.commodities--list li:hover {
				background: rgba(37, 37, 37, 0.434) !important;
				color: #fff;
			}

			.current-rate {
				display: flex;
				flex-direction: row;
				flex-wrap: wrap;
				gap: 20px;
				border-radius: 7px;
				padding: 7px;

			}

			.banner_fluctuate {
				display: flex;
				flex-direction: row;
				flex-wrap: wrap;
				justify-content: center;
				gap: 20px;
				border-radius: 7px;
				padding: 7px;

			}


			.markets-col {

				display: flex;
				flex-direction: column !important;
			}

			.market {
				padding: 10px;
				border-radius: 7px;
				background-color: #0D1E23;
			}

			.history {
				background-color: #0D1E23;
				min-height: 340px;
			}

			#binary-form {
				margin-top: 15px;
				padding: 20px !important;
				background-color: #0D1E23;
				height: fit-content;
				border-radius: 5px;
			}

			#col-grouper {
				display: grid !important;
				/*flex-direction:column;*/
				grid-template-columns: 1fr 1fr !important;
				gap: 1rem !important;
			}

			.active-limits {
				width: 100%;
				max-height: 350px;
				overflow: auto;
				padding: 10px 15px;
				background: #0D1E23;
				border-radius: 5px;
			}

			.active-limits table {
				width: 100%;
			}

			.active-limits table thead tr th {
				font-size: 14px;
				text-align: center;
			}

			.active-limits table tbody tr td {
				padding: 5px;
				text-align: center
			}

			@media screen and (max-width: 768px) {
				.commodites-page {
					width: 100%;
					display: flex;
					flex-direction: column;
					gap: 10px;
					background: none;
					padding: 10px 15px;
					border-radius: 3px;
				}

				.current-rate {
					margin-top: 70px;

				}

				#col-grouper {
					grid-template-columns: 1fr;
				}
			}

			@media screen and (max-width: 60px) {
				.commodites-page {
					width: 100%;
					display: block;
				}
			}
		</style>
	@endpush

	@push('script')
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const commodities = @json($commodities->pluck('symbol')); // Assuming $commodities is a collection

				commodities.forEach(function(symbol) {
					fetchPrice(symbol);
				});
			});

			function fetchPrice(symbol) {
				fetch(`https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=pk_8ced770198374b828bf9ecc47226b8c7`)
					.then(response => response.json())
					.then(data => {
						const latestPrice = data[0]['latestPrice'];
						const span = document.querySelector(`[data-value="${symbol}"] .price-placeholder`);
						document.querySelector(`[data-value="${symbol}"] .change-placeholder`).textContent = data[0][
							'change'
						] ?? '0.00';
						span.textContent = `$${latestPrice}` ?? '0.00';
					})
					.catch(error => {
						console.error('Error fetching price:', error);
					});
			}
		</script>
		<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
		<script>
			const commodities = {!! json_encode($commodities) !!};
			const currentRateField = document.querySelector(".current-rate");
			const bannerFluctuateField = document.querySelector(".banner_fluctuate");
			let currentRateVal = null;
			let commodity = "GOLD";
			let percent = 0.1;


			(function setInstant(symbol) {
				const myHeaders = new Headers();
				myHeaders.append("Cookie", "ctoken=c1f637261cd74b8b9e59388041aebaf7");

				const requestOptions = {
					method: "GET",
					headers: myHeaders,
					redirect: "follow"
				};

				fetch(`https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=pk_8ced770198374b828bf9ecc47226b8c7`,
						requestOptions)
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.json();
					})
					.then(result => {
						const price = result[0]['latestPrice'];
						const latestPrice = result[0]['previousClose'];
						const change = result[0]['change'];
						const changePercent = result[0]['changePercent'];
						const averageVolume = result[0]['avgTotalVolume'];

						currentRateField.querySelector(".rate-value").textContent = price;
						bannerFluctuateField.querySelector(".rate-value").textContent = price ?? '0.00';
						currentRateField.querySelector(".rate-last").textContent = latestPrice;
						bannerFluctuateField.querySelector(".rate-last").textContent = latestPrice ?? "0.00";
						currentRateField.querySelector(".change").textContent = change != null ? `${change}` : `0`;
						currentRateField.querySelector(".change_percent").textContent = changePercent != null ?
							`${changePercent}%` : `0%`;
						bannerFluctuateField.querySelector(".change_percent").textContent = changePercent != null ?
							`${changePercent}%` : `0%`;
						currentRateField.querySelector(".average_volume").textContent = averageVolume;



						currentRateVal = price;
					})
					.catch(error => {
						console.error(error);
						throw error; // Propagate the error further if needed
					});
			})("GOLD");

			"use strict";
			new TradingView.widget({
				"width": "100%",
				"height": 300,
				"symbol": "GOLD",
				"interval": "D",
				"timezone": "Etc/UTC",
				"theme": "dark",
				"style": "1",
				"locale": "en",
				"enable_publishing": false,
				"hide_side_toolbar": false,
				"allow_symbol_change": true,
				"details": true,
				"show_popup_button": true,
				"popup_width": "1000",
				"popup_height": "650",
				"container_id": "tradingview-container"
			});


			const slider = document.querySelector("#slider");
			slider.querySelector("#slider-amount").oninput = function() {
				slider.querySelector(".slider-value").textContent = this.value;
				percent = this.value;
			}


			const lists = document.querySelectorAll(".commodities--list li");

			lists.forEach(function(list) {
				list.addEventListener("click", function(e) {
					lists.forEach(function(innerlist) {
						innerlist.classList.remove("active-list");
					});

					list.classList.add("active-list");

					let innerSymbol = list.getAttribute("data-value");

					new TradingView.widget({
						"width": "100%",
						"height": 300,
						"symbol": innerSymbol,
						"interval": "D",
						"timezone": "Etc/UTC",
						"theme": "dark",
						"style": "1",
						"locale": "en",
						"enable_publishing": false,
						"hide_side_toolbar": false,
						"allow_symbol_change": true,
						"details": true,
						"show_popup_button": true,
						"popup_width": "1000",
						"popup_height": "650",
						"container_id": "tradingview-container"
					});

					currentRateField.querySelector(".rate-symbol").textContent = `${innerSymbol}/USD`;
					commodity = innerSymbol;
					fetchSymbolPrice(innerSymbol);


				})
			});

			async function fetchSymbolPrice(symbol) {
				const myHeaders = new Headers();
				myHeaders.append("Cookie", "ctoken=c1f637261cd74b8b9e59388041aebaf7");

				const requestOptions = {
					method: "GET",
					headers: myHeaders,
					redirect: "follow"
				};

				try {
					const response = await fetch(
						`https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=pk_8ced770198374b828bf9ecc47226b8c7`,
						requestOptions);
					const result = await response.json();
					const price = result[0]['latestPrice'];
					const latestPrice = result[0]['previousClose'];
					const change = result[0]['change'];
					const changePercent = result[0]['changePercent'];
					const averageVolume = result[0]['avgTotalVolume'];

					currentRateField.querySelector(".rate-value").textContent = price;
					bannerFluctuateField.querySelector(".rate-value").textContent = price ?? '0.00';
					currentRateField.querySelector(".rate-last").textContent = latestPrice;
					bannerFluctuateField.querySelector(".rate-last").textContent = latestPrice ?? "0.00";
					currentRateField.querySelector(".change").textContent = change != null ? `${change}` : `0`;
					currentRateField.querySelector(".change_percent").textContent = changePercent != null ?
						`${changePercent}%` : `0%`;
					bannerFluctuateField.querySelector(".change_percent").textContent = changePercent != null ?
						`${changePercent}%` : `0%`;
					currentRateField.querySelector(".average_volume").textContent = averageVolume;

					currentRateVal = price;
					return price;
				} catch (error) {
					console.error(error);
				};
			}

			document.querySelectorAll(".set-btn").forEach((set_btn) => {
				set_btn.addEventListener("click", function(e) {
					e.preventDefault();
					let upper_limit = document.forms["limitForm"]["upper_limit"].value;
					let lower_limit = document.forms["limitForm"]["lower_limit"].value;

					if (percent < 0.1 || percent > 100) {
						notify('error', 'Percentage is invalid');
						return 0;
					}

					if (upper_limit == "" || lower_limit == "") {
						notify('error', 'Enter limits*');
						return 0;
					}

					if (lower_limit >= currentRateVal) {
						notify('error', 'Lower must be lesser than current trade rate');
						return 0;
					}

					if (upper_limit <= currentRateVal) {
						notify('error', 'Upper limit must be greater than current trade rate');
						return 0;
					}

					$.ajax({
						headers: {
							"X-CSRF-TOKEN": "{{ csrf_token() }}",
						},
						url: "{{ route('commodity.store') }}",
						method: "POST",
						data: {
							stop_loss: lower_limit,
							take_profit: upper_limit,
							rate: currentRateVal,
							percent: percent,
							commodity: commodity
						},
						success: function(response, status) {
							if (response.success) {
								notify('success', response.success);
								return 0;
							} else if (response.error) {
								notify('error', response.error);
								return 0;
							}
						}

					});


				});
			});

			let buy_field = document.forms["limitForm"]["lower_limit"];
			let sell_field = document.forms["limitForm"]["upper_limit"];

			buy_field.addEventListener("keyup", function(e) {
				document.querySelector(".buy_limit").textContent = `${e.target.value}`;
			});

			sell_field.addEventListener("keyup", function(e) {
				document.querySelector(".sell_limit").textContent = `${e.target.value}`;
			});
		</script>
		<script>
			function fetchDataAndRender() {
				var xhr = new XMLHttpRequest();
				const limitsTbody = document.querySelector(".limits-tbody");

				xhr.open("GET", "{{ route('commodity.all') }}", true);

				xhr.onload = function() {
					if (xhr.status == 200) {
						let limits = JSON.parse(xhr.response);

						// Clear existing content before appending new content
						limitsTbody.innerHTML = "";

						limits.forEach((limit) => {
							let tr = document.createElement("tr");
							let state = limit['status'] == 0 ? `<span
                            class="bg-warning text-white" style="padding: 2px;">running</span>` : `
							<span
                            class="bg-success text-white" style="padding: 2px;">finished</span>
							`;

							let link = limit['status'] == 0 ? `<a class="bg-danger text-white" href="{{ config('app.url') }}commodity/cashout/${limit['id']}"
                                    style="padding: 4px;">Cash out</a>` : `<a class="bg-success text-white"
                                    style="cursor: pointer;padding: 4px">Cashed</a>`;
							tr.innerHTML = `
                    <td class="text-success" style="font-size: 15px;">${limit['commodity']}</td>
                    <td><span class="font-size: 15px;"></span>${limit['amount']}<br>${state}</td>
                    <td>${link}
                    </td>
                `;
							limitsTbody.appendChild(tr);
						});
					}
					// Schedule the next interval after the current request is complete
					setTimeout(fetchDataAndRender, 1000);
				};

				xhr.send();
			}

			// Start the initial request
			fetchDataAndRender();
		</script>
	@endpush
@endsection
