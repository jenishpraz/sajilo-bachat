@extends('layouts.site')
@section('title', ui('membership.thanks_title'))

@section('content')
<section class="section">
    <div class="container-x max-w-2xl text-center">
        <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-brand-100 text-brand-600"><x-icon name="check-circle" class="h-11 w-11" /></span>
        <h1 class="mt-6 text-3xl font-extrabold">{{ ui('membership.thanks_title') }}</h1>
        <p class="mt-3 text-slate-600">{{ ui('membership.thanks_text') }}</p>
        <div class="card mx-auto mt-8 max-w-md p-6">
            <p class="text-xs font-bold tracking-wider text-slate-500 uppercase">{{ ui('membership.reference') }}</p>
            <p class="mt-1 select-all text-3xl font-extrabold tracking-wider text-brand-700">{{ $reference }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ ui('membership.reference_hint') }}</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary mt-8">{{ ui('common.back_home') }}</a>
    </div>
</section>
@endsection
