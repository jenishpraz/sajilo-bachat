@extends('layouts.site')
@section('title', ui('nav.photos'))

@section('content')
<x-page-hero :title="ui('nav.photos')" :crumbs="[[ui('nav.activities'), null]]" />
<section class="section">
    <div class="container-x">
        @include('site.gallery._filter')
        @if ($photos->isEmpty())
            <x-empty-state icon="image" :title="ui('gallery.none')" :text="ui('gallery.none_hint')" />
        @else
            <div class="columns-2 gap-4 space-y-4 md:columns-3 lg:columns-4">
                @foreach ($photos as $p)
                    <a href="{{ public_url($p->image) }}" data-lightbox="gallery" data-caption="{{ $p->tr('title') }}{{ $p->tr('description') ? ' — '.$p->tr('description') : '' }}" class="group relative block break-inside-avoid overflow-hidden rounded-xl bg-brand-100">
                        <img src="{{ public_url($p->image) }}" alt="{{ $p->tr('title') }}" loading="lazy" decoding="async" class="w-full transition duration-500 group-hover:scale-105">
                        @if ($p->tr('title'))<span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 text-sm font-semibold text-white opacity-0 transition group-hover:opacity-100">{{ $p->tr('title') }}</span>@endif
                    </a>
                @endforeach
            </div>
            {{ $photos->links() }}
        @endif
    </div>
</section>
@endsection
