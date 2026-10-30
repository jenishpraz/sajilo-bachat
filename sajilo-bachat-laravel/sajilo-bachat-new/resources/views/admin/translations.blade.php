@extends('layouts.admin')
@section('title', 'Language & website content')

@section('content')
<div class="space-y-5">
    <p class="max-w-3xl text-sm text-slate-600">Edit any wording used across the website in English and नेपाली. Leave a box empty to keep the default text. Content such as notices, plans and profiles is edited in its own module; this page controls labels, buttons, headings and messages.</p>

    <form method="GET" class="card flex flex-wrap items-center gap-3 p-4">
        <select name="group" data-autosubmit class="field w-auto" aria-label="Section">@foreach ($groups as $g)<option value="{{ $g }}" @selected($group === $g && $term === '')>{{ ucfirst(str_replace('_', ' ', $g)) }}</option>@endforeach</select>
        <input type="search" name="q" value="{{ $term }}" placeholder="Search all text…" class="field max-w-xs" aria-label="Search">
        <button class="btn btn-outline"><x-icon name="search" class="h-4 w-4" />Search</button>
    </form>

    <form method="POST" action="{{ route('admin.translations.update') }}" class="card" data-loading="Saving…">
        @csrf @method('PUT')
        <div class="divide-y divide-slate-100">
            @forelse ($rows as $r)
                @php $k = str_replace('.', '__', $r['key']); @endphp
                <div class="grid gap-3 p-4 md:grid-cols-2">
                    <div><label class="label !mb-1 font-mono text-xs !text-slate-400" for="en_{{ $k }}">{{ $r['key'] }} — English</label>
                        <textarea id="en_{{ $k }}" name="text[{{ $k }}][en]" rows="2" placeholder="{{ $r['default_en'] }}" class="field">{{ $r['en'] }}</textarea></div>
                    <div><label class="label !mb-1 text-xs !text-slate-400" for="ne_{{ $k }}">नेपाली</label>
                        <textarea id="ne_{{ $k }}" name="text[{{ $k }}][ne]" rows="2" lang="ne" placeholder="{{ $r['default_ne'] }}" class="field">{{ $r['ne'] }}</textarea></div>
                </div>
            @empty
                <p class="p-10 text-center text-slate-400">No text found.</p>
            @endforelse
        </div>
        @if ($rows->isNotEmpty())<div class="sticky bottom-0 border-t border-slate-200 bg-white/95 p-4 backdrop-blur"><button class="btn btn-primary px-8">Save text</button></div>@endif
    </form>
</div>
@endsection
