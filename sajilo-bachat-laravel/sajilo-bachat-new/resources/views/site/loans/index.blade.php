@extends('layouts.site')
@section('title', ui('nav.loans'))
@section('description', ui('loans.sub'))

@section('content')
<x-page-hero :title="ui('nav.loans')" :subtitle="ui('loans.sub')" :crumbs="[[ui('nav.services'), null]]" />
<section class="section">
    <div class="container-x">
        @if ($loans->isEmpty())
            <x-empty-state icon="loan" :title="ui('loans.none')" :text="ui('loans.none_hint')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($loans as $l)<x-plan-card :item="$l" route="loans.show" kind="loan" />@endforeach
            </div>
            <p class="mt-8 flex gap-2 rounded-lg bg-amber-50 p-4 text-sm text-amber-900"><x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0" />{{ ui('common.rates_notice') }}</p>
        @endif
    </div>
</section>
@endsection
