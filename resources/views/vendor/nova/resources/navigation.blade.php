@if (count(\Laravel\Nova\Nova::resourcesForNavigation(request())))
@foreach($navigation as $group => $resources)
@if (count($groups) > 1)
<h4 class="ml-8 mb-4 text-xs text-white-50% uppercase tracking-wide">{{ $group }}</h4>
@endif

<ul class="list-reset mb-8">
	@foreach($resources as $resource)
	<li class="leading-tight mb-4 ml-8 text-sm">
		<router-link :to="{
                        name: 'index',
                        params: {
                            resourceName: '{{ $resource::uriKey() }}'
                        }
                    }" class="text-white text-justify no-underline dim">
			{{ $resource::label() }}
		</router-link>
	</li>
	@endforeach
</ul>
@endforeach
@endif
