@extends('layouts.site')
@section('title', ui('nav.'.str_replace('people.', '', $titleKey)))

@section('content')
<x-page-hero :title="ui('nav.'.str_replace('people.', '', $titleKey))" :crumbs="[[ui('nav.hr'), null]]" />

<section class="section">
    <div class="container-x">
        @if ($people->isEmpty())
            <x-empty-state icon="users" :title="ui('people.none')" :text="ui('people.none_hint')" />
        @else
            <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($people as $p)<x-person-card :person="$p" />@endforeach
            </div>
            {{ $people->links() }}
        @endif
    </div>
</section>
@endsection
