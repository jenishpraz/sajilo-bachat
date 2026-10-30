<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends ContentModel
{
    protected $table = 'gallery_photos';

    protected array $fileFields = ['image'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
