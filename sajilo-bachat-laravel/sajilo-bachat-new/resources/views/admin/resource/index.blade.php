@extends('layouts.admin')
@section('title', $def['label'])

@section('content')
@php $canCreate = $def['can']['create'] ?? true; $canDelete = $def['can']['delete'] ?? true; @endphp
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="GET" class="flex flex-1 flex-wrap items-center gap-2">
            @if (! empty($def['search']))
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search…" class="field max-w-xs" aria-label="Search">
            @endif
            @foreach ($def['filters'] ?? [] as $column => $filter)
                <select name="{{ $column }}" data-autosubmit class="field w-auto" aria-label="{{ $filter['label'] }}">
                    <option value="">{{ $filter['label'] }}: all</option>
                    @if ($filter['type'] === 'bool')
                        <option value="1" @selected(request($column) === '1')>{{ $filter['true'] ?? 'Yes / active' }}</option>
                        <option value="0" @selected(request($column) === '0')>{{ $filter['false'] ?? 'No / inactive' }}</option>
                    @else
                        @foreach (\App\Support\AdminResources::options($filter) as $k => $label)<option value="{{ $k }}" @selected((string) request($column) === (string) $k)>{{ $label }}</option>@endforeach
                    @endif
                </select>
            @endforeach
            <button class="btn btn-outline"><x-icon name="search" class="h-4 w-4" />Search</button>
            @if (request()->hasAny(array_merge(['q'], array_keys($def['filters'] ?? []))) && request()->query())
                <a href="{{ route('admin.resource.index', $resource) }}" class="text-sm font-semibold text-slate-500 hover:text-ink">Clear</a>
            @endif
        </form>
        @if ($canCreate)<a href="{{ route('admin.resource.create', $resource) }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" />Add {{ strtolower($def['singular']) }}</a>@endif
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[40rem]">
                <thead class="bg-slate-50"><tr>
                    @foreach ($def['columns'] as $col)<th class="th">{{ $col['label'] }}</th>@endforeach
                    <th class="th text-right">Actions</th>
                </tr></thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="border-t border-slate-100 hover:bg-slate-50/70">
                            @foreach ($def['columns'] as $col)
                                @php $val = data_get($item, $col['name']); @endphp
                                <td class="td">
                                    @switch($col['type'])
                                        @case('image')
                                            @if ($val)<img src="{{ public_url($val) }}" alt="" loading="lazy" class="h-12 w-12 rounded-lg border border-slate-200 object-cover object-top">@else<span class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-300"><x-icon name="image" class="h-5 w-5" /></span>@endif
                                            @break
                                        @case('bool')
                                            <span class="badge {{ $val ? 'badge-green' : 'badge-gray' }}">{{ $val ? 'Active' : 'Inactive' }}</span>
                                            @break
                                        @case('published')
                                            <span class="badge {{ $val ? 'badge-green' : 'badge-gray' }}">{{ $val ? 'Published' : 'Draft' }}</span>
                                            @break
                                        @case('draft')
                                            @if ($val)<span class="badge badge-gold">Sample</span>@else<span class="badge badge-green">Official</span>@endif
                                            @break
                                        @case('percent')
                                            {{ $val !== null ? rtrim(rtrim(number_format((float) $val, 2), '0'), '.').'%' : '—' }}
                                            @break
                                        @case('stat')
                                            {{ $item->prefix }} {{ $val }}{{ $item->suffix }}
                                            @break
                                        @case('date')
                                            {{ $val ? $val->format('d M Y') : '—' }}
                                            @break
                                        @case('datetime')
                                            {{ $val ? $val->format('d M Y, H:i') : '—' }}
                                            @break
                                        @case('section')
                                            {{ \App\Support\Committee::SECTIONS[$val]['en'] ?? $val }}
                                            @break
                                        @case('category_type')
                                            {{ \App\Models\Category::TYPES[$val] ?? $val }}
                                            @break
                                        @case('key')
                                            <span class="font-semibold">{{ str_replace('_', ' ', ucfirst((string) $val)) }}</span>
                                            @break
                                        @default
                                            <span class="font-semibold text-ink">{{ \Illuminate\Support\Str::limit((string) $val, 70) ?: '—' }}</span>
                                            @if (! empty($col['flag']) && $item->{$col['flag']})<span class="badge badge-red ml-1">Important</span>@endif
                                            @if (! empty($col['sub']) && data_get($item, $col['sub']))<span class="block text-xs text-slate-500">{{ \Illuminate\Support\Str::limit(data_get($item, $col['sub']), 70) }}</span>@endif
                                    @endswitch
                                </td>
                            @endforeach
                            <td class="td">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if (! empty($def['toggle']))
                                        <form method="POST" action="{{ route('admin.resource.toggle', [$resource, $item->id]) }}">@csrf
                                            <button class="btn btn-sm {{ $item->{$def['toggle']} ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-brand-100 text-brand-800 hover:bg-brand-200' }}" title="{{ $item->{$def['toggle']} ? 'Unpublish / deactivate' : 'Publish / activate' }}">{{ $item->{$def['toggle']} ? 'Unpublish' : 'Publish' }}</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.resource.edit', [$resource, $item->id]) }}" class="btn btn-sm bg-slate-100 text-slate-700 hover:bg-slate-200" aria-label="Edit"><x-icon name="edit" class="h-4 w-4" />Edit</a>
                                    @if ($canDelete)
                                        <form method="POST" action="{{ route('admin.resource.destroy', [$resource, $item->id]) }}" data-confirm="This will permanently delete this {{ strtolower($def['singular']) }} and any files attached to it.">@csrf @method('DELETE')
                                            <button class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100" aria-label="Delete"><x-icon name="trash" class="h-4 w-4" /></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($def['columns']) + 1 }}" class="px-4 py-14 text-center text-slate-400">Nothing here yet.@if ($canCreate) <a class="font-bold text-brand-700" href="{{ route('admin.resource.create', $resource) }}">Add the first one</a>.@endif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages())<div class="border-t border-slate-100 px-4 pb-6">{{ $items->links() }}</div>@endif
    </div>
    <p class="text-xs text-slate-400">{{ $items->total() }} total</p>
</div>
@endsection
