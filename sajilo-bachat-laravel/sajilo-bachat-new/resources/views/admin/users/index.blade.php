@extends('layouts.admin')
@section('title', 'Admin users')

@section('content')
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="GET" class="flex gap-2"><input type="search" name="q" value="{{ request('q') }}" placeholder="Search users…" class="field max-w-xs" aria-label="Search"><button class="btn btn-outline"><x-icon name="search" class="h-4 w-4" />Search</button></form>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" />Add user</a>
    </div>
    <div class="card overflow-hidden"><div class="overflow-x-auto">
        <table class="w-full min-w-[36rem]">
            <thead class="bg-slate-50"><tr><th class="th">Name</th><th class="th">Role</th><th class="th">Status</th><th class="th">Last login</th><th class="th text-right">Actions</th></tr></thead>
            <tbody>
                @foreach ($users as $u)
                    <tr class="border-t border-slate-100 hover:bg-slate-50/70">
                        <td class="td"><span class="font-semibold">{{ $u->name }}</span>@if ($u->id === auth()->id())<span class="badge badge-blue ml-1">You</span>@endif<span class="block text-xs text-slate-500">{{ $u->email }}</span></td>
                        <td class="td"><span class="badge {{ $u->isSuperAdmin() ? 'badge-green' : 'badge-gray' }}">{{ $u->isSuperAdmin() ? 'Super admin' : 'Editor' }}</span></td>
                        <td class="td"><span class="badge {{ $u->is_active ? 'badge-green' : 'badge-red' }}">{{ $u->is_active ? 'Active' : 'Disabled' }}</span></td>
                        <td class="td text-slate-500">{{ $u->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                        <td class="td"><div class="flex justify-end gap-1.5">
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm bg-slate-100 text-slate-700 hover:bg-slate-200"><x-icon name="edit" class="h-4 w-4" />Edit</a>
                            @if ($u->id !== auth()->id())<form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Delete user {{ $u->email }}?">@csrf @method('DELETE')<button class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100" aria-label="Delete"><x-icon name="trash" class="h-4 w-4" /></button></form>@endif
                        </div></td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
        @if ($users->hasPages())<div class="border-t border-slate-100 px-4 pb-6">{{ $users->links() }}</div>@endif
    </div>
</div>
@endsection
