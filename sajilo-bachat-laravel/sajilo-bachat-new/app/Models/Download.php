<?php

namespace App\Models;

use App\Services\MediaService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class Download extends ContentModel
{
    protected array $fileFields = ['file'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function extension(): string
    {
        return strtoupper(pathinfo((string) $this->file, PATHINFO_EXTENSION) ?: 'FILE');
    }

    public function sizeLabel(): ?string
    {
        $path = MediaService::absolutePath($this->file);
        if (! $path || ! File::isFile($path)) {
            return null;
        }
        $bytes = File::size($path);

        return $bytes >= 1048576 ? round($bytes / 1048576, 1).' MB' : max(1, round($bytes / 1024)).' KB';
    }
}
