@extends('layouts.app')

@section('content')
<v-container fluid class="welcome">
    <v-row align="center" justify="center">
        <v-img src="/img/header_logo.png" max-width="100%">
        </v-img>
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
<v-container class="services-section" fluid>
    <services-circle></services-circle>
</v-container>
<features></features>
<customer></customer>
<why-us></why-us>
<blogs></blogs>
@stop
