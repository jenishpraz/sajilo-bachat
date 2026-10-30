@extends('layouts.site')
@section('title', ui('nav.calendar'))

@section('content')
<x-page-hero :title="ui('nav.calendar')" :subtitle="ui('calendar.sub')" :crumbs="[[ui('nav.activities'), null]]" />
<section class="section"><div class="container-x">@include('site._calendar')</div></section>
@endsection
