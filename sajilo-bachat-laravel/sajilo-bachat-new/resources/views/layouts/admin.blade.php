@php
    $nav = \App\Support\AdminResources::navigation();
    $badges = [
        'applications' => \App\Models\MembershipApplication::where('status', 'pending')->count(),
        'messages' => \App\Models\ContactMessage::where('is_read', false)->count(),
    ];
    $role = auth()->user()->role;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') — {{ setting('site_name_en', 'Sajilo Bachat') }} Admin</title>
    <link rel="icon" href="{{ site_favicon() }}">
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="bg-slate-100">
<div data-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden" data-sidebar-toggle></div>

<aside data-sidebar class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-brand-900 transition-transform lg:translate-x-0">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 border-b border-white/10 px-5 py-4">
        <img src="{{ site_logo() }}" alt="" class="h-10 w-10 rounded-full bg-white/95 object-contain p-0.5">
        <span class="text-sm font-extrabold leading-tight text-white">{{ setting('site_name_en', 'Sajilo Bachat') }}<span class="block text-[10px] font-semibold tracking-widest text-gold-400 uppercase">Admin panel</span></span>
    </a>
    <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4" aria-label="Admin">
        @foreach ($nav as $group)
            @php $items = collect($group['items'])->filter(fn ($i) => empty($i['roles']) || in_array($role, $i['roles'])); @endphp
            @if ($items->isNotEmpty())
                <div>
                    @if ($group['title'])<p class="mb-1.5 px-3 text-[10px] font-bold tracking-widest text-brand-300 uppercase">{{ $group['title'] }}</p>@endif
                    <ul class="space-y-0.5">
                        @foreach ($items as $item)
                            <li><a href="{{ route($item['route'][0], $item['route'][1]) }}" @class(['admin-link', 'active' => request()->is($item['match'])])>
                                <x-icon :name="$item['icon']" class="h-4 w-4 shrink-0" /><span class="flex-1">{{ $item['label'] }}</span>
                                @if (! empty($item['badge']) && ($badges[$item['badge']] ?? 0) > 0)<span class="rounded-full bg-accent-500 px-1.5 text-[10px] font-bold text-white">{{ $badges[$item['badge']] }}</span>@endif
                            </a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    </nav>
</aside>

<div class="lg:pl-64">
    <header class="sticky top-0 z-20 flex items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex items-center gap-3">
            <button type="button" data-sidebar-toggle class="rounded-lg border border-slate-200 p-2 lg:hidden" aria-label="Menu"><x-icon name="menu" class="h-5 w-5" /></button>
            <h1 class="text-lg font-extrabold">@yield('title', 'Dashboard')</h1>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm"><x-icon name="external" class="h-4 w-4" /><span class="hidden sm:inline">View website</span></a>
            <span class="hidden text-slate-500 md:inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-sm bg-slate-100 text-slate-700 hover:bg-slate-200"><x-icon name="logout" class="h-4 w-4" /><span class="hidden sm:inline">Log out</span></button></form>
        </div>
    </header>

    <main class="p-4 sm:p-6">
        <div class="pointer-events-none fixed right-4 top-20 z-50 w-full max-w-sm space-y-2">
            @foreach (['success' => 'border-brand-300 text-brand-900', 'error' => 'border-red-300 text-red-800', 'warning' => 'border-amber-300 text-amber-900'] as $type => $cls)
                @if (session($type))<div data-toast class="pointer-events-auto rounded-xl border bg-white p-4 text-sm font-medium shadow-lg {{ $cls }}" role="status">{{ session($type) }}</div>@endif
            @endforeach
        </div>
        @yield('content')
    </main>
</div>

<div data-confirm-dialog class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4" role="alertdialog" aria-modal="true">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600"><x-icon name="alert" class="h-6 w-6" /></span>
        <h2 class="mt-4 text-lg font-extrabold">Are you sure?</h2>
        <p data-confirm-text class="mt-1 text-sm text-slate-600"></p>
        <div class="mt-6 flex justify-end gap-2">
            <button type="button" data-confirm-no class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Cancel</button>
            <button type="button" data-confirm-yes class="btn btn-danger">Yes, continue</button>
        </div>
    </div>
</div>
</body>
</html>
