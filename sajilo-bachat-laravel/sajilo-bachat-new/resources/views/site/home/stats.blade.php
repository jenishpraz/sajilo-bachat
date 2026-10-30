@if ($stats->isNotEmpty())
<section class="section-alt py-12" aria-label="{{ ui('home.stats') }}">
    <div class="container-x">
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
            @foreach ($stats as $s)
                <div class="reveal card p-5 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon :name="$s->icon ?: 'chart'" class="h-5 w-5" /></span>
                    <p class="mt-3 text-2xl font-extrabold text-brand-800 sm:text-3xl">
                        @if ($s->prefix)<span class="text-base">{{ $s->prefix }}</span> @endif<span @if(is_numeric($s->value)) data-count="{{ $s->value }}" @endif>{{ num($s->value) }}</span>@if ($s->suffix)<span class="text-base">{{ $s->suffix }}</span>@endif
                    </p>
                    <p class="mt-1 text-xs font-semibold text-slate-600">{{ $s->tr('label') }}</p>
                    <x-draft :model="$s" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
