@component('mail::message')
# Introduction

We charged you {{ $order->price }}.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
