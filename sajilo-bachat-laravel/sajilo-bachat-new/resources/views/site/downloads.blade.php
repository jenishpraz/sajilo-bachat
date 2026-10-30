@extends('layouts.site')
@section('title', ui('nav.downloads'))

@section('content')
<x-page-hero :title="ui('nav.downloads')" :subtitle="ui('downloads.sub')" />
<section class="section">
    <div class="container-x max-w-5xl">
        <form method="GET" action="{{ route('downloads') }}" class="mb-8 grid gap-3 sm:grid-cols-[1fr_16rem_auto]">
            <label class="sr-only" for="q">{{ ui('common.search') }}</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="{{ ui('common.search') }}…" class="field">
            <label class="sr-only" for="category">{{ ui('common.category') }}</label>
            <select id="category" name="category" class="field"><option value="">{{ ui('common.all_categories') }}</option>@foreach ($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name() }}</option>@endforeach</select>
            <button class="btn btn-primary"><x-icon name="search" class="h-4 w-4" />{{ ui('common.search') }}</button>
        </form>

        @if ($downloads->isEmpty())
            <x-empty-state icon="download" :title="ui('downloads.none')" :text="request()->hasAny(['q', 'category']) ? ui('common.try_different') : null" />
        @else
            <div class="space-y-3">
                @foreach ($downloads as $d)
                    <div class="card flex flex-wrap items-center gap-4 p-4 sm:p-5">
                        <span class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-100 text-brand-800"><x-icon name="file" class="h-5 w-5" /><span class="text-[10px] font-extrabold">{{ $d->extension() }}</span></span>
                        <div class="min-w-0 flex-1">
                            <h2 class="font-extrabold">{{ $d->tr('title') }}</h2>
                            <p class="mt-0.5 text-xs text-slate-500">@if ($d->category){{ $d->category->name() }} · @endif{{ $d->sizeLabel() }}</p>
                            @if ($d->tr('description'))<p class="mt-1 text-sm text-slate-600">{{ $d->tr('description') }}</p>@endif
                        </div>
                        <a href="{{ route('downloads.file', $d) }}" class="btn btn-primary"><x-icon name="download" class="h-4 w-4" />{{ ui('common.download') }}</a>
                    </div>
                @endforeach
            </div>
            {{ $downloads->links() }}
        @endif
    </div>
</section>
@endsection
