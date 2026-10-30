@props(['src' => null, 'alt' => '', 'icon' => 'image', 'ratio' => 'aspect-[4/3]', 'eager' => false])
<div {{ $attributes->merge(['class' => "relative overflow-hidden {$ratio} skeleton-img"]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt }}" @if(! $eager) loading="lazy" @endif decoding="async" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
    @else
        <span class="absolute inset-0 flex items-center justify-center text-brand-600/50"><x-icon :name="$icon" class="h-12 w-12" /></span>
    @endif
</div>
