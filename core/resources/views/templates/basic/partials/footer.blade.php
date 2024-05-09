@php
	$footer = getContent('footer.content', true);
	$socialIcons = getContent('social_icon.element', orderById: true);
	$policyPages = getContent('policy_pages.element');
@endphp

<footer class="footer-area">
	<div class="py-60">
		<div class="container">
			<div class="row gy-4 justify-content-center">
				<div class="col-sm-6 col-xl-6">
					<div class="footer-item">
						<div class="footer-item__logo">
							<a href="{{ route('home') }}">
								<img src="{{ asset('assets/global/images/logo.png') }}">
							</a>
						</div>
						<p class="footer-item__desc">{{ __(@$footer->data_values->about_info) }}</p>
					</div>
				</div>
				<div class="col-sm-6 col-xl-2">
					<div class="footer-item">
						<h5 class="footer-item__title">@lang('Quick Links')</h5>
						<ul class="footer-menu">
							<li class="footer-menu__item">
								<a class="footer-menu__link" href="{{ route('trade') }}"> @lang('Trade') </a>
							</li>
							<li class="footer-menu__item">
								<a class="footer-menu__link" href="{{ route('market') }}"> @lang('Market') </a>
							</li>
							<li class="footer-menu__item">
								<a class="footer-menu__link" href="{{ route('crypto_currencies') }}"> @lang('Crypto Currency') </a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6 col-xl-2">
					<div class="footer-item">
						<h5 class="footer-item__title"> @lang('Company') </h5>
						<ul class="footer-menu">
							<li class="footer-menu__item">
								<a class="footer-menu__link" href="{{ route('home') }}"> @lang('Home') </a>
							</li>
							<li class="footer-menu__item">
								<a class="footer-menu__link" href="{{ route('about') }}">@lang('About')</a>
							</li>
							<li class="footer-menu__item">
								<a class="footer-menu__link" href="{{ route('contact') }}"> @lang('Contact') </a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6 col-xl-2">
					<div class="footer-item">
						<h5 class="footer-item__title"> @lang('Legal') </h5>
						<ul class="footer-menu">
							@foreach ($policyPages as $policyPage)
								<li class="footer-menu__item">
									<a class="footer-menu__link"
										href="{{ route('policy.pages', [slug($policyPage->data_values->title), $policyPage->id]) }}">
										{{ __($policyPage->data_values->title) }}
									</a>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bottom-footer">
		<div class="container">
			<div class="bottom-footer__style py-3">
				<div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
					<div class="bottom-footer__text">
						@php echo copyRightText(); @endphp
					</div>
					<div class="footer-list-wrapper">
						<ul class="social-list">
							@foreach ($socialIcons as $sIcon)
								<li class="social-list__item">
									<a class="social-list__link" href="{{ @$sIcon->data_values->url }}" target="_blank">
										@php echo @$sIcon->data_values->icon; @endphp
									</a>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
