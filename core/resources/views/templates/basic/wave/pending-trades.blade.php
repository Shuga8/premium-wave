<div class="table-overflow table-responsive--sm table-responsive">
	<table class="pending-trades-table">
		<thead>
			<tr>
				<th>
					ORDER ID
				</th>
				<th>
					OPEN PRICE
				</th>

				<th>
					OPEN RATE
				</th>

				<th>
					CREATED TIME
				</th>

				<th>
					CURRENT PRICE
				</th>

				<th>
					DIRECTION
				</th>

				<th>
					USED FUNDS
				</th>

				<th class="loss">
					STOP LOSS
				</th>

				<th>
					SYMBOL
				</th>

				<th>
					WALLET
				</th>

				<th class="profit">
					TAKE PROFIT
				</th>

				<th colspan="2">
					Action
				</th>
			</tr>
		</thead>

		<tbody>

			{{-- <tr>
				<td>
					2239908202
					<br>
					0.5564
				</td>

				<td>
					22:12:30 11/11/2024
					<br>
					0.6674
					<br>
					$100
				</td>

				<td>
					AED
					<br>
					0.3345
				</td>

				<td>
					USD
					<br>
					0.6999
				</td>

				<td>
					<a class="bg-danger px-4 py-2 text-white" href=""><i class="las la-trash"></i></a>
				</td>

			</tr> --}}

		</tbody>
	</table>
</div>

<!-- Pending Modal -->
<div class="modal fade" id="pendingTrade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="pendingTradeLabel">Modal title</h5>
				<button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="" method="GET">
					@csrf
					<div class="form-group">
						<label>@lang('Type')</label>
						<input class="form-control" name="type" type="text" step="any" disabled>
					</div>

					<div class="form-group">
						<label>@lang('Symbol')</label>
						<input class="form-control" name="symbol" type="text" step="any" disabled>
					</div>

					<div class="form-group">
						<label>@lang('Direction')</label>
						<input class="form-control" name="trade_type" type="text" step="any" disabled>
					</div>

					<div class="form-group">
						<label>@lang('Stop Loss')</label>
						<input class="form-control" name="stop_loss" type="number" step="any" required>
					</div>
					<div class="form-group">
						<label>@lang('Take Profit')</label>
						<input class="form-control" name="take_profit" type="number" step="any" required>
					</div>

					<div class="form-group open_at_field" hidden>
						<label>@lang('Open Trade When Rate Is')</label>
						<input class="form-control" name="open_at" type="number" step="any">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
				<button class="btn btn-primary submitEditedBtn" type="submit" onclick="">Save changes</button>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
	integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
