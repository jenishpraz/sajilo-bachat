{{-- Renders one form control. Expects: $f (field definition), $value (current value), $current (existing file path or null) --}}
@php
    $name = $f['name'];
    $type = $f['type'];
    $id = 'f_'.$name;
    $required = str_contains($f['rules'], 'required') && ! in_array($type, ['image', 'file']);
    $span = ($f['span'] ?? 1) === 2 || in_array($type, ['textarea']) && ($f['span'] ?? 1) === 2;
    $hasError = $errors->has($name);
    $cls = 'field'.($hasError ? ' field-error' : '');
    if ($value instanceof \DateTimeInterface) {
        $value = $value->format($type === 'datetime' ? 'Y-m-d\TH:i' : 'Y-m-d');
    }
@endphp
<div @class(['sm:col-span-2' => $span || in_array($type, ['image', 'file'])])>
    @if ($type === 'checkbox')
        <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3.5">
            <input type="hidden" name="{{ $name }}" value="0">
            <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="1" @checked((bool) $value) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
            <span class="text-sm font-semibold text-slate-700">{{ $f['label'] }}@isset($f['help'])<span class="mt-0.5 block text-xs font-normal text-slate-500">{{ $f['help'] }}</span>@endisset</span>
        </label>
    @else
        <label class="label" for="{{ $id }}">{{ $f['label'] }} @if ($required)<span class="text-accent-500">*</span>@endif</label>

        @if ($type === 'textarea')
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $f['rows'] ?? 4 }}" class="{{ $cls }}">{{ $value }}</textarea>
        @elseif ($type === 'select')
            @php $options = \App\Support\AdminResources::options($f); @endphp
            <select id="{{ $id }}" name="{{ $name }}" class="{{ $cls }}">
                <option value="">— Select —</option>
                @foreach ($options as $k => $label)<option value="{{ $k }}" @selected((string) $value === (string) $k)>{{ $label }}</option>@endforeach
            </select>
        @elseif ($type === 'image' || $type === 'file')
            @php $isImage = $type === 'image'; $library = $f['library'] ?? null; $lib = $library ? \App\Services\MediaService::library($library) : []; @endphp
            <div class="flex flex-wrap items-start gap-4">
                @if ($isImage)
                    <img id="prev_{{ $name }}" src="{{ $current ? public_url($current) : '' }}" alt="" class="{{ $current ? '' : 'hidden' }} h-28 w-28 rounded-lg border border-slate-200 bg-slate-50 object-cover">
                @elseif ($current)
                    <a href="{{ public_url($current) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-brand-700"><x-icon name="file" class="h-4 w-4" />Current file</a>
                @endif
                <div class="min-w-0 flex-1">
                    <input id="{{ $id }}" type="file" name="{{ $name }}" @if($isImage) accept="image/jpeg,image/png,image/webp" data-preview="prev_{{ $name }}" @endif class="{{ $cls }} file:mr-3 file:rounded-md file:border-0 file:bg-brand-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-800">
                    @if ($current && empty($f['required']))
                        <label class="mt-2 flex items-center gap-2 text-xs text-slate-600"><input type="checkbox" name="remove_{{ $name }}" value="1" class="rounded border-slate-300"> Remove current {{ $isImage ? 'image' : 'file' }}</label>
                    @endif
                </div>
            </div>
            @if ($lib)
                <details class="mt-3 rounded-lg border border-slate-200" @if(str_contains((string) $current, '/library/')) open @endif>
                    <summary class="cursor-pointer px-3 py-2 text-sm font-semibold text-brand-700">Choose from supplied portraits ({{ count($lib) }})</summary>
                    <div data-library="prev_{{ $name }}" class="grid grid-cols-4 gap-2 p-3 sm:grid-cols-6 md:grid-cols-8">
                        <input type="hidden" name="{{ $name }}__existing" value="{{ old($name.'__existing') }}">
                        @foreach ($lib as $path)
                            <button type="button" data-pick="{{ $path }}" data-url="{{ public_url($path) }}" aria-pressed="{{ $current === $path ? 'true' : 'false' }}" title="{{ basename($path) }}" @class(['overflow-hidden rounded-lg', 'ring-4 ring-brand-600' => $current === $path])><img src="{{ public_url($path) }}" alt="{{ basename($path) }}" loading="lazy" class="aspect-[4/5] w-full object-cover object-top"></button>
                        @endforeach
                    </div>
                    <p class="px-3 pb-3 text-xs text-slate-500">Click a portrait to select it (click again to deselect). Uploading a file above takes priority.</p>
                </details>
            @endif
        @else
            @php
                $inputType = match ($type) {
                    'decimal', 'number' => 'number', 'date' => 'date', 'datetime' => 'datetime-local', 'email' => 'email', 'tel' => 'tel',
                    'url' => preg_match('/(^|\|)url(\||$)/', $f['rules']) ? 'url' : 'text',
                    default => 'text',
                };
            @endphp
            <input id="{{ $id }}" type="{{ $inputType }}" name="{{ $name }}" value="{{ $value }}" @if ($type === 'decimal') step="0.01" min="0" @elseif ($type === 'number') step="1" min="0" @endif @required($required) class="{{ $cls }}">
        @endif

        @isset($f['help'])@if ($type !== 'checkbox')<p class="help">{{ $f['help'] }}</p>@endif @endisset
        @error($name)<p class="err">{{ $message }}</p>@enderror
        @error($name.'__existing')<p class="err">{{ $message }}</p>@enderror
    @endif
</div>
