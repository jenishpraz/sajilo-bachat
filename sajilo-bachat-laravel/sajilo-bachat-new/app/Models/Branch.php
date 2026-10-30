<?php

namespace App\Models;

class Branch extends ContentModel
{
    protected array $fileFields = ['image'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_head_office' => 'boolean', 'is_draft' => 'boolean'];
    }
}
