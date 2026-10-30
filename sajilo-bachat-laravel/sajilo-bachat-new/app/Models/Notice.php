<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends ContentModel
{
    use HasSlug;

    public const GENERAL = 'general';

    public const AUCTION = 'auction';

    protected array $fileFields = ['attachment'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_important' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Published and already past its publication date (may be expired). */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /** Published and not yet expired. */
    public function scopeCurrent(Builder $q): Builder
    {
        return $q->published()
            ->where(fn ($w) => $w->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function title(): string
    {
        return (string) $this->tr('title');
    }
}
