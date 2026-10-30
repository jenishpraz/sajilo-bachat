@props(['eyebrow' => null, 'title', 'subtitle' => null, 'center' => false, 'href' => null, 'cta' => null])
<div @class(['mb-10 flex flex-wrap items-end justify-between gap-4', 'text-center !justify-center' => $center])>
    <div @class(['max-w-2xl', 'mx-auto' => $center])>
        @if ($eyebrow)<p class="eyebrow mb-2">{{ $eyebrow }}</p>@endif
        <h2 class="section-title">{{ $title }}</h2>
        @if ($subtitle)<p class="mt-3 text-slate-600">{{ $subtitle }}</p>@endif
    </div>
    @if ($href && $cta)
        <a href="{{ $href }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-700 hover:text-brand-800">{{ $cta }}<x-icon name="arrow-right" class="h-4 w-4" /></a>
    @endif
</div>
