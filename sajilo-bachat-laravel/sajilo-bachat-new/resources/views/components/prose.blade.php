@props(['text'])
@php $paras = preg_split('/\R{2,}/', trim((string) $text)); @endphp
<div {{ $attributes->merge(['class' => 'prose-sb']) }}>
    @foreach ($paras as $p)@if (trim($p) !== '')<p>{!! nl2br(e(trim($p))) !!}</p>@endif @endforeach
</div>
