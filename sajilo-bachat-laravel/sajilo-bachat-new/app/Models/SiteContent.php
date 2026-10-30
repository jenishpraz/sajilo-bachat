<?php

namespace App\Models;

class SiteContent extends ContentModel
{
    protected $table = 'site_contents';

    protected array $fileFields = ['image'];

    /** Fetch every content block keyed by its key (cached by callers). */
    public static function keyed()
    {
        return static::query()->get()->keyBy('key');
    }
}
