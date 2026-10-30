@extends('layouts.site')
@section('title', ui('nav.events'))

@section('content')
<x-page-hero :title="ui('nav.events')" :crumbs="[[ui('nav.activities'), null]]" />
<section class="section">
    <div class="container-x">
        <div class="mb-8 flex flex-wrap items-center gap-2">
            @foreach ([null => 'common.all', 'upcoming' => 'events.upcoming', 'past' => 'events.past'] as $key => $label)
                <a href="{{ route('events.index', array_filter(['when' => $key, 'category' => request('category')])) }}" @class(['badge px-4 py-1.5 text-sm', '!bg-brand-600 !text-white' => (string) $when === (string) $key, 'badge-gray hover:bg-brand-100' => (string) $when !== (string) $key])>{{ ui($label) }}</a>
            @endforeach
            @if ($categories->isNotEmpty())
                <span class="mx-2 hidden h-5 w-px bg-slate-300 sm:block"></span>
                @foreach ($categories as $c)
                    <a href="{{ route('events.index', array_filter(['when' => $when, 'category' => request('category') == $c->id ? null : $c->id])) }}" @class(['badge px-4 py-1.5 text-sm', '!bg-brand-600 !text-white' => request('category') == $c->id, 'badge-gray hover:bg-brand-100' => request('category') != $c->id])>{{ $c->name() }}</a>
                @endforeach
            @endif
        </div>

        @if ($events->isEmpty())
            <x-empty-state icon="calendar" :title="ui('events.none')" :text="ui('events.none_hint')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($events as $e)
                    <a href="{{ route('events.show', $e->slug) }}" class="group card card-hover overflow-hidden">
                        <x-pic :src="public_url($e->image)" :alt="$e->tr('title')" icon="calendar" ratio="aspect-[16/10]" />
                        <div class="p-5">
                            <p class="text-xs font-bold text-brand-700">{{ fdate($e->event_date) }}@if ($e->category) · {{ $e->category->name() }}@endif</p>
                            <h3 class="mt-1 font-extrabold group-hover:text-brand-700">{{ $e->tr('title') }}</h3>
                            @if ($e->tr('location'))<p class="mt-1 flex items-center gap-1 text-xs text-slate-500"><x-icon name="map-pin" class="h-3.5 w-3.5" />{{ $e->tr('location') }}</p>@endif
                            @if ($e->tr('description'))<p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $e->tr('description') }}</p>@endif
                        </div>
                    </a>
                @endforeach
            </div>
            {{ $events->links() }}
        @endif
    </div>
</section>
@endsection
