<section class="section">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.services')" :title="ui('home.plans_title')" :subtitle="ui('home.plans_sub')" :href="route('saving.index')" :cta="ui('common.view_all')" />
        @if ($plans->isEmpty())
            <x-empty-state icon="savings" :title="ui('saving.none')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($plans as $p)<div class="reveal"><x-plan-card :item="$p" route="saving.show" kind="saving" /></div>@endforeach
            </div>
        @endif
    </div>
</section>
