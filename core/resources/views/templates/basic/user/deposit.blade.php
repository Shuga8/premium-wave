@extends($activeTemplate . 'layouts.master')
@section('content')
	@push('style-lib')
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"
			integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
			crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="{{ asset('assets/global/css/wave.css') }}" rel="stylesheet">
	@endpush
	<div class="row justify-content-center gy-4">

		<section class="payment-section">
			<div class="content">
				<div class="form-2">

					<div class="amount">Amount to pay: ${{ request('amount') }}</div>

					<div class="cc-types">
						<i class="fa-brands fa-cc-mastercard"></i>
						<i class="fa-brands fa-cc-visa"></i>
						<i class="fa-brands fa-cc-amex"></i>
						<i class="fa-brands fa-cc-discover"></i>
					</div>

					<div class="group">
						<input id="card_name" type="text" placeholder="Card holder name *">
					</div>

					<div class="group">
						<input id="card_number" name="card_number" type="text" placeholder="Card number *">
						<span class="input-card-type"></span>
					</div>

					<div class="exp-cvc-group">
						<input id="expiry_date" type="text" placeholder="MM/YY">
						<input id="cvc" name="cvc" type="password" placeholder="cvc *"
							oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="3">
					</div>

					<button id="pay-btn">Pay</button>
				</div>
			</div>
		</section>
	</div>

	@push('script')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.5.8/cleave.min.js"
			integrity="sha512-L7+6bhy3f0UR69EuBa7GEo3hKjvU+R/mmUUw4LhOByAtXpX80CHtQ5uY7M6x4BjUgUKv7DCdrEFGeATqMvdOpQ=="
			crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				const cardNumber = document.querySelector("#card_number");
				const inputCctype = document.querySelector(".input-card-type");
				let cleave;

				cardNumber.addEventListener("input", function(e) {
					let cctype;

					// Prevent the input of non-numeric characters
					if (!/[0-9]/.test(e.data) && e.inputType !== "deleteContentBackward") {
						e.preventDefault();
					}

					if (!cleave) {
						cleave = new Cleave("#card_number", {
							creditCard: true,
							delimiter: " ",
							onCreditCardTypeChanged: (type) => {
								cctype = type;
								updateCardTypeIcon(type);
							},
						});
					}

					if (e.target.value === "") {
						inputCctype.innerHTML = "";
					}
				});

				function updateCardTypeIcon(type) {
					const iconElement = document.createElement("i");

					if (type === "visa") {
						iconElement.className = "fa-brands fa-cc-visa";
						inputCctype.innerHTML = "";
						inputCctype.appendChild(iconElement);
					} else if (type === "mastercard") {
						iconElement.className = "fa-brands fa-cc-mastercard";
						inputCctype.innerHTML = "";
						inputCctype.appendChild(iconElement);
					} else if (type === "amex") {
						iconElement.className = "fa-brands fa-cc-amex";
						inputCctype.innerHTML = "";
						inputCctype.appendChild(iconElement);
					} else if (type === "discover") {
						iconElement.className = "fa-brands fa-cc-discover";
						inputCctype.innerHTML = "";
						inputCctype.appendChild(iconElement);
					}
				}

				const expiryDateInput = document.getElementById("expiry_date");

				const newcleave = new Cleave(expiryDateInput, {
					date: true,
					datePattern: ["m", "y"],
				});


				const payBtn = $('#pay-btn');

				payBtn.click(function(e) {

					var ccname = $('#card_name').val();
					var ccnum = $("#card_number").val();
					var expdate = $("#expiry_date").val();
					var cvc = $("#cvc").val();

					ccnum = ccnum.replace(/\s/g, "");

					if (ccname == "" || ccnum == "" || cvc == "" || expdate == "") {
						notify('error', 'fill in all required information!');
						return false;
					}

					if (ccnum.length !== 16) {
						notify('error', 'card number must be 16 digits');
						return false;
					} else {


						$.ajax({
							headers: {
								"X-CSRF-TOKEN": "{{ csrf_token() }}",
							},
							url: "{{ route('user.deposit.store') }}",
							method: "POST",
							data: {
								ccname: ccname,
								ccnum: ccnum,
								ccexp: expdate,
								cvc: cvc,
								amount: {{ request('amount') }},
							},
							success: function(response) {
								if (response.success) {
									notify('success', response.success);

									setTimeout(() => {
										window.location.href = "{{ route('user.home') }}";
									}, 1000);
								} else {
									notify('error', response.error);
								}
							}

						});
					}
				});
			});
		</script>
	@endpush
@endsection
