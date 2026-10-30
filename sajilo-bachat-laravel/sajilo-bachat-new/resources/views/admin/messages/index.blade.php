@extends('layouts.admin')
@section('title', 'Contact messages')

@section('content')
<div class="space-y-5">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search messages…" class="field max-w-xs" aria-label="Search">
        <select name="read" data-autosubmit class="field w-auto" aria-label="Read state"><option value="">All</option><option value="0" @selected(request('read') === '0')>Unread</option><option value="1" @selected(request('read') === '1')>Read</option></select>
        <button class="btn btn-outline"><x-icon name="search" class="h-4 w-4" />Search</button>
    </form>
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[40rem]">
                <thead class="bg-slate-50"><tr><th class="th">From</th><th class="th">Subject</th><th class="th">Received</th><th class="th text-right">Actions</th></tr></thead>
                <tbody>
                    @forelse ($messages as $m)
                        <tr class="border-t border-slate-100 hover:bg-slate-50/70 {{ $m->is_read ? '' : 'bg-brand-50/40' }}">
                            <td class="td"><span class="flex items-center gap-2 font-semibold">@unless ($m->is_read)<span class="h-2 w-2 rounded-full bg-accent-500" title="Unread"></span>@endunless{{ $m->name }}</span><span class="block text-xs text-slate-500">{{ $m->email }}</span></td>
                            <td class="td"><a href="{{ route('admin.messages.show', $m) }}" class="font-semibold text-brand-700">{{ \Illuminate\Support\Str::limit($m->subject, 60) }}</a><span class="block text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($m->message, 80) }}</span></td>
                            <td class="td text-slate-500">{{ $m->created_at->format('d M Y, H:i') }}</td>
                            <td class="td"><div class="flex justify-end gap-1.5">
                                <form method="POST" action="{{ route('admin.messages.toggle', $m) }}">@csrf<button class="btn btn-sm bg-slate-100 text-slate-700 hover:bg-slate-200">{{ $m->is_read ? 'Mark unread' : 'Mark read' }}</button></form>
                                <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" data-confirm="Delete this message permanently?">@csrf @method('DELETE')<button class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100" aria-label="Delete"><x-icon name="trash" class="h-4 w-4" /></button></form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-14 text-center text-slate-400">No messages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($messages->hasPages())<div class="border-t border-slate-100 px-4 pb-6">{{ $messages->links() }}</div>@endif
    </div>
</div>
@endsection
