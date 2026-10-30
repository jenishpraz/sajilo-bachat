<section class="relative z-10 -mt-16 pb-6 sm:-mt-20">
    <div class="container-x grid gap-6 lg:grid-cols-3">
        {{-- Quick access cards --}}
        <div class="grid grid-cols-2 gap-4 lg:col-span-1">
            @foreach ([
                ['downloads', 'download', 'home.q_downloads'], ['gallery.photos', 'image', 'home.q_gallery'],
                ['calculators', 'calculator', 'home.q_calculator'], ['membership.create', 'user-plus', 'home.q_membership'],
            ] as [$route, $icon, $label])
                <a href="{{ route($route) }}" class="group card card-hover flex flex-col items-center justify-center gap-3 p-5 text-center">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-100 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-icon :name="$icon" class="h-7 w-7" /></span>
                    <span class="text-sm font-bold text-ink">{{ ui($label) }}</span>
                </a>
            @endforeach
        </div>

        {{-- Latest notices --}}
        <div class="card p-5 sm:p-6 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-lg font-extrabold"><x-icon name="bell" class="h-5 w-5 text-accent-500" />{{ ui('home.latest_notices') }}</h2>
                <a href="{{ route('notices.index') }}" class="text-sm font-bold text-brand-700 hover:underline">{{ ui('common.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse ($notices as $n)
                    <x-notice-row :notice="$n" />
                @empty
                    <p class="rounded-lg bg-slate-50 p-6 text-center text-sm text-slate-500">{{ ui('notices.none') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
