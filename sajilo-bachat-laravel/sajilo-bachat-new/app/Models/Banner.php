<?php

namespace App\Models;

class Banner extends ContentModel
{
    protected array $fileFields = ['image'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
