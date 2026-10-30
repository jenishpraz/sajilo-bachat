<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ListItem extends ContentModel
{
    public const OBJECTIVE = 'objective';

    public const VALUE = 'core_value';

    public const ACHIEVEMENT = 'achievement';

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }
}
