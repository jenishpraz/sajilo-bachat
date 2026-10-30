@props(['person'])
<article class="group card card-hover overflow-hidden text-center">
    <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-br from-brand-100 to-brand-200">
        @if ($person->photo)
            <img src="{{ public_url($person->photo) }}" alt="{{ $person->name() }}" loading="lazy" decoding="async" class="h-full w-full object-cover object-top transition duration-500 group-hover:scale-105">
        @else
            <span class="flex h-full w-full items-center justify-center text-5xl font-extrabold text-brand-600/40" aria-hidden="true">{{ initials($person->name_en) }}</span>
        @endif
    </div>
    <div class="p-4">
        <h3 class="text-base font-bold">{{ $person->name() }}</h3>
        @if ($person->tr('designation'))<p class="mt-0.5 text-sm font-semibold text-brand-700">{{ $person->tr('designation') }}</p>@endif
        @if ($person->tenure)<p class="mt-0.5 text-xs text-slate-500">{{ $person->tenure }}</p>@endif
        @if ($person->tr('bio'))<p class="mt-2 line-clamp-3 text-xs text-slate-500">{{ $person->tr('bio') }}</p>@endif
        @if ($person->phone || $person->email)
            <div class="mt-3 flex justify-center gap-2 text-brand-700">
                @if ($person->phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $person->phone) }}" class="rounded-full bg-brand-50 p-2 hover:bg-brand-100" aria-label="{{ $person->phone }}"><x-icon name="phone" class="h-4 w-4" /></a>@endif
                @if ($person->email)<a href="mailto:{{ $person->email }}" class="rounded-full bg-brand-50 p-2 hover:bg-brand-100" aria-label="{{ $person->email }}"><x-icon name="mail" class="h-4 w-4" /></a>@endif
            </div>
        @endif
    </div>
</article>
