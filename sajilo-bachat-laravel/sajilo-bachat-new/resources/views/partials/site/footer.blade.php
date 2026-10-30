@php $name = setting_t('site_name', 'Sajilo Bachat'); @endphp
<footer class="mt-16 bg-brand-950 text-brand-100">
    <div class="container-x grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ site_logo() }}" alt="" class="h-14 w-14 rounded-full bg-white/95 object-contain p-1" loading="lazy">
                <span class="text-base font-extrabold leading-snug text-white">{{ $name }}</span>
            </a>
            @if (setting_t('footer_about'))<p class="mt-4 text-sm leading-relaxed text-brand-200">{{ setting_t('footer_about') }}</p>@endif
            @if ($socialLinks->isNotEmpty())
                <ul class="mt-5 flex flex-wrap gap-2" aria-label="{{ ui('footer.follow') }}">
                    @foreach ($socialLinks as $s)
                        <li><a href="{{ safe_url($s->url) }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-gold-500 hover:text-ink" aria-label="{{ \App\Models\SocialLink::PLATFORMS[$s->platform] ?? $s->platform }}"><x-icon :name="$s->platform" class="h-4 w-4" /></a></li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div>
            <h2 class="mb-4 text-sm font-bold tracking-wider text-white uppercase">{{ ui('footer.quick_links') }}</h2>
            <ul class="space-y-2 text-sm">
                <li><a class="hover:text-gold-400" href="{{ route('about') }}">{{ ui('nav.about') }}</a></li>
                <li><a class="hover:text-gold-400" href="{{ route('people.board') }}">{{ ui('nav.board') }}</a></li>
                <li><a class="hover:text-gold-400" href="{{ route('notices.index') }}">{{ ui('nav.notices') }}</a></li>
                <li><a class="hover:text-gold-400" href="{{ route('downloads') }}">{{ ui('nav.downloads') }}</a></li>
                <li><a class="hover:text-gold-400" href="{{ route('gallery.photos') }}">{{ ui('nav.photos') }}</a></li>
                <li><a class="hover:text-gold-400" href="{{ route('calculators') }}">{{ ui('nav.calculators') }}</a></li>
                <li><a class="hover:text-gold-400" href="{{ route('membership.create') }}">{{ ui('nav.member') }}</a></li>
            </ul>
        </div>

        <div>
            <h2 class="mb-4 text-sm font-bold tracking-wider text-white uppercase">{{ ui('nav.services') }}</h2>
            <ul class="space-y-2 text-sm">
                @foreach ($navPlans->take(4) as $p)<li><a class="hover:text-gold-400" href="{{ route('saving.show', $p->slug) }}">{{ $p->name() }}</a></li>@endforeach
                @foreach ($navLoans->take(4) as $l)<li><a class="hover:text-gold-400" href="{{ route('loans.show', $l->slug) }}">{{ $l->name() }}</a></li>@endforeach
                @if ($navPlans->isEmpty() && $navLoans->isEmpty())<li class="text-brand-300">{{ ui('common.coming_soon') }}</li>@endif
            </ul>
        </div>

        <div>
            <h2 class="mb-4 text-sm font-bold tracking-wider text-white uppercase">{{ ui('footer.contact') }}</h2>
            <ul class="space-y-3 text-sm">
                @if (setting_t('address'))<li class="flex gap-2.5"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" /><span>{{ setting_t('address') }}</span></li>@endif
                @if (setting('phone'))<li class="flex gap-2.5"><x-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" /><a class="hover:text-gold-400" href="tel:{{ preg_replace('/[^0-9+]/', '', setting('phone')) }}">{{ setting('phone') }}</a></li>@endif
                @if (setting('email'))<li class="flex gap-2.5"><x-icon name="mail" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" /><a class="break-all hover:text-gold-400" href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></li>@endif
                @if (setting_t('hours'))<li class="flex gap-2.5"><x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" /><span>{{ setting_t('hours') }}</span></li>@endif
                @if (! setting_t('address') && ! setting('phone') && ! setting('email'))<li class="text-brand-300">{{ ui('footer.contact_soon') }}</li>@endif
            </ul>
            <a href="{{ route('contact') }}" class="btn btn-accent mt-5">{{ ui('nav.contact') }}</a>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="container-x flex flex-col items-center justify-between gap-2 py-5 text-xs text-brand-300 sm:flex-row">
            <p>© {{ is_ne() ? devanagari_digits(date('Y')) : date('Y') }} {{ setting_t('copyright', $name) }}</p>
            <a href="{{ route('admin.login') }}" class="hover:text-white">{{ ui('footer.staff_login') }}</a>
        </div>
    </div>
</footer>
