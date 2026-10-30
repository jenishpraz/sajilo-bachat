@if ($employees->isNotEmpty())
<section class="section section-alt">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.hr')" :title="ui('home.staff_title')" :href="route('people.employees')" :cta="ui('common.view_all')" />
        <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($employees as $p)<div class="reveal"><x-person-card :person="$p" /></div>@endforeach
        </div>
    </div>
</section>
@endif
