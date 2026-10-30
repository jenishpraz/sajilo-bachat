@extends('layouts.site')
@section('title', ui('nav.branches'))

@section('content')
<x-page-hero :title="ui('nav.branches')" :subtitle="ui('branches.sub')" />
<section class="section">
    <div class="container-x">
        @if ($branches->isEmpty())
            <x-empty-state icon="building" :title="ui('branches.none')" />
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($branches as $b)
                    <article class="card flex flex-col overflow-hidden sm:flex-row">
                        @if ($b->image)<img src="{{ public_url($b->image) }}" alt="{{ $b->tr('name') }}" class="h-48 w-full object-cover sm:h-auto sm:w-48" loading="lazy">@endif
                        <div class="flex-1 p-6">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-xl font-extrabold">{{ $b->tr('name') }}</h2>
                                @if ($b->is_head_office)<span class="badge badge-green">{{ ui('branches.head_office') }}</span>@endif
                                <x-draft :model="$b" />
                            </div>
                            <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                                @if ($b->tr('address'))<li class="flex gap-2.5"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $b->tr('address') }}</li>@endif
                                @if ($b->phone)<li class="flex gap-2.5"><x-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /><a class="hover:text-brand-700" href="tel:{{ preg_replace('/[^0-9+]/', '', $b->phone) }}">{{ $b->phone }}</a></li>@endif
                                @if ($b->email)<li class="flex gap-2.5"><x-icon name="mail" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /><a class="break-all hover:text-brand-700" href="mailto:{{ $b->email }}">{{ $b->email }}</a></li>@endif
                                @if ($b->tr('manager'))<li class="flex gap-2.5"><x-icon name="users" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ ui('branches.manager') }}: {{ $b->tr('manager') }}</li>@endif
                                @if ($b->tr('opening_hours'))<li class="flex gap-2.5"><x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $b->tr('opening_hours') }}</li>@endif
                            </ul>
                            @if ($b->map_url)<a href="{{ safe_url($b->map_url) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm mt-5"><x-icon name="map-pin" class="h-4 w-4" />{{ ui('branches.view_map') }}</a>@endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
