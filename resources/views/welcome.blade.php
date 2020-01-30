@extends('layouts.app')

@section('content')
<v-container fluid class="welcome">
    <v-row align="center" justify="center">
        <v-img src="/img/material2.jpg" gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
            max-width="400" max-height="150">
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
{{-- <v-container class="services-section" fluid>
    <services-circle></services-circle>
</v-container> --}}
{{-- <v-content>
</v-content> --}}
{{-- <features></features> --}}
@stop
