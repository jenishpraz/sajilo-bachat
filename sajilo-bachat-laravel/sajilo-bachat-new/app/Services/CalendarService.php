<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\Event;

/** Merges calendar entries and published events into one list for the digital calendar. */
class CalendarService
{
    /** Items for the JS calendar (from 3 months back to 12 months ahead). */
    public static function items(): array
    {
        $locale = app()->getLocale();

        return ContentCache::remember('calendar.'.$locale, function () {
            $from = now()->subMonths(3)->startOfMonth();
            $to = now()->addMonths(12)->endOfMonth();

            $entries = CalendarEvent::active()
                ->whereDate('event_date', '>=', $from)->whereDate('event_date', '<=', $to)->get()
                ->map(fn ($c) => [
                    'date' => $c->event_date->toDateString(),
                    'end' => $c->end_date?->toDateString(),
                    'title' => $c->tr('title'),
                    'note' => $c->tr('description'),
                    'type' => $c->type,
                    'url' => null,
                ]);

            $events = Event::published()
                ->whereDate('event_date', '>=', $from)->whereDate('event_date', '<=', $to)->get()
                ->map(fn ($e) => [
                    'date' => $e->event_date->toDateString(),
                    'end' => $e->end_date?->toDateString(),
                    'title' => $e->tr('title'),
                    'note' => $e->tr('location'),
                    'type' => 'event',
                    'url' => route('events.show', $e->slug),
                ]);

            return $entries->merge($events)->sortBy('date')->values()->all();
        }, 600);
    }

    /** Next N upcoming items (today onwards). */
    public static function upcoming(int $limit = 5): array
    {
        $today = today()->toDateString();

        return collect(self::items())
            ->filter(fn ($i) => ($i['end'] ?? $i['date']) >= $today)
            ->take($limit)->values()->all();
    }
}
