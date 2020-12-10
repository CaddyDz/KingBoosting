@component('mail::message')
# Your account has been created

Login to your account using the credentials below

Email: {{ $user->email }} <br>
Password: {{ $password }}

@component('mail::button', ['url' => url(config('nova.path'))])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
