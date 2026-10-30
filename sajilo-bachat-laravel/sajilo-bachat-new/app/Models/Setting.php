<?php

namespace App\Models;

use App\Models\Concerns\BumpsContentCache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BumpsContentCache;

    protected $fillable = ['key', 'value', 'group'];

    public static function put(string $key, ?string $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
