@extends('layouts.site')
@section('title', ui('nav.member'))
@section('description', ui('membership.sub'))

@section('content')
<x-page-hero :title="ui('nav.member')" :subtitle="ui('membership.sub')" />
<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-3">
        <form method="POST" action="{{ route('membership.store') }}" enctype="multipart/form-data" data-loading="{{ ui('common.submitting') }}" class="card space-y-6 p-6 sm:p-8 lg:col-span-2" novalidate>
            @csrf
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <p class="font-bold">{{ ui('common.fix_errors') }}</p>
                    <ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="label" for="full_name">{{ ui('membership.full_name') }} <span class="text-accent-500">*</span></label>
                    <input id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="150" autocomplete="name" @class(['field', 'field-error' => $errors->has('full_name')])>
                    @error('full_name')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="date_of_birth">{{ ui('membership.dob') }} <span class="text-accent-500">*</span></label>
                    <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required max="{{ now()->subDay()->toDateString() }}" @class(['field', 'field-error' => $errors->has('date_of_birth')])>
                    @error('date_of_birth')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="gender">{{ ui('membership.gender') }} <span class="text-accent-500">*</span></label>
                    <select id="gender" name="gender" required @class(['field', 'field-error' => $errors->has('gender')])>
                        <option value="">{{ ui('common.select') }}</option>
                        @foreach (['male', 'female', 'other'] as $g)<option value="{{ $g }}" @selected(old('gender') === $g)>{{ ui('membership.gender_'.$g) }}</option>@endforeach
                    </select>
                    @error('gender')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="phone">{{ ui('membership.phone') }} <span class="text-accent-500">*</span></label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="20" autocomplete="tel" inputmode="tel" @class(['field', 'field-error' => $errors->has('phone')])>
                    @error('phone')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="email">{{ ui('membership.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="190" autocomplete="email" @class(['field', 'field-error' => $errors->has('email')])>
                    @error('email')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="permanent_address">{{ ui('membership.permanent_address') }} <span class="text-accent-500">*</span></label>
                    <input id="permanent_address" name="permanent_address" value="{{ old('permanent_address') }}" required maxlength="255" @class(['field', 'field-error' => $errors->has('permanent_address')])>
                    @error('permanent_address')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="current_address">{{ ui('membership.current_address') }}</label>
                    <input id="current_address" name="current_address" value="{{ old('current_address') }}" maxlength="255" class="field">
                </div>
                <div>
                    <label class="label" for="occupation">{{ ui('membership.occupation') }}</label>
                    <input id="occupation" name="occupation" value="{{ old('occupation') }}" maxlength="150" class="field">
                </div>
                <div>
                    <label class="label" for="membership_type_id">{{ ui('membership.type') }}</label>
                    <select id="membership_type_id" name="membership_type_id" class="field">
                        <option value="">{{ ui('common.select') }}</option>
                        @foreach ($types as $t)<option value="{{ $t->id }}" @selected(old('membership_type_id') == $t->id)>{{ $t->name() }}</option>@endforeach
                    </select>
                    @error('membership_type_id')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="message">{{ ui('membership.message') }}</label>
                    <textarea id="message" name="message" rows="4" maxlength="2000" class="field">{{ old('message') }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="document">{{ ui('membership.document') }}</label>
                    <input id="document" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" @class(['field file:mr-3 file:rounded-md file:border-0 file:bg-brand-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-800', 'field-error' => $errors->has('document')])>
                    <p class="help">{{ ui('membership.document_help') }}</p>
                    @error('document')<p class="err">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Honeypot: hidden from people, tempting for bots --}}
            <div class="absolute -left-[9999px]" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

            <label class="flex items-start gap-3 text-sm">
                <input type="checkbox" name="consent" value="1" required @checked(old('consent')) class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                <span>{{ ui('membership.consent') }}</span>
            </label>
            @error('consent')<p class="err">{{ $message }}</p>@enderror

            <button type="submit" class="btn btn-primary px-8 py-3">{{ ui('membership.submit') }}<x-icon name="arrow-right" class="h-4 w-4" /></button>
        </form>

        <aside class="space-y-6">
            <div class="card p-6">
                <h2 class="mb-4 text-lg font-extrabold">{{ ui('membership.how') }}</h2>
                <ol class="space-y-4 text-sm">
                    @foreach (['step1', 'step2', 'step3'] as $i => $s)
                        <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs font-extrabold text-white">{{ num($i + 1) }}</span><span class="pt-0.5 text-slate-600">{{ ui('membership.'.$s) }}</span></li>
                    @endforeach
                </ol>
            </div>
            <p class="flex gap-2 rounded-lg bg-amber-50 p-4 text-xs text-amber-900"><x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0" />{{ ui('membership.not_account') }}</p>
            <a href="{{ route('downloads') }}" class="btn btn-outline w-full"><x-icon name="download" class="h-4 w-4" />{{ ui('membership.download_form') }}</a>
        </aside>
    </div>
</section>
@endsection
