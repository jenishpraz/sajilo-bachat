@extends('layouts.site')
@section('title', $event->tr('title'))
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $event->tr('description')), 160))
@if ($event->image)@section('og_image', public_url($event->image))@endif

@section('content')
<x-page-hero :title="$event->tr('title')" :crumbs="[[ui('nav.events'), route('events.index')]]" />
<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-3">
        <article class="lg:col-span-2">
            @if ($event->image)<img src="{{ public_url($event->image) }}" alt="{{ $event->tr('title') }}" class="mb-6 w-full rounded-2xl object-cover" decoding="async">@endif
            @if ($event->tr('description'))<x-prose :text="$event->tr('description')" class="text-lg text-slate-700" />@endif
        </article>
        <aside class="space-y-6">
            <div class="card p-6">
                <h2 class="mb-4 text-lg font-extrabold">{{ ui('events.details') }}</h2>
                <ul class="space-y-3 text-sm">
                    <li class="flex gap-3"><x-icon name="calendar" class="mt-0.5 h-5 w-5 text-brand-600" /><span>{{ fdate($event->event_date) }}@if ($event->end_date && ! $event->end_date->isSameDay($event->event_date)) – {{ fdate($event->end_date) }}@endif</span></li>
                    @if ($event->tr('location'))<li class="flex gap-3"><x-icon name="map-pin" class="mt-0.5 h-5 w-5 text-brand-600" /><span>{{ $event->tr('location') }}</span></li>@endif
                    @if ($event->category)<li class="flex gap-3"><x-icon name="tag" class="mt-0.5 h-5 w-5 text-brand-600" /><span>{{ $event->category->name() }}</span></li>@endif
                </ul>
            </div>
        </aside>
    </div>
    @if ($others->isNotEmpty())
        <div class="container-x mt-16">
            <h2 class="mb-6 text-xl font-extrabold">{{ ui('events.more') }}</h2>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($others as $o)
                    <a href="{{ route('events.show', $o->slug) }}" class="group card card-hover overflow-hidden"><x-pic :src="public_url($o->image)" :alt="$o->tr('title')" icon="calendar" ratio="aspect-[16/10]" /><div class="p-5"><p class="text-xs font-bold text-brand-700">{{ fdate($o->event_date) }}</p><h3 class="mt-1 font-extrabold">{{ $o->tr('title') }}</h3></div></a>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
