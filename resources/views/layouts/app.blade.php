<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Buy LOL ELO Boost &amp; Premium LOL Boosting | {{ studly_case(request()->getHost()) }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description"
        content="Buy high quality ELO Boosting and LoL Boost services. 100% Safety and Privacy. The best ELO &amp; League Boost experience. Available on all servers.">
    <meta name="keywords"
        content="elo boost, lol elo boost, lol boost, lol elo boosting, elo boost lol, cheap elo boosting, elo boosting">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="canonical" href="{{ config('app.url') }}">
    <link rel="alternate" hreflang="en-us" href="{{ config('app.url') }}">
    <link rel="alternate" hreflang="x-default" href="{{ config('app.url') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="root">
        <v-app>
            <notification-component></notification-component>
            <v-system-bar lights-out app>
                <social-component></social-component>
                <v-spacer></v-spacer>
                <top-right-links></top-right-links>
            </v-system-bar>
            <app-bar-component></app-bar-component>
            <v-content>
                @yield('content')
            </v-content>
        </v-app>
    </div>
    <script>
        window.auth_user = {!! json_encode($auth_user); !!};
    </script>
</body>

</html>
