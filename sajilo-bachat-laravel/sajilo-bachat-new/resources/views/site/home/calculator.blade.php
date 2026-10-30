@php
    $loanRates = $loans->whereNotNull('interest_rate')->map(fn ($l) => ['name' => $l->name(), 'rate' => (float) $l->interest_rate])->values();
    $savingRates = $plans->whereNotNull('interest_rate')->map(fn ($p) => ['name' => $p->name(), 'rate' => (float) $p->interest_rate])->values();
@endphp
<section class="section" id="calculator">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.calculators')" :title="ui('home.calc_title')" :subtitle="ui('home.calc_sub')" :href="route('calculators')" :cta="ui('common.open_full')" />
        @include('site._calculator')
    </div>
</section>
