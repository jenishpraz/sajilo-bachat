@extends('layouts.site')
@section('title', $loan->name())
@section('description', $loan->tr('summary') ?: \Illuminate\Support\Str::limit(strip_tags($loan->tr('description')), 160))
@if ($loan->image)@section('og_image', public_url($loan->image))@endif

@section('content')
<x-page-hero :title="$loan->name()" :subtitle="$loan->tr('summary')" :crumbs="[[ui('nav.loans'), route('loans.index')]]" />
<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            @if ($loan->image)<img src="{{ public_url($loan->image) }}" alt="{{ $loan->name() }}" class="w-full rounded-2xl object-cover" decoding="async">@endif
            <x-draft :model="$loan" />
            @if ($loan->tr('description'))<x-prose :text="$loan->tr('description')" class="text-slate-600" />@endif
            @if ($loan->tr('eligibility'))<div><h2 class="mb-4 text-xl font-extrabold">{{ ui('loans.eligibility') }}</h2><x-bullets :text="$loan->tr('eligibility')" /></div>@endif
            @if ($loan->tr('documents'))<div><h2 class="mb-4 text-xl font-extrabold">{{ ui('loans.documents') }}</h2><x-bullets :text="$loan->tr('documents')" icon="file" /></div>@endif
            @if ($loan->tr('terms'))<div><h2 class="mb-4 text-xl font-extrabold">{{ ui('loans.terms') }}</h2><x-bullets :text="$loan->tr('terms')" icon="info" /></div>@endif
        </div>
        <aside class="space-y-6">
            <div class="card p-6">
                <h2 class="mb-4 text-lg font-extrabold">{{ ui('saving.key_facts') }}</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">{{ ui('common.interest_rate') }}</dt><dd class="text-lg font-extrabold text-brand-700">{{ $loan->interest_rate !== null ? num($loan->interest_rate, 2).'% p.a.' : ui('common.as_per_policy') }}</dd></div>
                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">{{ ui('loans.min_amount') }}</dt><dd class="font-bold">{{ money($loan->min_amount) }}</dd></div>
                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">{{ ui('loans.max_amount') }}</dt><dd class="font-bold">{{ money($loan->max_amount) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">{{ ui('loans.duration') }}</dt><dd class="text-right font-bold">{{ $loan->tr('duration') ?: '—' }}</dd></div>
                </dl>
                <a href="{{ route('calculators') }}#emi" class="btn btn-primary mt-6 w-full"><x-icon name="calculator" class="h-4 w-4" />{{ ui('calc.emi') }}</a>
                <a href="{{ route('downloads') }}" class="btn btn-outline mt-3 w-full"><x-icon name="download" class="h-4 w-4" />{{ ui('loans.get_form') }}</a>
            </div>
            <p class="rounded-lg bg-amber-50 p-4 text-xs text-amber-900">{{ ui('common.rates_notice') }}</p>
        </aside>
    </div>
    @if ($others->isNotEmpty())
        <div class="container-x mt-16">
            <h2 class="mb-6 text-xl font-extrabold">{{ ui('loans.other') }}</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach ($others as $o)<x-plan-card :item="$o" route="loans.show" kind="loan" />@endforeach</div>
        </div>
    @endif
</section>
@endsection
