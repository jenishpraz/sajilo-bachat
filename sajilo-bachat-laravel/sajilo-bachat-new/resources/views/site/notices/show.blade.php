@extends('layouts.site')
@section('title', $notice->title())
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $notice->tr('body')), 160))

@section('content')
@php $isAuction = $notice->type === 'auction'; @endphp
<x-page-hero :title="$notice->title()" :crumbs="[[ui('nav.notices'), null], [$isAuction ? ui('nav.auction_notices') : ui('nav.general_notices'), route($isAuction ? 'notices.auction' : 'notices.index')]]" />
<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-3">
        <article class="lg:col-span-2">
            <div class="mb-5 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                @if ($notice->is_important)<span class="badge badge-red">{{ ui('notices.important') }}</span>@endif
                @if ($notice->category)<span class="badge badge-gray">{{ $notice->category->name() }}</span>@endif
                @if ($notice->isExpired())<span class="badge badge-gray">{{ ui('notices.expired') }}</span>@endif
                <span class="inline-flex items-center gap-1"><x-icon name="calendar" class="h-4 w-4" />{{ fdate($notice->published_at ?? $notice->created_at) }}</span>
                @if ($notice->expires_at)<span>· {{ ui('notices.valid_until') }} {{ fdate($notice->expires_at) }}</span>@endif
            </div>
            @if ($notice->tr('body'))<x-prose :text="$notice->tr('body')" class="text-lg text-slate-700" />@endif

            @if ($notice->attachment)
                <div class="card mt-8 flex flex-wrap items-center justify-between gap-4 p-5">
                    <div class="flex items-center gap-3"><span class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-600"><x-icon name="file" class="h-6 w-6" /></span>
                        <div><p class="font-bold">{{ ui('notices.attachment') }}</p><p class="text-xs text-slate-500">{{ strtoupper(pathinfo($notice->attachment, PATHINFO_EXTENSION)) }}</p></div></div>
                    <a href="{{ public_url($notice->attachment) }}" target="_blank" rel="noopener" class="btn btn-primary"><x-icon name="download" class="h-4 w-4" />{{ ui('common.download') }}</a>
                </div>
                @if (strtolower(pathinfo($notice->attachment, PATHINFO_EXTENSION)) === 'pdf')
                    <iframe src="{{ public_url($notice->attachment) }}" title="{{ $notice->title() }}" class="mt-6 hidden h-[40rem] w-full rounded-xl border border-slate-200 md:block" loading="lazy"></iframe>
                @endif
            @endif
        </article>
        <aside>
            <div class="card p-6">
                <h2 class="mb-4 text-lg font-extrabold">{{ ui('notices.more') }}</h2>
                <div class="space-y-3">
                    @forelse ($related as $r)<x-notice-row :notice="$r" />@empty<p class="text-sm text-slate-500">{{ ui('notices.none') }}</p>@endforelse
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection
