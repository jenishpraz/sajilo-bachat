<section class="section section-alt">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.services')" :title="ui('home.loans_title')" :subtitle="ui('home.loans_sub')" :href="route('loans.index')" :cta="ui('common.view_all')" />
        @if ($loans->isEmpty())
            <x-empty-state icon="loan" :title="ui('loans.none')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($loans as $l)<div class="reveal"><x-plan-card :item="$l" route="loans.show" kind="loan" /></div>@endforeach
            </div>
        @endif
    </div>
</section>
