@component('mail::message')
# @lang('Hello!') {{ config('site.owner') }} {{ "\u{1F603}" }}
## @lang('You have received a contact request on your site')

@lang('From'): {{ $details['name'] }}

@lang('Subject'): {{ $details['category'] }}

@lang('Message'): {{ $details['message'] }}

@component('mail::button', ['url' => 'mailto:' . $details['email'], 'color' => 'blue'])
	@lang('Reply')
@endcomponent

@lang('Thanks'),<br>
{{ config('app.name') }}
@endcomponent
