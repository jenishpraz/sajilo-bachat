@extends('layouts.admin')
@section('title', $config['title'])

@section('content')
<div class="mx-auto max-w-5xl">
    @if (! empty($config['description']))<p class="mb-4 text-sm text-slate-600">{{ $config['description'] }}</p>@endif
    <form method="POST" action="{{ route('admin.settings.update', $section) }}" enctype="multipart/form-data" data-loading="Saving…" class="card p-5 sm:p-8">
        @csrf @method('PUT')
        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($config['fields'] as $f)
                @php
                    $name = $f['name'];
                    $stored = $values[$name] ?? ($f['default'] ?? null);
                    $isFile = in_array($f['type'], ['image', 'file']);
                    $value = $f['type'] === 'checkbox'
                        ? (session()->hasOldInput() ? (bool) old($name, false) : (bool) ($values[$name] ?? $f['default'] ?? false))
                        : ($isFile ? null : old($name, $stored));
                @endphp
                @include('admin.partials.field', ['f' => $f, 'value' => $value, 'current' => $isFile ? ($values[$name] ?? null) : null])
            @endforeach
        </div>
        <div class="mt-8 border-t border-slate-100 pt-6"><button class="btn btn-primary px-8">Save settings</button></div>
    </form>
</div>
@endsection
