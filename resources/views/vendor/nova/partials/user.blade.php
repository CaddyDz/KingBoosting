<dropdown-trigger class="h-9 flex items-center">
	<span class="text-90">
		{{ $user->username }}
	</span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200">
	<ul class="list-reset">
		<li>
			<nova-dark-theme-toggle label="@lang('Dark Theme')"></nova-dark-theme-toggle>
		</li>
		<li>
			<a href="{{ route('nova.logout') }}" class="block no-underline text-90 hover:bg-30 p-3">
				@lang('Logout')
			</a>
		</li>
	</ul>
</dropdown-menu>
