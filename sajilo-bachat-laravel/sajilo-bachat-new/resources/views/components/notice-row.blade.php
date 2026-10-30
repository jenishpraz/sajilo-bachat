@props(['notice'])
<a href="{{ route('notices.show', $notice->slug) }}" class="group flex items-start gap-4 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-brand-300 hover:shadow-md">
    @php
        $d = $notice->published_at ?? $notice->created_at;
        $short = ['जन', 'फेब्रु', 'मार्च', 'अप्रिल', 'मे', 'जुन', 'जुलाई', 'अग', 'सेप्ट', 'अक्टो', 'नोभे', 'डिसे'];
    @endphp
    <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-lg {{ $notice->is_important ? 'bg-accent-500 text-white' : 'bg-brand-100 text-brand-800' }}">
        <span class="text-lg font-extrabold leading-none">{{ is_ne() ? devanagari_digits($d->format('d')) : $d->format('d') }}</span>
        <span class="mt-0.5 text-[10px] font-bold uppercase">{{ is_ne() ? $short[$d->month - 1] : $d->format('M') }}</span>
    </div>
    <div class="min-w-0 flex-1">
        <div class="mb-1 flex flex-wrap items-center gap-1.5">
            @if ($notice->is_important)<span class="badge badge-red">{{ ui('notices.important') }}</span>@endif
            @if ($notice->type === 'auction')<span class="badge badge-gold">{{ ui('nav.auction_notices') }}</span>@endif
            @if ($notice->category)<span class="badge badge-gray">{{ $notice->category->name() }}</span>@endif
            @if ($notice->isExpired())<span class="badge badge-gray">{{ ui('notices.expired') }}</span>@endif
            @if ($notice->attachment)<span class="inline-flex items-center gap-1 text-xs text-slate-500"><x-icon name="file" class="h-3.5 w-3.5" />PDF</span>@endif
        </div>
        <h3 class="font-bold leading-snug text-ink group-hover:text-brand-700">{{ $notice->title() }}</h3>
        <p class="mt-0.5 text-xs text-slate-500">{{ fdate($notice->published_at ?? $notice->created_at) }}</p>
    </div>
    <x-icon name="chevron-right" class="mt-1 hidden h-5 w-5 shrink-0 text-slate-300 group-hover:text-brand-600 sm:block" />
</a>
