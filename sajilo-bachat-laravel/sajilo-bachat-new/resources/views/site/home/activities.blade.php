<section class="section">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.activities')" :title="ui('home.activities_title')" :href="route('events.index')" :cta="ui('common.view_all')" />
        @if ($events->isEmpty() && $photos->isEmpty())
            <x-empty-state icon="calendar" :title="ui('events.none')" :text="ui('events.none_hint')" />
        @endif
        @if ($events->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($events as $e)
                    <a href="{{ route('events.show', $e->slug) }}" class="reveal group card card-hover overflow-hidden">
                        <x-pic :src="public_url($e->image)" :alt="$e->tr('title')" icon="calendar" ratio="aspect-[16/10]" />
                        <div class="p-5">
                            <p class="text-xs font-bold text-brand-700">{{ fdate($e->event_date) }}@if ($e->category) · {{ $e->category->name() }}@endif</p>
                            <h3 class="mt-1 font-extrabold group-hover:text-brand-700">{{ $e->tr('title') }}</h3>
                            @if ($e->tr('description'))<p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $e->tr('description') }}</p>@endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
        @if ($photos->isNotEmpty())
            <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($photos as $ph)
                    <a href="{{ public_url($ph->image) }}" data-lightbox="home" data-caption="{{ $ph->tr('title') }}" class="group block overflow-hidden rounded-xl">
                        <x-pic :src="public_url($ph->image)" :alt="$ph->tr('title')" ratio="aspect-square" />
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
