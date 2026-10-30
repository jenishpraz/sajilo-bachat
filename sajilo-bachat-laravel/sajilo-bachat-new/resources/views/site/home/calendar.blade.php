<section class="section section-alt">
    <div class="container-x">
        <x-section-head :eyebrow="ui('nav.calendar')" :title="ui('home.calendar_title')" :href="route('calendar')" :cta="ui('common.open_full')" />
        @include('site._calendar')
    </div>
</section>
