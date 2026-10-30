@extends('layouts.admin')
@section('title', 'Message')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <div class="card p-6 sm:p-8">
        <h2 class="text-xl font-extrabold">{{ $message->subject }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $message->created_at->format('d M Y, H:i') }}</p>
        <dl class="mt-5 grid gap-4 border-y border-slate-100 py-5 sm:grid-cols-3">
            <div><dt class="text-xs font-bold text-slate-500">Name</dt><dd class="font-semibold">{{ $message->name }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-500">Email</dt><dd><a class="font-semibold text-brand-700 break-all" href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd></div>
            <div><dt class="text-xs font-bold text-slate-500">Phone</dt><dd class="font-semibold">{{ $message->phone ?: '—' }}</dd></div>
        </dl>
        <p class="mt-5 whitespace-pre-line leading-relaxed">{{ $message->message }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}" class="btn btn-primary"><x-icon name="mail" class="h-4 w-4" />Reply by email</a>
        <form method="POST" action="{{ route('admin.messages.toggle', $message) }}">@csrf<button class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Mark as unread</button></form>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Delete this message permanently?">@csrf @method('DELETE')<button class="btn btn-danger">Delete</button></form>
        <a href="{{ route('admin.messages.index') }}" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">← All messages</a>
    </div>
</div>
@endsection
