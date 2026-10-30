@extends('layouts.site')
@section('title', ui('nav.contact'))

@section('content')
@php
    $mapEmbed = setting('map_embed_url');
    $address = setting_t('address');
    $mapLink = setting('map_link') ?: ($address ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($address) : null);
@endphp
<x-page-hero :title="ui('nav.contact')" :subtitle="ui('contact.sub')" />
<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-5">
        <div class="space-y-5 lg:col-span-2">
            <div class="card space-y-5 p-6">
                <h2 class="text-lg font-extrabold">{{ ui('contact.info') }}</h2>
                @foreach ([
                    ['map-pin', ui('contact.address'), $address, null],
                    ['phone', ui('contact.phone'), setting('phone'), 'tel:'.preg_replace('/[^0-9+]/', '', (string) setting('phone'))],
                    ['mail', ui('contact.email'), setting('email'), 'mailto:'.setting('email')],
                    ['clock', ui('contact.hours'), setting_t('hours'), null],
                ] as [$icon, $label, $value, $href])
                    @if ($value)
                        <div class="flex gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-brand-700"><x-icon :name="$icon" class="h-5 w-5" /></span>
                            <div><p class="text-xs font-bold text-slate-500">{{ $label }}</p>@if ($href)<a href="{{ $href }}" class="break-all font-semibold hover:text-brand-700">{{ $value }}</a>@else<p class="font-semibold">{{ $value }}</p>@endif</div></div>
                    @endif
                @endforeach
                @if (! $address && ! setting('phone') && ! setting('email') && ! setting_t('hours'))<p class="text-sm text-slate-500">{{ ui('footer.contact_soon') }}</p>@endif
            </div>
            @if (setting_t('contact_note'))<p class="rounded-lg bg-brand-50 p-4 text-sm text-brand-900">{{ setting_t('contact_note') }}</p>@endif
        </div>

        <form method="POST" action="{{ route('contact.store') }}" data-loading="{{ ui('common.submitting') }}" class="card space-y-5 p-6 sm:p-8 lg:col-span-3" novalidate>
            @csrf
            <h2 class="text-lg font-extrabold">{{ ui('contact.form_title') }}</h2>
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert"><ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label class="label" for="name">{{ ui('contact.name') }} *</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name" @class(['field', 'field-error' => $errors->has('name')])></div>
                <div><label class="label" for="email">{{ ui('contact.email') }} *</label><input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="190" autocomplete="email" @class(['field', 'field-error' => $errors->has('email')])></div>
                <div><label class="label" for="phone">{{ ui('contact.phone') }}</label><input id="phone" type="tel" name="phone" value="{{ old('phone') }}" maxlength="20" autocomplete="tel" class="field"></div>
                <div><label class="label" for="subject">{{ ui('contact.subject') }} *</label><input id="subject" name="subject" value="{{ old('subject') }}" required maxlength="190" @class(['field', 'field-error' => $errors->has('subject')])></div>
                <div class="sm:col-span-2"><label class="label" for="message">{{ ui('contact.message') }} *</label><textarea id="message" name="message" rows="6" required minlength="10" maxlength="3000" @class(['field', 'field-error' => $errors->has('message')])>{{ old('message') }}</textarea></div>
            </div>
            <div class="absolute -left-[9999px]" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
            <button type="submit" class="btn btn-primary px-8 py-3"><x-icon name="mail" class="h-4 w-4" />{{ ui('contact.send') }}</button>
        </form>
    </div>

    <div class="container-x mt-12">
        <div class="overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
            @if ($mapEmbed)
                <iframe src="{{ $mapEmbed }}" title="{{ ui('contact.map') }}" class="h-96 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @else
                <div class="flex h-64 flex-col items-center justify-center gap-3 bg-brand-50 p-6 text-center">
                    <x-icon name="map-pin" class="h-10 w-10 text-brand-600" />
                    <p class="text-sm text-slate-600">{{ ui('contact.map_missing') }}</p>
                    @if ($mapLink)<a href="{{ safe_url($mapLink) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">{{ ui('contact.open_maps') }}<x-icon name="external" class="h-4 w-4" /></a>@endif
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
