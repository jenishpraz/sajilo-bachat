<?php

namespace App\Models\Concerns;

use App\Services\ContentCache;

/** Any change to the model invalidates cached public content. */
trait BumpsContentCache
{
    public static function bootBumpsContentCache(): void
    {
        $bump = fn () => ContentCache::bump();

        static::saved($bump);
        static::deleted($bump);
    }
}
