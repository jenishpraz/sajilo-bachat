@props(['title', 'subtitle' => null, 'crumbs' => []])
<section class="relative overflow-hidden bg-brand-800 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_20%,rgba(242,184,75,.22),transparent_45%),linear-gradient(135deg,#123f26,#1f7a43)]"></div>
    <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full border-[28px] border-white/5"></div>
    <div class="container-x relative py-12 sm:py-16">
        <nav aria-label="Breadcrumb" class="mb-4 text-xs text-brand-100">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="hover:text-white">{{ ui('nav.home') }}</a></li>
                @foreach ($crumbs as [$label, $url])
                    <li class="flex items-center gap-1.5"><x-icon name="chevron-right" class="h-3 w-3" />@if ($url)<a href="{{ $url }}" class="hover:text-white">{{ $label }}</a>@else<span>{{ $label }}</span>@endif</li>
                @endforeach
                <li class="flex items-center gap-1.5" aria-current="page"><x-icon name="chevron-right" class="h-3 w-3" /><span class="text-white">{{ $title }}</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl font-extrabold !text-white sm:text-4xl">{{ $title }}</h1>
        @if ($subtitle)<p class="mt-3 max-w-2xl text-brand-100">{{ $subtitle }}</p>@endif
    </div>
</section>
