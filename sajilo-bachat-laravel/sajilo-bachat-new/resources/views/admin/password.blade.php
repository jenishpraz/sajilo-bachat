@extends('layouts.admin')
@section('title', 'Change password')

@section('content')
<form method="POST" action="{{ route('admin.password.update') }}" class="card mx-auto max-w-lg space-y-5 p-6 sm:p-8">
    @csrf @method('PUT')
    @if (auth()->user()->must_change_password)<p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-900">You are using a temporary password. Please choose your own to continue.</p>@endif
    <div><label class="label" for="current_password">Current password</label><input id="current_password" type="password" name="current_password" required autocomplete="current-password" class="field">@error('current_password')<p class="err">{{ $message }}</p>@enderror</div>
    <div><label class="label" for="password">New password</label><input id="password" type="password" name="password" required autocomplete="new-password" class="field"><p class="help">At least 10 characters with upper- and lower-case letters and a number.</p>@error('password')<p class="err">{{ $message }}</p>@enderror</div>
    <div><label class="label" for="password_confirmation">Confirm new password</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="field"></div>
    <button class="btn btn-primary">Update password</button>
</form>
@endsection
