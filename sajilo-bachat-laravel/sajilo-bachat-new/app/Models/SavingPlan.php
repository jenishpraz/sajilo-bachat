<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;

class SavingPlan extends ContentModel
{
    use HasSlug;

    protected string $slugSource = 'name_en';

    protected array $fileFields = ['image'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_draft' => 'boolean',
            'interest_rate' => 'decimal:2',
            'min_deposit' => 'decimal:2',
        ];
    }

    public function name(): string
    {
        return (string) $this->tr('name');
    }
}
