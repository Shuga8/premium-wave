<div class="sidebar-menu">
	<div class="sidebar-menu__inner">
		<span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times"></i></span>
		<div class="sidebar-logo">
			<a class="sidebar-logo__link" href="https://premiumwave.ca/">
				<img src="https://premiumwave.ca/wp-content/uploads/2024/06/cropped-cropped-PREMIUM-WAVES-3.png">
			</a>
		</div>
		<ul class="sidebar-menu-list">
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.home') }}" href="{{ route('user.home') }}">
					<span class="icon"><span class="icon-dashboard"></span></span>
					<span class="text">@lang('Dashboard')</span>
				</a>
				
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.deposit.new') }}" href="{{ route('user.deposit.new') }}">
					<span class="icon"><span class="icon-deposit"></span></span>
					<span class="text">@lang('Deposit')</span>
				</a>
			</li>
			<!--	<li class="sidebar-menu-list__item">-->
			<!--	<a class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}" href="https://premiumwave.ca/auth/deposit?amount=1000&currency=USD">-->
			<!--		<span class="icon"><span class="icon-security"></span></span>-->
			<!--		<span class="text">Deposit Funds</span>-->
			<!--	</a>-->
			<!--</li>-->
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.order.*') }}" href="https://premiumwave.ca/auth/wave">
					<span class="icon"><span class="icon-order"></span></span>
					<span class="text">Trade Now</span>
				</a>
			</li>
		
		

			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.wallet.*') }}"
					href="https://premiumwave.ca/auth/user/wallet/spot/USD">
					<span class="icon"><span class="icon-wallet"></span></span>
					<span class="text">@lang('Manage Wallet')</span>
				</a>
			</li>
		
			
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.referrals') }}" href="{{ route('user.referrals') }}">
					<span class="icon"><span class="icon-affiliation"></span></span>
					<span class="text">@lang('My Affiliation')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.transactions') }}" href="{{ route('user.transactions') }}">
					<span class="icon"><span class="icon-transaction"></span></span>
					<span class="text">@lang('Transaction Histoy')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('ticket.*') }}" href="{{ route('ticket.index') }}">
					<span class="icon"><span class="icon-support"></span></span>
					<span class="text">@lang('Get Support')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}" href="https://premiumwave.ca/privacy-policy/">
					<span class="icon"><span class="icon-security"></span></span>
					<span class="text">Policies</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link" href="{{ route('user.twofactor') }}">
					<span class="icon"><span class="icon-logout"></span></span>
					<span class="text">@lang('Logout')</span>
				</a>
			</li>
		</ul>
	</div>
</div>
