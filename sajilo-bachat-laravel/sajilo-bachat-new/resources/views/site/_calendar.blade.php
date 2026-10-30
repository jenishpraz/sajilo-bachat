@php
    $types = collect(\App\Models\CalendarEvent::TYPES)->map(fn ($v, $k) => ui('calendar.type_'.$k))->all();
@endphp
<div data-calendar data-events="{{ json_encode($calendarItems, JSON_UNESCAPED_UNICODE) }}" data-types="{{ json_encode($types, JSON_UNESCAPED_UNICODE) }}" data-empty="{{ ui('calendar.no_events') }}" class="grid gap-6 lg:grid-cols-3">
    <div class="card p-4 sm:p-6 lg:col-span-2">
        <div class="mb-4 flex items-center justify-between">
            <button type="button" data-cal-prev class="rounded-lg border border-slate-200 p-2 hover:bg-brand-50" aria-label="{{ ui('common.previous') }}"><x-icon name="chevron-left" class="h-5 w-5" /></button>
            <div class="text-center"><h3 data-cal-title class="text-lg font-extrabold"></h3><button type="button" data-cal-today class="text-xs font-bold text-brand-700 hover:underline">{{ ui('calendar.today') }}</button></div>
            <button type="button" data-cal-next class="rounded-lg border border-slate-200 p-2 hover:bg-brand-50" aria-label="{{ ui('common.next') }}"><x-icon name="chevron-right" class="h-5 w-5" /></button>
        </div>
        <div data-cal-grid class="grid grid-cols-7 gap-1"></div>
    </div>
    <div class="space-y-5">
        <div class="card p-5">
            <h3 data-cal-day class="mb-3 text-sm font-extrabold text-brand-800"></h3>
            <ul data-cal-list class="space-y-2"></ul>
        </div>
        <div class="card p-5">
            <h3 class="mb-3 flex items-center gap-2 text-sm font-extrabold"><x-icon name="calendar" class="h-4 w-4 text-brand-600" />{{ ui('calendar.upcoming') }}</h3>
            @forelse ($upcoming as $u)
                <div class="flex gap-3 border-t border-slate-100 py-2.5 first:border-0">
                    <span class="w-20 shrink-0 text-xs font-bold text-brand-700">{{ fdate($u['date']) }}</span>
                    <span class="text-sm font-semibold">@if ($u['url'])<a class="hover:text-brand-700" href="{{ $u['url'] }}">{{ $u['title'] }}</a>@else{{ $u['title'] }}@endif</span>
                </div>
            @empty
                <p class="text-sm text-slate-500">{{ ui('calendar.none_upcoming') }}</p>
            @endforelse
        </div>
    </div>
</div>
