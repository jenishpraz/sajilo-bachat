@extends('layouts.admin')
@section('title', 'Application '.$application->reference_no)

@section('content')
<div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-3">
    <div class="card p-6 lg:col-span-2">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div><p class="text-xs font-bold tracking-wider text-slate-500 uppercase">Reference</p><p class="text-2xl font-extrabold text-brand-700">{{ $application->reference_no }}</p></div>
            @include('admin.applications._status', ['status' => $application->status])
        </div>
        <dl class="grid gap-x-8 gap-y-4 sm:grid-cols-2">
            @foreach ([
                'Full name' => $application->full_name,
                'Date of birth' => $application->date_of_birth?->format('d M Y'),
                'Gender' => ucfirst($application->gender),
                'Phone' => $application->phone,
                'Email' => $application->email,
                'Occupation' => $application->occupation,
                'Membership type' => $application->membershipType?->name_en,
                'Submitted' => $application->created_at->format('d M Y, H:i'),
                'Permanent address' => $application->permanent_address,
                'Current address' => $application->current_address,
            ] as $label => $val)
                <div><dt class="text-xs font-bold text-slate-500">{{ $label }}</dt><dd class="mt-0.5 font-semibold">{{ $val ?: '—' }}</dd></div>
            @endforeach
            <div class="sm:col-span-2"><dt class="text-xs font-bold text-slate-500">Message</dt><dd class="mt-0.5 whitespace-pre-line">{{ $application->message ?: '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-bold text-slate-500">Supporting document</dt>
                <dd class="mt-1">@if ($application->document)<a href="{{ route('admin.applications.document', $application) }}" class="btn btn-outline btn-sm"><x-icon name="download" class="h-4 w-4" />Download document</a>@else —@endif</dd></div>
        </dl>
        <p class="mt-6 text-xs text-slate-400">IP address {{ $application->ip_address ?: 'unknown' }}</p>
    </div>

    <div class="space-y-6">
        <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="card space-y-4 p-6">
            @csrf @method('PUT')
            <h2 class="font-extrabold">Review</h2>
            <div><label class="label" for="status">Status</label>
                <select id="status" name="status" class="field">@foreach (\App\Models\MembershipApplication::STATUSES as $k => $l)<option value="{{ $k }}" @selected(old('status', $application->status) === $k)>{{ $l }}</option>@endforeach</select>@error('status')<p class="err">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="admin_note">Internal note</label><textarea id="admin_note" name="admin_note" rows="5" class="field">{{ old('admin_note', $application->admin_note) }}</textarea>@error('admin_note')<p class="err">{{ $message }}</p>@enderror</div>
            <button class="btn btn-primary w-full">Save review</button>
            @if ($application->reviewed_at)<p class="text-xs text-slate-400">Last reviewed {{ $application->reviewed_at->diffForHumans() }}</p>@endif
        </form>
        <a href="{{ route('admin.applications.index') }}" class="btn w-full bg-slate-100 text-slate-700 hover:bg-slate-200">← All applications</a>
    </div>
</div>
@endsection
