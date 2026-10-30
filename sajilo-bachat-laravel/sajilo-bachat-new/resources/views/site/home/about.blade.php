@php $intro = $content['about_intro'] ?? null; @endphp
@if ($intro)
<section class="section">
    <div class="container-x grid items-center gap-10 lg:grid-cols-2">
        <div class="reveal relative">
            @if ($intro->image)
                <img src="{{ public_url($intro->image) }}" alt="{{ $intro->tr('title') }}" loading="lazy" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl">
            @else
                <div class="flex aspect-[4/3] w-full items-center justify-center rounded-2xl bg-gradient-to-br from-brand-700 to-brand-500 shadow-xl">
                    <img src="{{ site_logo() }}" alt="" class="h-40 w-40 rounded-full bg-white/95 object-contain p-4 shadow-lg" loading="lazy">
                </div>
            @endif
            <div class="absolute -bottom-5 -right-3 hidden rounded-2xl bg-gold-500 px-6 py-4 text-ink shadow-lg sm:block"><p class="text-xs font-bold uppercase">{{ ui('home.member_owned') }}</p></div>
        </div>
        <div class="reveal">
            <p class="eyebrow mb-2">{{ ui('home.about_eyebrow') }}</p>
            <h2 class="section-title">{{ $intro->tr('title') }}</h2>
            <x-prose :text="\Illuminate\Support\Str::limit($intro->tr('body'), 700)" class="mt-4 text-slate-600" />
            <a href="{{ route('about') }}" class="btn btn-primary mt-2">{{ ui('common.read_more') }}<x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
    </div>
</section>
@endif
