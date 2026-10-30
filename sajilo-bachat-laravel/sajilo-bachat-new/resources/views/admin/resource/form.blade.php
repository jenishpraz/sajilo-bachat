@extends('layouts.admin')
@php $editing = $model->exists; @endphp
@section('title', ($editing ? 'Edit ' : 'New ').strtolower($def['singular']))

@section('content')
<div class="mx-auto max-w-5xl">
    <a href="{{ route('admin.resource.index', $resource) }}" class="mb-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-700"><x-icon name="chevron-left" class="h-4 w-4" />Back to {{ strtolower($def['label']) }}</a>

    <form method="POST" enctype="multipart/form-data" data-loading="Saving…"
          action="{{ $editing ? route('admin.resource.update', [$resource, $model->id]) : route('admin.resource.store', $resource) }}" class="card p-5 sm:p-8">
        @csrf
        @if ($editing) @method('PUT') @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                <p class="font-bold">Please correct the highlighted fields.</p>
            </div>
        @endif

        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($def['fields'] as $f)
                @php
                    $name = $f['name'];
                    $stored = $editing ? $model->getAttribute($name) : ($f['default'] ?? null);
                    $value = $f['type'] === 'checkbox'
                        ? (session()->hasOldInput() ? (bool) old($name, false) : (bool) $stored)
                        : (in_array($f['type'], ['image', 'file']) ? null : old($name, $stored));
                    $current = in_array($f['type'], ['image', 'file']) && $editing ? $model->getAttribute($name) : null;
                @endphp
                @include('admin.partials.field', ['f' => $f, 'value' => $value, 'current' => $current])
            @endforeach
        </div>

        <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="btn btn-primary px-8">{{ $editing ? 'Save changes' : 'Create' }}</button>
            <a href="{{ route('admin.resource.index', $resource) }}" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
