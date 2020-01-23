<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Buy LOL ELO Boost &amp; Premium LOL Boosting | {{ studly_case(request()->getHost()) }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="description"
        content="Buy high quality ELO Boosting and LoL Boost services. 100% Safety and Privacy. The best ELO &amp; League Boost experience. Available on all servers.">
    <meta name="keywords"
        content="elo boost, lol elo boost, lol boost, lol elo boosting, elo boost lol, cheap elo boosting, elo boosting">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="canonical" href="{{ config('app.url') }}">
    <link rel="alternate" hreflang="en-us" href="{{ config('app.url') }}">
    {{-- <link rel="alternate" hreflang="en-gb" href="https://www.kingboosting.co.uk/">
    <link rel="alternate" hreflang="de-de" href="https://www.kingboosting.de/">
    <link rel="alternate" hreflang="de-ch" href="https://www.kingboosting.ch/">
    <link rel="alternate" hreflang="de-at" href="https://www.kingboosting.at/">
    <link rel="alternate" hreflang="fr-fr" href="https://www.kingboosting.fr/">
    <link rel="alternate" hreflang="nl-nl" href="https://www.kingboosting.nl/">
    <link rel="alternate" hreflang="it-it" href="https://www.elokingboosting.it/">
    <link rel="alternate" hreflang="es-es" href="https://www.elokingboosting.es/">
    <link rel="alternate" hreflang="se-se" href="https://www.kingboosting.se/">
    <link rel="alternate" hreflang="dk-da" href="https://www.kingboosting.dk/">
    <link rel="alternate" hreflang="no-no" href="https://www.kingboosting.no/"> --}}
    <link rel="alternate" hreflang="x-default" href="{{ config('app.url') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>
    <div id="root">
        <v-app>
            <v-system-bar lights-out app>
                <social-component></social-component>
                <v-spacer></v-spacer>
                <top-right-links></top-right-links>
            </v-system-bar>
            <app-bar-component></app-bar-component>
            <v-content>
                <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                        <v-img src="https://cdn.vuetifyjs.com/images/parallax/material2.jpg"
                            gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)" max-width="400"
                            max-height="150"></v-img>
                    </v-row>
                    <slogan-component></slogan-component>
                    <v-row align="center" justify="center">
                        <v-btn class="ma-2" outlined color="white">Elo Boosting</v-btn>
                        <v-btn class="ma-2" outlined color="white">Unranked Smurf</v-btn>
                        <v-btn class="ma-2" outlined color="white">League Ranked Accounts</v-btn>
                        <v-btn class="ma-2" outlined color="white">Coaching</v-btn>
                    </v-row>
                    <v-row style="background: rgba(0, 0, 0, 0.5);">
                        <video-section></video-section>
                    </v-row>
                </v-container>
                <services-circle></services-circle>
            </v-content>
        </v-app>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>

</html>
