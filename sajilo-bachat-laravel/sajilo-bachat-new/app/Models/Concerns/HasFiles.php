<?php

namespace App\Models\Concerns;

use App\Services\MediaService;

/**
 * Removes uploaded files from storage when a record is deleted, so no orphaned
 * images/documents are left behind. Models list their columns in $fileFields.
 */
trait HasFiles
{
    public static function bootHasFiles(): void
    {
        static::deleting(function ($model) {
            foreach ($model->fileFields ?? [] as $field) {
                MediaService::delete($model->getAttribute($field), $model->fileDisk ?? 'public');
            }
        });
    }

    public function fileUrl(string $field): ?string
    {
        return MediaService::url($this->getAttribute($field));
    }
}
