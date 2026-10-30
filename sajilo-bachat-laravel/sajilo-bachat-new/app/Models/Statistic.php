<?php

namespace App\Models;

class Statistic extends ContentModel
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_draft' => 'boolean'];
    }
}
