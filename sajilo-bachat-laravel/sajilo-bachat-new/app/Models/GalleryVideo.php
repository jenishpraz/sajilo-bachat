<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryVideo extends ContentModel
{
    protected $table = 'gallery_videos';

    protected array $fileFields = ['thumbnail'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function thumb(): ?string
    {
        return $this->fileUrl('thumbnail') ?: video_thumbnail($this->url);
    }
}
