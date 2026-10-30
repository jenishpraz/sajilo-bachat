@props(['text', 'icon' => 'check-circle'])
@php $items = lines($text); @endphp
@if ($items)
    <ul {{ $attributes->merge(['class' => 'space-y-2.5']) }}>
        @foreach ($items as $i)
            <li class="flex gap-2.5"><x-icon :name="$icon" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" /><span>{{ $i }}</span></li>
        @endforeach
    </ul>
@endif
