@props(['icon' => 'inbox', 'title', 'text' => null])
<div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-brand-100 text-brand-700"><x-icon :name="$icon" class="h-7 w-7" /></span>
    <h3 class="mt-4 text-lg font-bold">{{ $title }}</h3>
    @if ($text)<p class="mx-auto mt-1 max-w-md text-sm text-slate-500">{{ $text }}</p>@endif
    {{ $slot }}
</div>
