@php $siteName = setting_t('site_name', 'Sajilo Bachat'); @endphp
<section data-hero class="relative isolate overflow-hidden bg-brand-900 text-white" aria-roledescription="carousel" aria-label="{{ ui('home.banners') }}">
    <div class="relative min-h-[26rem] sm:min-h-[32rem] lg:min-h-[36rem]">
        @forelse ($banners as $i => $b)
            <div data-slide class="hero-slide absolute inset-0 {{ $i === 0 ? 'is-active' : '' }}">
                @if ($b->image)<img src="{{ public_url($b->image) }}" alt="" class="absolute inset-0 h-full w-full object-cover" @if($i > 0) loading="lazy" @endif decoding="async">@endif
                <div class="absolute inset-0 bg-gradient-to-r from-brand-950/90 via-brand-900/65 to-brand-900/20"></div>
                <div class="container-x relative flex h-full min-h-[26rem] items-center py-16 sm:min-h-[32rem] lg:min-h-[36rem]">
                    <div class="max-w-2xl">
                        <p class="eyebrow !text-gold-400">{{ $siteName }}</p>
                        <h1 class="mt-3 text-3xl font-extrabold leading-tight !text-white sm:text-5xl">{{ $b->tr('title') }}</h1>
                        @if ($b->tr('subtitle'))<p class="mt-4 max-w-xl text-base text-brand-50 sm:text-lg">{{ $b->tr('subtitle') }}</p>@endif
                        <div class="mt-8 flex flex-wrap gap-3">
                            @if ($b->tr('button_text') && $b->button_url)<a href="{{ safe_url($b->button_url) }}" class="btn btn-accent px-6 py-3">{{ $b->tr('button_text') }}</a>@endif
                            @if ($b->tr('button2_text') && $b->button2_url)<a href="{{ safe_url($b->button2_url) }}" class="btn btn-ghost-light px-6 py-3">{{ $b->tr('button2_text') }}</a>@endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Default hero shown until banners are added in Admin → Homepage Banners --}}
            <div data-slide class="hero-slide is-active absolute inset-0">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_25%,rgba(242,184,75,.25),transparent_40%),linear-gradient(120deg,#0a2616,#1f7a43)]"></div>
                <div class="absolute -right-24 top-10 hidden h-[28rem] w-[28rem] rounded-full border-[48px] border-white/5 lg:block"></div>
                <div class="container-x relative flex min-h-[26rem] items-center py-16 sm:min-h-[32rem] lg:min-h-[36rem]">
                    <div class="max-w-2xl">
                        <p class="eyebrow !text-gold-400">{{ $siteName }}</p>
                        <h1 class="mt-3 text-3xl font-extrabold leading-tight !text-white sm:text-5xl">{{ setting_t('tagline', ui('home.default_title')) }}</h1>
                        <p class="mt-4 max-w-xl text-base text-brand-50 sm:text-lg">{{ ui('home.default_subtitle') }}</p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('membership.create') }}" class="btn btn-accent px-6 py-3">{{ ui('nav.member') }}</a>
                            <a href="{{ route('saving.index') }}" class="btn btn-ghost-light px-6 py-3">{{ ui('nav.saving') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($banners->count() > 1)
        <button type="button" data-prev class="absolute left-3 top-1/2 hidden -translate-y-1/2 rounded-full bg-white/15 p-2 hover:bg-white/30 sm:block" aria-label="{{ ui('common.previous') }}"><x-icon name="chevron-left" class="h-6 w-6" /></button>
        <button type="button" data-next class="absolute right-3 top-1/2 hidden -translate-y-1/2 rounded-full bg-white/15 p-2 hover:bg-white/30 sm:block" aria-label="{{ ui('common.next') }}"><x-icon name="chevron-right" class="h-6 w-6" /></button>
        <div class="absolute bottom-24 left-0 right-0 flex justify-center gap-2 sm:bottom-28">
            @foreach ($banners as $i => $b)
                <button type="button" data-dot aria-label="{{ $i + 1 }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}" class="h-2.5 w-2.5 rounded-full bg-white/50 transition aria-[current=true]:w-7 aria-[current=true]:bg-gold-400"></button>
            @endforeach
        </div>
    @endif
</section>
