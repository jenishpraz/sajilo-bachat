@extends('layouts.admin')
@section('title', 'Membership applications')

@section('content')
<div class="space-y-5">
    <form method="GET" class="card grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-6">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Name, reference, phone, email…" class="field lg:col-span-2" aria-label="Search">
        <select name="status" class="field" aria-label="Status"><option value="">All statuses</option>@foreach (\App\Models\MembershipApplication::STATUSES as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach</select>
        <select name="type" class="field" aria-label="Membership type"><option value="">All types</option>@foreach ($types as $t)<option value="{{ $t->id }}" @selected(request('type') == $t->id)>{{ $t->name_en }}</option>@endforeach</select>
        <input type="date" name="from" value="{{ request('from') }}" class="field" aria-label="From date" title="From date">
        <input type="date" name="to" value="{{ request('to') }}" class="field" aria-label="To date" title="To date">
        <div class="flex gap-2 sm:col-span-2 lg:col-span-6"><button class="btn btn-primary"><x-icon name="search" class="h-4 w-4" />Filter</button>@if (request()->query())<a href="{{ route('admin.applications.index') }}" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Clear</a>@endif</div>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[44rem]">
                <thead class="bg-slate-50"><tr><th class="th">Reference</th><th class="th">Applicant</th><th class="th">Phone</th><th class="th">Type</th><th class="th">Status</th><th class="th">Submitted</th><th class="th text-right">Actions</th></tr></thead>
                <tbody>
                    @forelse ($applications as $a)
                        <tr class="border-t border-slate-100 hover:bg-slate-50/70">
                            <td class="td font-bold text-brand-700"><a href="{{ route('admin.applications.show', $a) }}">{{ $a->reference_no }}</a></td>
                            <td class="td"><span class="font-semibold">{{ $a->full_name }}</span><span class="block text-xs text-slate-500">{{ $a->email }}</span></td>
                            <td class="td">{{ $a->phone }}</td>
                            <td class="td">{{ $a->membershipType?->name_en ?? '—' }}</td>
                            <td class="td">@include('admin.applications._status', ['status' => $a->status])</td>
                            <td class="td text-slate-500">{{ $a->created_at->format('d M Y, H:i') }}</td>
                            <td class="td"><div class="flex justify-end gap-1.5">
                                <a href="{{ route('admin.applications.show', $a) }}" class="btn btn-sm bg-slate-100 text-slate-700 hover:bg-slate-200"><x-icon name="eye" class="h-4 w-4" />View</a>
                                <form method="POST" action="{{ route('admin.applications.destroy', $a) }}" data-confirm="Delete application {{ $a->reference_no }} and its uploaded document?">@csrf @method('DELETE')<button class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100" aria-label="Delete"><x-icon name="trash" class="h-4 w-4" /></button></form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-14 text-center text-slate-400">No applications found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($applications->hasPages())<div class="border-t border-slate-100 px-4 pb-6">{{ $applications->links() }}</div>@endif
    </div>
</div>
@endsection
