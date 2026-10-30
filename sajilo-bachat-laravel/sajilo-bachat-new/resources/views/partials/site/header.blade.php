@php
    $link = fn ($routeName, $label, $patterns = null) => ['label' => ui($label), 'url' => route($routeName), 'active' => request()->routeIs($patterns ?? $routeName)];
    $hr = [
        $link('people.board', 'nav.board'), $link('people.employees', 'nav.employees'),
        $link('people.former-committee', 'nav.former_committee'), $link('people.former-employees', 'nav.former_employees'),
    ];
    $activities = [
        $link('gallery.photos', 'nav.photos'), $link('gallery.videos', 'nav.videos'),
        $link('events.index', 'nav.events', ['events.*']), $link('calendar', 'nav.calendar'),
    ];
    $notices = [
        ['label' => ui('nav.general_notices'), 'url' => route('notices.index'), 'active' => request()->routeIs('notices.index')],
        ['label' => ui('nav.auction_notices'), 'url' => route('notices.auction'), 'active' => request()->routeIs('notices.auction')],
    ];
    $servicesActive = request()->routeIs('saving.*', 'loans.*', 'calculators');
    $name = setting_t('site_name', 'Sajilo Bachat');
@endphp
<header data-sticky-header class="sticky top-0 z-50 bg-white/95 backdrop-blur transition-shadow">
    <div class="container-x flex items-center justify-between gap-4 py-3">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3" aria-label="{{ $name }}">
            <img src="{{ site_logo() }}" alt="" class="h-12 w-12 shrink-0 object-contain sm:h-14 sm:w-14">
            <span class="min-w-0">
                <span class="block truncate text-sm font-extrabold leading-tight text-brand-800 sm:text-base xl:max-w-[15rem] xl:whitespace-normal 2xl:max-w-none">{{ $name }}</span>
                @if (setting_t('tagline'))<span class="hidden truncate text-xs text-slate-500 sm:block">{{ setting_t('tagline') }}</span>@endif
            </span>
        </a>

        {{-- Desktop navigation --}}
        <nav class="hidden items-center xl:flex" aria-label="{{ ui('common.main_menu') }}">
            <a href="{{ route('home') }}" @class(['nav-link', 'active' => request()->routeIs('home')])>{{ ui('nav.home') }}</a>
            <a href="{{ route('about') }}" @class(['nav-link', 'active' => request()->routeIs('about')])>{{ ui('nav.about') }}</a>

            @foreach ([
                ['nav.hr', $hr, request()->routeIs('people.*')],
                ['nav.activities', $activities, request()->routeIs('gallery.*', 'events.*', 'calendar')],
            ] as [$label, $children, $active])
                <div class="group relative" data-dropdown>
                    <button type="button" data-dropdown-btn aria-haspopup="true" aria-expanded="false" @class(['nav-link', 'active' => $active])>
                        {{ ui($label) }}<x-icon name="chevron-down" class="h-3.5 w-3.5 transition group-hover:rotate-180" />
                    </button>
                    <div class="invisible absolute left-0 top-full z-50 w-60 translate-y-1 pt-2 opacity-0 transition group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100 group-[.is-open]:visible group-[.is-open]:translate-y-0 group-[.is-open]:opacity-100">
                        <ul class="overflow-hidden rounded-xl border border-slate-200 bg-white py-2 shadow-xl">
                            @foreach ($children as $c)
                                <li><a href="{{ $c['url'] }}" @class(['block px-4 py-2 text-sm font-medium hover:bg-brand-50 hover:text-brand-700', 'text-brand-700 bg-brand-50' => $c['active'], 'text-slate-700' => ! $c['active']])>{{ $c['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            {{-- Services mega menu (lists live saving plans and loans from the database) --}}
            <div class="group relative" data-dropdown>
                <button type="button" data-dropdown-btn aria-haspopup="true" aria-expanded="false" @class(['nav-link', 'active' => $servicesActive])>
                    {{ ui('nav.services') }}<x-icon name="chevron-down" class="h-3.5 w-3.5 transition group-hover:rotate-180" />
                </button>
                <div class="invisible absolute left-1/2 top-full z-50 w-[44rem] max-w-[90vw] -translate-x-1/2 translate-y-1 pt-2 opacity-0 transition group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100 group-[.is-open]:visible group-[.is-open]:translate-y-0 group-[.is-open]:opacity-100">
                    <div class="grid grid-cols-2 gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-xl">
                        <div>
                            <a href="{{ route('saving.index') }}" class="mb-2 flex items-center gap-2 text-sm font-extrabold text-brand-700"><x-icon name="savings" class="h-5 w-5" />{{ ui('nav.saving') }}</a>
                            <ul class="space-y-0.5">
                                @forelse ($navPlans as $p)
                                    <li><a href="{{ route('saving.show', $p->slug) }}" class="block rounded px-2 py-1.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-700">{{ $p->name() }}</a></li>
                                @empty
                                    <li class="px-2 py-1.5 text-sm text-slate-400">{{ ui('common.coming_soon') }}</li>
                                @endforelse
                            </ul>
                        </div>
                        <div>
                            <a href="{{ route('loans.index') }}" class="mb-2 flex items-center gap-2 text-sm font-extrabold text-brand-700"><x-icon name="loan" class="h-5 w-5" />{{ ui('nav.loans') }}</a>
                            <ul class="space-y-0.5">
                                @forelse ($navLoans as $l)
                                    <li><a href="{{ route('loans.show', $l->slug) }}" class="block rounded px-2 py-1.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-700">{{ $l->name() }}</a></li>
                                @empty
                                    <li class="px-2 py-1.5 text-sm text-slate-400">{{ ui('common.coming_soon') }}</li>
                                @endforelse
                            </ul>
                        </div>
                        <a href="{{ route('calculators') }}" class="col-span-2 flex items-center justify-between rounded-lg bg-brand-50 px-4 py-3 text-sm font-bold text-brand-800 hover:bg-brand-100">
                            <span class="inline-flex items-center gap-2"><x-icon name="calculator" class="h-5 w-5" />{{ ui('nav.calculators') }}</span><x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('branches') }}" @class(['nav-link', 'active' => request()->routeIs('branches')])>{{ ui('nav.branches') }}</a>
            <a href="{{ route('downloads') }}" @class(['nav-link', 'active' => request()->routeIs('downloads')])>{{ ui('nav.downloads') }}</a>

            <div class="group relative" data-dropdown>
                <button type="button" data-dropdown-btn aria-haspopup="true" aria-expanded="false" @class(['nav-link', 'active' => request()->routeIs('notices.*')])>
                    {{ ui('nav.notices') }}<x-icon name="chevron-down" class="h-3.5 w-3.5 transition group-hover:rotate-180" />
                </button>
                <div class="invisible absolute right-0 top-full z-50 w-56 translate-y-1 pt-2 opacity-0 transition group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100 group-[.is-open]:visible group-[.is-open]:translate-y-0 group-[.is-open]:opacity-100">
                    <ul class="overflow-hidden rounded-xl border border-slate-200 bg-white py-2 shadow-xl">
                        @foreach ($notices as $c)
                            <li><a href="{{ $c['url'] }}" @class(['block px-4 py-2 text-sm font-medium hover:bg-brand-50 hover:text-brand-700', 'bg-brand-50 text-brand-700' => $c['active'], 'text-slate-700' => ! $c['active']])>{{ $c['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <a href="{{ route('contact') }}" @class(['nav-link', 'active' => request()->routeIs('contact')])>{{ ui('nav.contact') }}</a>
            <a href="{{ route('membership.create') }}" class="btn btn-primary ml-2">{{ ui('nav.member') }}</a>
        </nav>

        <button type="button" data-mobile-toggle class="rounded-lg border border-slate-200 p-2 text-slate-700 xl:hidden" aria-expanded="false" aria-controls="mobile-panel" aria-label="{{ ui('common.menu') }}">
            <x-icon name="menu" class="h-6 w-6" />
        </button>
    </div>

    {{-- Mobile navigation --}}
    <div id="mobile-panel" data-mobile-panel class="hidden max-h-[calc(100vh-5rem)] overflow-y-auto border-t border-slate-200 bg-white xl:hidden">
        <nav class="container-x py-4" aria-label="{{ ui('common.main_menu') }}">
            <ul class="space-y-1 text-base font-semibold">
                <li><a class="block rounded-lg px-3 py-2.5 hover:bg-brand-50" href="{{ route('home') }}">{{ ui('nav.home') }}</a></li>
                <li><a class="block rounded-lg px-3 py-2.5 hover:bg-brand-50" href="{{ route('about') }}">{{ ui('nav.about') }}</a></li>

                @php
                    $mobileGroups = [
                        ['m-hr', 'nav.hr', $hr],
                        ['m-services', 'nav.services', array_merge(
                            [['label' => ui('nav.saving'), 'url' => route('saving.index')]],
                            $navPlans->map(fn ($p) => ['label' => '– '.$p->name(), 'url' => route('saving.show', $p->slug)])->all(),
                            [['label' => ui('nav.loans'), 'url' => route('loans.index')]],
                            $navLoans->map(fn ($l) => ['label' => '– '.$l->name(), 'url' => route('loans.show', $l->slug)])->all(),
                            [['label' => ui('nav.calculators'), 'url' => route('calculators')]]
                        )],
                        ['m-activities', 'nav.activities', $activities],
                    ];
                @endphp
                @foreach ($mobileGroups as [$id, $label, $children])
                    <li>
                        <button type="button" data-accordion aria-expanded="false" aria-controls="{{ $id }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left hover:bg-brand-50">
                            {{ ui($label) }}<x-icon name="chevron-down" data-chevron class="h-4 w-4 transition" />
                        </button>
                        <ul id="{{ $id }}" class="mb-1 ml-3 hidden space-y-0.5 border-l-2 border-brand-100 pl-3 text-sm font-medium">
                            @foreach ($children as $c)
                                <li><a class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-brand-50 hover:text-brand-700" href="{{ $c['url'] }}">{{ $c['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach

                <li><a class="block rounded-lg px-3 py-2.5 hover:bg-brand-50" href="{{ route('branches') }}">{{ ui('nav.branches') }}</a></li>
                <li><a class="block rounded-lg px-3 py-2.5 hover:bg-brand-50" href="{{ route('downloads') }}">{{ ui('nav.downloads') }}</a></li>
                <li>
                    <button type="button" data-accordion aria-expanded="false" aria-controls="m-notices" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left hover:bg-brand-50">
                        {{ ui('nav.notices') }}<x-icon name="chevron-down" data-chevron class="h-4 w-4 transition" />
                    </button>
                    <ul id="m-notices" class="mb-1 ml-3 hidden space-y-0.5 border-l-2 border-brand-100 pl-3 text-sm font-medium">
                        @foreach ($notices as $c)
                            <li><a class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-brand-50 hover:text-brand-700" href="{{ $c['url'] }}">{{ $c['label'] }}</a></li>
                        @endforeach
                    </ul>
                </li>
                <li><a class="block rounded-lg px-3 py-2.5 hover:bg-brand-50" href="{{ route('contact') }}">{{ ui('nav.contact') }}</a></li>
                <li class="pt-2"><a class="btn btn-primary w-full" href="{{ route('membership.create') }}">{{ ui('nav.member') }}</a></li>
            </ul>
        </nav>
    </div>
</header>
