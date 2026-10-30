@props(['model'])
@if (($model->is_draft ?? false) && setting('show_draft_badges', '1') !== '0')
    <span class="badge badge-gold" title="{{ ui('common.sample_hint') }}">{{ ui('common.sample') }}</span>
@endif
