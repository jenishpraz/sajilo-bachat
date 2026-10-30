@extends('layouts.admin')
@php $editing = $user->exists; @endphp
@section('title', $editing ? 'Edit user' : 'New user')

@section('content')
<form method="POST" action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" class="card mx-auto max-w-xl space-y-5 p-6 sm:p-8" data-loading="Saving…">
    @csrf @if ($editing) @method('PUT') @endif
    <div><label class="label" for="name">Name</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required class="field">@error('name')<p class="err">{{ $message }}</p>@enderror</div>
    <div><label class="label" for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="field">@error('email')<p class="err">{{ $message }}</p>@enderror</div>
    <div><label class="label" for="role">Role</label>
        <select id="role" name="role" class="field"><option value="editor" @selected(old('role', $user->role) === 'editor')>Editor — manages website content</option><option value="super_admin" @selected(old('role', $user->role) === 'super_admin')>Super admin — also manages users</option></select>@error('role')<p class="err">{{ $message }}</p>@enderror</div>
    <div><label class="label" for="password">{{ $editing ? 'New password (leave blank to keep)' : 'Temporary password' }}</label><input id="password" type="password" name="password" autocomplete="new-password" @required(! $editing) class="field"><p class="help">At least 10 characters, with upper- and lower-case letters and a number. The user must change it at next login.</p>@error('password')<p class="err">{{ $message }}</p>@enderror</div>
    <label class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3.5"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="h-4 w-4 rounded border-slate-300 text-brand-600"><span class="text-sm font-semibold">Account is active</span></label>
    <div class="flex gap-3"><button class="btn btn-primary px-8">{{ $editing ? 'Save changes' : 'Create user' }}</button><a href="{{ route('admin.users.index') }}" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Cancel</a></div>
</form>
@endsection
