@extends('layouts.site')
@section('title', ui('nav.about'))
@section('description', \Illuminate\Support\Str::limit(strip_tags($content['about_intro']->body_en ?? ''), 160))

@section('content')
<x-page-hero :title="ui('nav.about')" :subtitle="setting_t('tagline')" />

@php $intro = $content['about_intro'] ?? null; $history = $content['history'] ?? null; $vision = $content['vision'] ?? null; $mission = $content['mission'] ?? null; $org = $content['org_info'] ?? null; @endphp

<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-3">
        <div class="space-y-12 lg:col-span-2">
            @if ($intro)
                <article>
                    <h2 class="section-title mb-4">{{ $intro->tr('title') }}</h2>
                    @if ($intro->image)<img src="{{ public_url($intro->image) }}" alt="" class="mb-6 w-full rounded-2xl object-cover" loading="lazy">@endif
                    <x-prose :text="$intro->tr('body')" class="text-slate-600" />
                </article>
            @endif
            @if ($history)
                <article>
                    <h2 class="section-title mb-4">{{ $history->tr('title') ?: ui('about.history') }}</h2>
                    <x-prose :text="$history->tr('body')" class="text-slate-600" />
                </article>
            @endif

            <div class="grid gap-6 md:grid-cols-2">
                @foreach ([[$vision, 'eye', 'about.vision'], [$mission, 'target', 'about.mission']] as [$block, $icon, $label])
                    @if ($block)
                        <div class="card p-6">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon :name="$icon" class="h-6 w-6" /></span>
                            <h3 class="mt-4 text-xl font-extrabold">{{ ui($label) }}</h3>
                            <p class="mt-2 text-slate-600">{{ $block->tr('body') }}</p>
                        </div>
                    @endif
                @endforeach
            </div>

            @if ($objectives->isNotEmpty())
                <div>
                    <h2 class="section-title mb-5">{{ ui('about.objectives') }}</h2>
                    <ol class="space-y-3">
                        @foreach ($objectives as $i => $o)
                            <li class="card flex gap-4 p-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-extrabold text-white">{{ num($i + 1) }}</span>
                                <div><p class="font-bold">{{ $o->tr('title') }}</p>@if ($o->tr('description'))<p class="mt-0.5 text-sm text-slate-600">{{ $o->tr('description') }}</p>@endif</div></li>
                        @endforeach
                    </ol>
                </div>
            @endif

            @if ($values->isNotEmpty())
                <div>
                    <h2 class="section-title mb-5">{{ ui('about.values') }}</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($values as $v)
                            <div class="card p-5">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 text-brand-700"><x-icon :name="$v->icon ?: 'star'" class="h-5 w-5" /></span>
                                <h3 class="mt-3 font-extrabold">{{ $v->tr('title') }}</h3>
                                @if ($v->tr('description'))<p class="mt-1 text-sm text-slate-600">{{ $v->tr('description') }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <h2 class="section-title mb-5">{{ ui('about.achievements') }}</h2>
                @forelse ($achievements as $a)
                    <div class="card mb-3 flex gap-4 p-4"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gold-400/30 text-amber-700"><x-icon :name="$a->icon ?: 'award'" class="h-5 w-5" /></span>
                        <div><p class="font-bold">{{ $a->tr('title') }}</p>@if ($a->tr('description'))<p class="text-sm text-slate-600">{{ $a->tr('description') }}</p>@endif</div></div>
                @empty
                    <p class="rounded-xl bg-slate-50 p-6 text-sm text-slate-500">{{ ui('about.achievements_none') }}</p>
                @endforelse
            </div>
        </div>

        <aside class="space-y-6">
            @if ($org)
                <div class="card p-6">
                    <h2 class="mb-3 text-lg font-extrabold">{{ $org->tr('title') ?: ui('about.org_info') }}</h2>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @foreach (lines($org->tr('body')) as $line)<li class="border-b border-slate-100 pb-2 last:border-0">{{ $line }}</li>@endforeach
                    </ul>
                    @if (setting('registration_no'))<p class="mt-3 text-sm"><span class="font-bold">{{ ui('about.reg_no') }}:</span> {{ setting('registration_no') }}</p>@endif
                </div>
            @endif
            @if ($stats->isNotEmpty())
                <div class="card p-6">
                    <h2 class="mb-4 text-lg font-extrabold">{{ ui('home.stats') }}</h2>
                    <dl class="space-y-3">
                        @foreach ($stats as $s)
                            <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2 last:border-0"><dt class="text-sm text-slate-600">{{ $s->tr('label') }} <x-draft :model="$s" /></dt>
                                <dd class="font-extrabold text-brand-800">{{ $s->prefix }} {{ num($s->value) }}{{ $s->suffix }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            @endif
            <div class="rounded-2xl bg-brand-700 p-6 text-white"><h2 class="text-lg font-extrabold !text-white">{{ ui('home.cta_title') }}</h2>
                <a href="{{ route('membership.create') }}" class="btn btn-accent mt-4">{{ ui('nav.member') }}</a></div>
        </aside>
    </div>
</section>
@endsection
