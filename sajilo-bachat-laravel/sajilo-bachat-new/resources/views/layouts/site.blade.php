@php
    $siteName = setting_t('site_name', 'Sajilo Bachat');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' | '.$siteName : $siteName.' — '.setting_t('tagline', '');
    $metaDescription = trim($__env->yieldContent('description')) ?: setting_t('meta_description', setting_t('footer_about', ''));
    $ogImage = trim($__env->yieldContent('og_image')) ?: (public_url(setting('og_image')) ?: site_logo());
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-pt-28">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 180) }}">
    @if (setting('meta_keywords'))<meta name="keywords" content="{{ setting('meta_keywords') }}">@endif
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle ?: $siteName }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 200) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="{{ is_ne() ? 'ne_NP' : 'en_US' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#1f7a43">
    <link rel="icon" href="{{ site_favicon() }}">
    @vite(['resources/css/app.css', 'resources/js/site.js'])
    @stack('head')
</head>
<body class="flex min-h-screen flex-col">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[200] focus:rounded focus:bg-white focus:px-4 focus:py-2 focus:shadow">{{ ui('common.skip') }}</a>

    @include('partials.site.topbar')
    @include('partials.site.header')

    <main id="main" class="flex-1">
        @if (session('success'))
            <div data-toast class="fixed inset-x-4 top-4 z-[150] mx-auto flex max-w-md items-start gap-3 rounded-xl border border-brand-200 bg-white p-4 shadow-xl" role="status">
                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                <p class="text-sm font-medium text-ink">{{ session('success') }}</p>
            </div>
        @endif
        @yield('content')
    </main>

    @include('partials.site.footer')

    <button type="button" data-back-top class="hidden fixed bottom-5 right-5 z-40 rounded-full bg-brand-600 p-3 text-white shadow-lg hover:bg-brand-700" aria-label="{{ ui('common.back_to_top') }}">
        <x-icon name="chevron-down" class="h-5 w-5 rotate-180" />
    </button>
    @stack('scripts')
</body>
</html>
