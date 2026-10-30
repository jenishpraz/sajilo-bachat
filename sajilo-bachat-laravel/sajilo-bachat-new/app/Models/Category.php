<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Category extends ContentModel
{
    public const TYPES = [
        'notice' => 'Notice',
        'gallery' => 'Photo gallery',
        'video' => 'Video gallery',
        'event' => 'Events & activities',
        'download' => 'Downloads',
        'membership' => 'Membership types',
    ];

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type)->orderBy('sort_order')->orderBy('id');
    }

    public function name(): string
    {
        return (string) $this->tr('name');
    }
}
