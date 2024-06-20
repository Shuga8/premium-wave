<div class="sidebar bg--dark">
	<button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
	<div class="sidebar__inner">

		<div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
			<ul class="sidebar__menu">
				<li class="sidebar-menu-item {{ menuActive('admin.dashboard') }}">
					<a class="nav-link" href="{{ route('admin.dashboard') }}">
						<i class="menu-icon las la-home"></i>
						<span class="menu-title">@lang('Dashboard')</span>
					</a>
				</li>
				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive(['admin.order*', 'admin.trade.history'], 3) }}" href="javascript:void(0)">
						<i class="menu-icon las la-coins"></i>
						<span class="menu-title">@lang('Manage Order')</span>
					</a>
					<div class="sidebar-submenu {{ menuActive(['admin.order*', 'admin.trade.history'], 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive(['admin.order.open']) }}">
								<a class="nav-link" href="{{ route('admin.order.open') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Open Order')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive(['admin.order.history']) }}">
								<a class="nav-link" href="{{ route('admin.order.history') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Order History')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive(['admin.trade.history']) }}">
								<a class="nav-link" href="{{ route('admin.trade.history') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Trade History')</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
			
			
			
				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive('admin.users*', 3) }}" href="javascript:void(0)">
						<i class="menu-icon las la-users"></i>
						<span class="menu-title">@lang('Manage Users')</span>

						@if (
							$bannedUsersCount > 0 ||
								$emailUnverifiedUsersCount > 0 ||
								$mobileUnverifiedUsersCount > 0 ||
								$kycUnverifiedUsersCount > 0 ||
								$kycPendingUsersCount > 0)
							<span class="menu-badge pill bg--danger ms-auto">
								<i class="fa fa-exclamation"></i>
							</span>
						@endif
					</a>
					<div class="sidebar-submenu {{ menuActive('admin.users*', 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive('admin.users.active') }}">
								<a class="nav-link" href="{{ route('admin.users.active') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Active Users')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.users.banned') }}">
								<a class="nav-link" href="{{ route('admin.users.banned') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Banned Users')</span>
									@if ($bannedUsersCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $bannedUsersCount }}</span>
									@endif
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.users.email.unverified') }}">
								<a class="nav-link" href="{{ route('admin.users.email.unverified') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Email Unverified')</span>

									@if ($emailUnverifiedUsersCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $emailUnverifiedUsersCount }}</span>
									@endif
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.users.mobile.unverified') }}">
								<a class="nav-link" href="{{ route('admin.users.mobile.unverified') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Mobile Unverified')</span>
									@if ($mobileUnverifiedUsersCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $mobileUnverifiedUsersCount }}</span>
									@endif
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.users.kyc.unverified') }}">
								<a class="nav-link" href="{{ route('admin.users.kyc.unverified') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('KYC Unverified')</span>
									@if ($kycUnverifiedUsersCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $kycUnverifiedUsersCount }}</span>
									@endif
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.users.kyc.pending') }}">
								<a class="nav-link" href="{{ route('admin.users.kyc.pending') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('KYC Pending')</span>
									@if ($kycPendingUsersCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $kycPendingUsersCount }}</span>
									@endif
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.users.all') }}">
								<a class="nav-link" href="{{ route('admin.users.all') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('All Users')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.users.trades') }}">
								<a class="nav-link" href="{{ route('admin.users.trades') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Binaries')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.users.notification.all') }}">
								<a class="nav-link" href="{{ route('admin.users.notification.all') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Notification to All')</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="sidebar-menu-item {{ menuActive('admin.referrals.index') }}">
					<a class="nav-link" href="{{ route('admin.referrals.index') }}">
						<i class="menu-icon las la-tree"></i>
						<span class="menu-title">@lang('Manage Referral')</span>
					</a>
				</li>
				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive('admin.gateway*', 3) }}" href="javascript:void(0)">
						<i class="menu-icon las la-credit-card"></i>
						<span class="menu-title">@lang('Payment Gateways')</span>
					</a>
					<div class="sidebar-submenu {{ menuActive('admin.gateway*', 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive('admin.gateway.automatic.*') }}">
								<a class="nav-link" href="{{ route('admin.gateway.automatic.index') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Automatic Gateways')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.gateway.manual.*') }}">
								<a class="nav-link" href="{{ route('admin.gateway.manual.index') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Manual Gateways')</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive('admin.deposit*', 3) }}" href="javascript:void(0)">
						<i class="menu-icon las la-file-invoice-dollar"></i>
						<span class="menu-title">@lang('Deposits')</span>
						@if (0 < $pendingDepositsCount)
							<span class="menu-badge pill bg--danger ms-auto">
								<i class="fa fa-exclamation"></i>
							</span>
						@endif
					</a>
					<div class="sidebar-submenu {{ menuActive('admin.deposit*', 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive('admin.deposit.pending') }}">
								<a class="nav-link" href="{{ route('admin.deposit.pending') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Pending Deposits')</span>
									@if ($pendingDepositsCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $pendingDepositsCount }}</span>
									@endif
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.deposit.approved') }}">
								<a class="nav-link" href="{{ route('admin.deposit.approved') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Approved Deposits')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.deposit.successful') }}">
								<a class="nav-link" href="{{ route('admin.deposit.successful') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Successful Deposits')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.deposit.rejected') }}">
								<a class="nav-link" href="{{ route('admin.deposit.rejected') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Rejected Deposits')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.deposit.initiated') }}">
								<a class="nav-link" href="{{ route('admin.deposit.initiated') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Initiated Deposits')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.deposit.list') }}">
								<a class="nav-link" href="{{ route('admin.deposit.list') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('All Deposits')</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive('admin.withdraw*', 3) }}" href="javascript:void(0)">
						<i class="menu-icon la la-bank"></i>
						<span class="menu-title">@lang('Withdrawals') </span>
						@if (0 < $pendingWithdrawCount)
							<span class="menu-badge pill bg--danger ms-auto">
								<i class="fa fa-exclamation"></i>
							</span>
						@endif
					</a>
					<div class="sidebar-submenu {{ menuActive('admin.withdraw*', 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive('admin.withdraw.method.*') }}">
								<a class="nav-link" href="{{ route('admin.withdraw.method.index') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Withdrawal Methods')</span>
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.withdraw.pending') }}">
								<a class="nav-link" href="{{ route('admin.withdraw.pending') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Pending Withdrawals')</span>

									@if ($pendingWithdrawCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $pendingWithdrawCount }}</span>
									@endif
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.withdraw.approved') }}">
								<a class="nav-link" href="{{ route('admin.withdraw.approved') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Approved Withdrawals')</span>
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.withdraw.rejected') }}">
								<a class="nav-link" href="{{ route('admin.withdraw.rejected') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Rejected Withdrawals')</span>
								</a>
							</li>

							<li class="sidebar-menu-item {{ menuActive('admin.withdraw.log') }}">
								<a class="nav-link" href="{{ route('admin.withdraw.log') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('All Withdrawals')</span>
								</a>
							</li>


						</ul>
					</div>
				</li>
				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive('admin.ticket*', 3) }}" href="javascript:void(0)">
						<i class="menu-icon la la-ticket"></i>
						<span class="menu-title">@lang('Support Ticket') </span>
						@if (0 < $pendingTicketCount)
							<span class="menu-badge pill bg--danger ms-auto">
								<i class="fa fa-exclamation"></i>
							</span>
						@endif
					</a>
					<div class="sidebar-submenu {{ menuActive('admin.ticket*', 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive('admin.ticket.pending') }}">
								<a class="nav-link" href="{{ route('admin.ticket.pending') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Pending Ticket')</span>
									@if ($pendingTicketCount)
										<span class="menu-badge pill bg--danger ms-auto">{{ $pendingTicketCount }}</span>
									@endif
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.ticket.closed') }}">
								<a class="nav-link" href="{{ route('admin.ticket.closed') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Closed Ticket')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.ticket.answered') }}">
								<a class="nav-link" href="{{ route('admin.ticket.answered') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Answered Ticket')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.ticket.index') }}">
								<a class="nav-link" href="{{ route('admin.ticket.index') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('All Ticket')</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
		

		
				<li class="sidebar-menu-item {{ menuActive('admin.wallet.setting') }}">
					<a class="nav-link" href="{{ route('admin.wallet.setting') }}">
						<i class="menu-icon las la-wallet"></i>
						<span class="menu-title">@lang('Wallet Setting')</span>
					</a>
				</li>
			
				<li class="sidebar-menu-item {{ menuActive('admin.currency.data.provider.index') }}">
					<a class="nav-link" href="{{ route('admin.currency.data.provider.index') }}">
						<i class="menu-icon las la-cog"></i>
						<span class="menu-title">@lang('Currency Data Provider')</span>
					</a>
				</li>
				

				<li class="sidebar-menu-item {{ menuActive('admin.seo') }}">
					<a class="nav-link" href="{{ route('admin.seo') }}">
						<i class="menu-icon las la-globe"></i>
						<span class="menu-title">@lang('SEO Manager')</span>
					</a>
				</li>

				<li class="sidebar-menu-item {{ menuActive('admin.kyc.setting') }}">
					<a class="nav-link" href="{{ route('admin.kyc.setting') }}">
						<i class="menu-icon las la-user-check"></i>
						<span class="menu-title">@lang('KYC Setting')</span>
					</a>
				</li>


				<li class="sidebar-menu-item sidebar-dropdown">
					<a class="{{ menuActive('admin.setting.notification*', 3) }}" href="javascript:void(0)">
						<i class="menu-icon las la-bell"></i>
						<span class="menu-title">@lang('Notification Setting')</span>
					</a>
					<div class="sidebar-submenu {{ menuActive('admin.setting.notification*', 2) }}">
						<ul>
							<li class="sidebar-menu-item {{ menuActive('admin.setting.notification.global') }}">
								<a class="nav-link" href="{{ route('admin.setting.notification.global') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Global Template')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.setting.notification.email') }}">
								<a class="nav-link" href="{{ route('admin.setting.notification.email') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Email Setting')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.setting.notification.sms') }}">
								<a class="nav-link" href="{{ route('admin.setting.notification.sms') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('SMS Setting')</span>
								</a>
							</li>
							<li class="sidebar-menu-item {{ menuActive('admin.setting.notification.templates') }}">
								<a class="nav-link" href="{{ route('admin.setting.notification.templates') }}">
									<i class="menu-icon las la-dot-circle"></i>
									<span class="menu-title">@lang('Notification Templates')</span>
								</a>
							</li>
						</ul>
					</div>
				</li>



			
			



				<li class="sidebar-menu-item {{ menuActive('admin.maintenance.mode') }}">
					<a class="nav-link" href="{{ route('admin.maintenance.mode') }}">
						<i class="menu-icon las la-robot"></i>
						<span class="menu-title">@lang('Maintenance Mode')</span>
					</a>
				</li>


			</ul>

		</div>
	</div>
</div>
<!-- sidebar end -->

@push('script')
	<script>
		if ($('li').hasClass('active')) {
			$('#sidebar__menuWrapper').animate({
				scrollTop: eval($(".active").offset().top - 320)
			}, 500);
		}
	</script>
@endpush
