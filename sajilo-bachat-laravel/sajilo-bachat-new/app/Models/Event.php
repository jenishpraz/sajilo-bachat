<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends ContentModel
{
    use HasSlug;

    protected array $fileFields = ['image'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'event_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where(fn ($w) => $w->whereDate('event_date', '>=', today())
            ->orWhereDate('end_date', '>=', today()));
    }
}
