@extends('layouts.site')
@section('title', ui('nav.videos'))

@section('content')
<x-page-hero :title="ui('nav.videos')" :crumbs="[[ui('nav.activities'), null]]" />
<section class="section">
    <div class="container-x">
        @include('site.gallery._filter')
        @if ($videos->isEmpty())
            <x-empty-state icon="video" :title="ui('videos.none')" :text="ui('videos.none_hint')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($videos as $v)
                    @php $embed = video_embed_url($v->url); @endphp
                    <article class="group card card-hover overflow-hidden">
                        @if ($embed)
                            <button type="button" data-video="{{ $embed }}" data-title="{{ $v->tr('title') }}" class="relative block w-full text-left">
                                <x-pic :src="$v->thumb()" :alt="$v->tr('title')" icon="video" ratio="aspect-video" />
                                <span class="absolute inset-0 flex items-center justify-center bg-black/20 transition group-hover:bg-black/40"><span class="flex h-14 w-14 items-center justify-center rounded-full bg-white/95 text-brand-700 shadow-lg"><x-icon name="play" class="ml-0.5 h-6 w-6" /></span></span>
                            </button>
                        @else
                            <a href="{{ safe_url($v->url) }}" target="_blank" rel="noopener noreferrer" class="relative block"><x-pic :src="$v->thumb()" :alt="$v->tr('title')" icon="video" ratio="aspect-video" /><span class="absolute inset-0 flex items-center justify-center"><x-icon name="external" class="h-8 w-8 text-brand-700" /></span></a>
                        @endif
                        <div class="p-5">
                            @if ($v->category)<span class="badge badge-green">{{ $v->category->name() }}</span>@endif
                            <h3 class="mt-2 font-extrabold">{{ $v->tr('title') }}</h3>
                            @if ($v->tr('description'))<p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $v->tr('description') }}</p>@endif
                        </div>
                    </article>
                @endforeach
            </div>
            {{ $videos->links() }}
        @endif
    </div>
</section>
@endsection
