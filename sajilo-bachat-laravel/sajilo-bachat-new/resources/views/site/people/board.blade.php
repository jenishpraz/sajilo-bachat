@extends('layouts.site')
@section('title', ui('nav.board'))

@section('content')
<x-page-hero :title="ui('nav.board')" :subtitle="ui('people.board_sub')" :crumbs="[[ui('nav.hr'), null]]" />

<section class="section">
    <div class="container-x space-y-14">
        @forelse ($sections as $key => $people)
            <div>
                <div class="mb-6 flex items-center gap-4">
                    <h2 class="text-xl font-extrabold sm:text-2xl">{{ \App\Support\Committee::label($key) }}</h2>
                    <span class="h-px flex-1 bg-brand-200"></span>
                    <span class="badge badge-green">{{ num($people->count()) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($people as $p)<x-person-card :person="$p" />@endforeach
                </div>
            </div>
        @empty
            <x-empty-state icon="users" :title="ui('people.none')" />
        @endforelse
    </div>
</section>
@endsection
