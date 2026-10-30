@extends('layouts.site')
@php $title = $type === 'auction' ? ui('nav.auction_notices') : ui('nav.general_notices'); $route = $type === 'auction' ? 'notices.auction' : 'notices.index'; @endphp
@section('title', $title)

@section('content')
<x-page-hero :title="$title" :crumbs="[[ui('nav.notices'), null]]" />
<section class="section">
    <div class="container-x max-w-4xl">
        <form method="GET" action="{{ route($route) }}" class="mb-8 grid gap-3 sm:grid-cols-[1fr_14rem_auto]">
            <label class="sr-only" for="q">{{ ui('common.search') }}</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="{{ ui('common.search') }}…" class="field">
            <label class="sr-only" for="category">{{ ui('common.category') }}</label>
            <select id="category" name="category" class="field">
                <option value="">{{ ui('common.all_categories') }}</option>
                @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name() }}</option>@endforeach
            </select>
            <button class="btn btn-primary"><x-icon name="search" class="h-4 w-4" />{{ ui('common.search') }}</button>
        </form>

        @if ($notices->isEmpty())
            <x-empty-state icon="bell" :title="ui('notices.none')" :text="request()->hasAny(['q', 'category']) ? ui('common.try_different') : null" />
        @else
            <div class="space-y-3">@foreach ($notices as $n)<x-notice-row :notice="$n" />@endforeach</div>
            {{ $notices->links() }}
        @endif
    </div>
</section>
@endsection
