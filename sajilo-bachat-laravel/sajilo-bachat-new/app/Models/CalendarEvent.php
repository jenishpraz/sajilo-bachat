<?php

namespace App\Models;

class CalendarEvent extends ContentModel
{
    protected $table = 'calendar_events';

    public const TYPES = ['event' => 'Event', 'meeting' => 'Meeting', 'holiday' => 'Holiday / office closed', 'deadline' => 'Deadline'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'event_date' => 'date', 'end_date' => 'date'];
    }
}
