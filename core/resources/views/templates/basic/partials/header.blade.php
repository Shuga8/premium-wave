<header class="header" id="header">
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light">
			<a class="navbar-brand logo" href="https://premiumwave.ca">
				<img src="https://premiumwave.ca/wp-content/uploads/2024/06/cropped-cropped-PREMIUM-WAVES-3.png" style="mix-blend-mode:difference;">
			</a>
			<button class="navbar-toggler header-button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span id="hiddenNav"><i class="las la-bars"></i></span>
			</button>

			<div class="navbar-collapse collapse" id="navbarSupportedContent">
				<ul class="navbar-nav nav-menu align-items-lg-center me-auto flex-wrap">
					<li class="nav-item d-block d-lg-none">
						@if ($general->multi_language)
							@php
								$langDetails = $languages->where('code', config('app.locale'))->first();
							@endphp
							<div class="top-button d-flex justify-content-between align-items-center flex-wrap">
								<div class="custom--dropdown">
									<div class="custom--dropdown__selected dropdown-list__item">
										<div class="thumb">
											<img src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
										</div>
										<span class="text">{{ __(@$langDetails->name) }}</span>
									</div>
									<ul class="dropdown-list">
										@foreach ($languages as $language)
											<li class="dropdown-list__item change-lang" data-code="{{ @$language->code }}">
												<div class="thumb">
													<img src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
												</div>
												<span class="text">{{ __(@$language->name) }}</span>
											</li>
										@endforeach
									</ul>
								</div>
								<ul class="login-registration-list d-flex align-items-center flex-wrap">
									@guest
										<li class="login-registration-list__item">
											<a class="sign-in" href="{{ route('user.login') }}">@lang('Login')</a>
										</li>
										<li class="login-registration-list__item">
											<a class="btn btn--base btn--sm" href="{{ route('user.register') }}">@lang('Sign up') </a>
										</li>
									@else
										<li class="login-registration-list__item">
											<a class="btn btn--base btn--sm" href="{{ route('user.home') }}">@lang('Dashboard')</a>
										</li>
										<li class="login-registration-list__item">
											<a class="sign-in" href="{{ route('user.logout') }}">@lang('Logout')</a>
										</li>
									@endguest
								</ul>
							</div>
						@endif
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://premiumwave.ca/">Home</a>

					</li>
	<li class="nav-item">
						<a class="nav-link" href="https://premiumwave.ca/blog">Blog</a>

					</li>
				

					<li class="nav-item">
						<a class="nav-link" href="{{ route('wave') }}">Trading Room</a>
					
				
					<li class="nav-item">
						<a class="nav-link" href="https://premiumwave.ca/contact/">Contact Us </a>
					</li>
				</ul>
			</div>
			<ul class="header-right d-lg-block d-none">
				<li class="nav-item">
					<div class="top-button d-flex justify-content-between align-items-center flex-wrap">
						@if ($general->multi_language)
							<div class="custom--dropdown">
								<div class="custom--dropdown__selected dropdown-list__item">
									<div class="thumb">
										<img src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
									</div>
									<span class="text">{{ __(@$langDetails->name) }}</span>
								</div>
								<ul class="dropdown-list">
									@foreach ($languages as $language)
										<li class="dropdown-list__item change-lang" data-code="{{ @$language->code }}">
											<div class="thumb">
												<img src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
											</div>
											<span class="text">{{ __(@$language->name) }}</span>
										</li>
									@endforeach
								</ul>
							</div>
						@endif
						<ul class="login-registration-list d-flex align-items-center flex-wrap">
							@guest
								<li class="login-registration-list__item">
									<a class="sign-in" href="{{ route('user.login') }}">@lang('Login')</a>
								</li>
								<li class="login-registration-list__item">
									<a class="btn btn--base btn--sm" href="{{ route('user.register') }}">@lang('Sign up') </a>

								</li>
							@else
								<li class="login-registration-list__item">
									<a class="btn btn--base btn--sm" href="{{ route('user.home') }}">@lang('Dashboard')</a>
								</li>
								<li class="login-registration-list__item">
									<a class="sign-in" href="{{ route('user.logout') }}">@lang('Logout')</a>
								</li>
							@endguest
						</ul>
					</div>
				</li>
			</ul>
			@if (!request()->routeIs('trade'))
				<div class="theme-switch-wrapper">
					<label class="theme-switch" for="checkbox">
						<input class="d-none" id="checkbox" type="checkbox">
						<span class="slider">
							<i class="las la-sun"></i>
						</span>
					</label>
				</div>
			@endif
		</nav>
	</div>
</header>
