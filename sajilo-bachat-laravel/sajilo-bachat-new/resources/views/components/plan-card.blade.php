@props(['item', 'route', 'kind' => 'saving'])
<a href="{{ route($route, $item->slug) }}" class="group card card-hover flex flex-col overflow-hidden">
    @if ($item->image)<x-pic :src="public_url($item->image)" :alt="$item->name()" ratio="aspect-[16/9]" />@endif
    <div class="flex flex-1 flex-col p-6">
        <div class="flex items-start justify-between gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-700"><x-icon :name="$item->icon ?: ($kind === 'saving' ? 'savings' : 'loan')" class="h-6 w-6" /></span>
            <x-draft :model="$item" />
        </div>
        <h3 class="mt-4 text-lg font-extrabold group-hover:text-brand-700">{{ $item->name() }}</h3>
        @if ($item->tr('summary'))<p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $item->tr('summary') }}</p>@endif
        <dl class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm">
            <div><dt class="text-xs text-slate-500">{{ ui('common.interest_rate') }}</dt><dd class="font-extrabold text-brand-700">{{ $item->interest_rate !== null ? num($item->interest_rate, 2).'%' : ui('common.as_per_policy') }}</dd></div>
            @if ($kind === 'saving')
                <div><dt class="text-xs text-slate-500">{{ ui('saving.min_deposit') }}</dt><dd class="font-bold">{{ $item->min_deposit !== null ? money($item->min_deposit) : '—' }}</dd></div>
            @else
                <div><dt class="text-xs text-slate-500">{{ ui('loans.max_amount') }}</dt><dd class="font-bold">{{ $item->max_amount !== null ? money($item->max_amount) : '—' }}</dd></div>
            @endif
        </dl>
        <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700">{{ ui('common.view_details') }}<x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
    </div>
</a>
