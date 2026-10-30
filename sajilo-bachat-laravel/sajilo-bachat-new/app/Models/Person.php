<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Person extends ContentModel
{
    public const BOARD = 'board';

    public const EMPLOYEE = 'employee';

    public const FORMER_COMMITTEE = 'former_committee';

    public const FORMER_EMPLOYEE = 'former_employee';

    protected $table = 'people';

    protected array $fileFields = ['photo'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeInGroup(Builder $q, string $group): Builder
    {
        return $q->where('group', $group);
    }

    public function name(): string
    {
        return (string) $this->tr('name');
    }
}
