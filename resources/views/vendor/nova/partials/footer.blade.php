<p class="mt-8 text-center text-xs text-80">
	<a href="{{ config('app.url') }}" class="text-primary dim no-underline">{{ config('app.name') }}</a>
	<span class="px-1">&middot;</span>
	&copy; {{ date('Y') }} Designed by <a href="https://sitando.com" class="text-primary dim no-underline">Sitando</a>
	<span class="px-1">&middot;</span>
	v{{ \Laravel\Nova\Nova::version() }}
</p>
