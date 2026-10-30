<?php

namespace App\Models;

use App\Models\Concerns\Bilingual;
use App\Models\Concerns\BumpsContentCache;
use App\Models\Concerns\HasFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Base class for admin-managed content. Attributes are written only through the
 * validated, whitelisted admin forms, so mass assignment is open on this base.
 */
abstract class ContentModel extends Model
{
    use Bilingual, BumpsContentCache, HasFiles;

    protected $guarded = ['id'];

    /** Columns that hold uploaded files (deleted together with the record). */
    protected array $fileFields = [];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
