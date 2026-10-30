@extends('layouts.site')
@section('title', ui('nav.saving'))
@section('description', ui('saving.sub'))

@section('content')
<x-page-hero :title="ui('nav.saving')" :subtitle="ui('saving.sub')" :crumbs="[[ui('nav.services'), null]]" />
<section class="section">
    <div class="container-x">
        @if ($plans->isEmpty())
            <x-empty-state icon="savings" :title="ui('saving.none')" :text="ui('saving.none_hint')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($plans as $p)<x-plan-card :item="$p" route="saving.show" kind="saving" />@endforeach
            </div>
            <p class="mt-8 flex gap-2 rounded-lg bg-amber-50 p-4 text-sm text-amber-900"><x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0" />{{ ui('common.rates_notice') }}</p>
        @endif
    </div>
</section>
@endsection
