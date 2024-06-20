@php
	$footer = getContent('footer.content', true);
	$socialIcons = getContent('social_icon.element', orderById: true);
	$policyPages = getContent('policy_pages.element');
@endphp

<footer class="footer-area">

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
