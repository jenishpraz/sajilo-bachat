@if ($branches->isNotEmpty())
<section class="section section-alt">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.branches')" :title="ui('home.branches_title')" :href="route('branches')" :cta="ui('common.view_all')" />
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($branches as $b)
                <div class="reveal card p-6">
                    <div class="flex items-start justify-between gap-2">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon name="building" class="h-5 w-5" /></span>
                        <div class="flex gap-1">@if ($b->is_head_office)<span class="badge badge-green">{{ ui('branches.head_office') }}</span>@endif<x-draft :model="$b" /></div>
                    </div>
                    <h3 class="mt-4 text-lg font-extrabold">{{ $b->tr('name') }}</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        @if ($b->tr('address'))<li class="flex gap-2"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $b->tr('address') }}</li>@endif
                        @if ($b->phone)<li class="flex gap-2"><x-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $b->phone }}</li>@endif
                        @if ($b->tr('opening_hours'))<li class="flex gap-2"><x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $b->tr('opening_hours') }}</li>@endif
                    </ul>
                    @if ($b->map_url)<a href="{{ safe_url($b->map_url) }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700 hover:underline">{{ ui('branches.view_map') }}<x-icon name="external" class="h-4 w-4" /></a>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
