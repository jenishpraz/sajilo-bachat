@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($counts as $c)
            <a href="{{ $c['route'] }}" class="card flex items-center gap-4 p-5 transition hover:border-brand-300 hover:shadow-md">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-100 text-brand-700"><x-icon :name="$c['icon']" class="h-7 w-7" /></span>
                <div><p class="text-3xl font-extrabold text-ink">{{ $c['value'] }}</p><p class="text-sm font-semibold text-slate-600">{{ $c['label'] }}</p><p class="text-xs text-slate-400">{{ $c['sub'] }}</p></div>
            </a>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="card xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 p-5"><h2 class="font-extrabold">Recent membership applications</h2><a class="text-sm font-bold text-brand-700" href="{{ route('admin.applications.index') }}">View all</a></div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead><tr><th class="th">Reference</th><th class="th">Name</th><th class="th">Phone</th><th class="th">Status</th><th class="th">Date</th></tr></thead>
                    <tbody>
                        @forelse ($applications as $a)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="td"><a class="font-bold text-brand-700" href="{{ route('admin.applications.show', $a) }}">{{ $a->reference_no }}</a></td>
                                <td class="td">{{ $a->full_name }}</td><td class="td">{{ $a->phone }}</td>
                                <td class="td">@include('admin.applications._status', ['status' => $a->status])</td>
                                <td class="td text-slate-500">{{ $a->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="td py-10 text-center text-slate-400">No applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between border-b border-slate-100 p-5"><h2 class="font-extrabold">Recent messages</h2><a class="text-sm font-bold text-brand-700" href="{{ route('admin.messages.index') }}">View all</a></div>
            <ul class="divide-y divide-slate-100">
                @forelse ($messages as $m)
                    <li><a href="{{ route('admin.messages.show', $m) }}" class="block p-4 hover:bg-slate-50"><p class="flex items-center gap-2 text-sm font-bold">@unless ($m->is_read)<span class="h-2 w-2 rounded-full bg-accent-500"></span>@endunless{{ $m->subject }}</p><p class="text-xs text-slate-500">{{ $m->name }} · {{ $m->created_at->diffForHumans() }}</p></a></li>
                @empty
                    <li class="p-8 text-center text-sm text-slate-400">No messages yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="border-b border-slate-100 p-5"><h2 class="font-extrabold">Recent activity</h2></div>
        <ul class="divide-y divide-slate-100">
            @forelse ($activities as $a)
                <li><a href="{{ $a['url'] }}" class="flex items-center justify-between gap-4 p-4 hover:bg-slate-50"><span><span class="badge badge-green mr-2">{{ $a['type'] }}</span><span class="text-sm font-semibold">{{ $a['title'] }}</span></span><span class="text-xs text-slate-400">{{ $a['at']->diffForHumans() }}</span></a></li>
            @empty
                <li class="p-8 text-center text-sm text-slate-400">Nothing published yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
