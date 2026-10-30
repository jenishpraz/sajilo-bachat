@extends('layouts.site')
@section('title', ui('nav.calculators'))
@section('description', ui('calc.sub'))

@section('content')
<x-page-hero :title="ui('nav.calculators')" :subtitle="ui('calc.sub')" :crumbs="[[ui('nav.services'), null]]" />
<section class="section">
    <div class="container-x">
        @include('site._calculator')
    </div>
</section>
@endsection
