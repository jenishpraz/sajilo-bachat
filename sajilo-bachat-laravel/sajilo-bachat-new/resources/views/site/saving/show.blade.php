@extends('layouts.site')
@section('title', $plan->name())
@section('description', $plan->tr('summary') ?: \Illuminate\Support\Str::limit(strip_tags($plan->tr('description')), 160))
@if ($plan->image)@section('og_image', public_url($plan->image))@endif

@section('content')
<x-page-hero :title="$plan->name()" :subtitle="$plan->tr('summary')" :crumbs="[[ui('nav.saving'), route('saving.index')]]" />
<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            @if ($plan->image)<img src="{{ public_url($plan->image) }}" alt="{{ $plan->name() }}" class="w-full rounded-2xl object-cover" decoding="async">@endif
            <x-draft :model="$plan" />
            @if ($plan->tr('description'))<x-prose :text="$plan->tr('description')" class="text-slate-600" />@endif
            @if ($plan->tr('features'))
                <div><h2 class="mb-4 text-xl font-extrabold">{{ ui('saving.features') }}</h2><x-bullets :text="$plan->tr('features')" /></div>
            @endif
            @if ($plan->tr('eligibility'))
                <div><h2 class="mb-4 text-xl font-extrabold">{{ ui('saving.eligibility') }}</h2><x-prose :text="$plan->tr('eligibility')" class="text-slate-600" /></div>
            @endif
        </div>
        <aside class="space-y-6">
            <div class="card p-6">
                <h2 class="mb-4 text-lg font-extrabold">{{ ui('saving.key_facts') }}</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">{{ ui('common.interest_rate') }}</dt><dd class="text-lg font-extrabold text-brand-700">{{ $plan->interest_rate !== null ? num($plan->interest_rate, 2).'% p.a.' : ui('common.as_per_policy') }}</dd></div>
                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">{{ ui('saving.min_deposit') }}</dt><dd class="font-bold">{{ money($plan->min_deposit) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">{{ ui('saving.duration') }}</dt><dd class="text-right font-bold">{{ $plan->tr('duration') ?: '—' }}</dd></div>
                </dl>
                <a href="{{ route('membership.create') }}" class="btn btn-primary mt-6 w-full">{{ ui('nav.member') }}</a>
                <a href="{{ route('calculators') }}#savings" class="btn btn-outline mt-3 w-full"><x-icon name="calculator" class="h-4 w-4" />{{ ui('calc.savings') }}</a>
            </div>
            <p class="rounded-lg bg-amber-50 p-4 text-xs text-amber-900">{{ ui('common.rates_notice') }}</p>
        </aside>
    </div>
    @if ($others->isNotEmpty())
        <div class="container-x mt-16">
            <h2 class="mb-6 text-xl font-extrabold">{{ ui('saving.other_plans') }}</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach ($others as $o)<x-plan-card :item="$o" route="saving.show" kind="saving" />@endforeach</div>
        </div>
    @endif
</section>
@endsection
