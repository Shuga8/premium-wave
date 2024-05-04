<div class="sidebar-menu">
	<div class="sidebar-menu__inner">
		<span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times"></i></span>
		<div class="sidebar-logo">
			<a class="sidebar-logo__link" href="{{ route('user.home') }}">
				<img src="{{ siteLogo() }}">
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
				<a class="sidebar-menu-list__link {{ menuActive('user.order.*') }}" href="{{ route('user.order.open') }}">
					<span class="icon"><span class="icon-order"></span></span>
					<span class="text">@lang('Manage Order')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.trade.history') }}" href="{{ route('user.trade.history') }}">
					<span class="icon"><span class="icon-trade"></span></span>
					<span class="text">@lang('Trade History')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }}" href="{{ route('user.p2p.dashboard') }}">
					<span class="icon"><span class="icon-trade"></span></span>
					<span class="text">@lang('P2P Center')</span>
				</a>
			</li>

			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.wallet.*') }}"
					href="{{ route('user.wallet.list', 'spot') }}">
					<span class="icon"><span class="icon-wallet"></span></span>
					<span class="text">@lang('Manage Wallet')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.deposit.*') }}" href="{{ route('user.deposit.history') }}">
					<span class="icon"><span class="icon-deposit"></span></span>
					<span class="text">@lang('Deposit History')</span>
				</a>
			</li>
			<li class="sidebar-menu-list__item">
				<a class="sidebar-menu-list__link {{ menuActive('user.withdraw.history') }}"
					href="{{ route('user.withdraw.history') }}">
					<span class="icon"><span class="icon-withdraw"></span></span>
					<span class="text">@lang('Withdraw History')</span>
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
				<a class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}" href="{{ route('user.twofactor') }}">
					<span class="icon"><span class="icon-security"></span></span>
					<span class="text">@lang('Security')</span>
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
