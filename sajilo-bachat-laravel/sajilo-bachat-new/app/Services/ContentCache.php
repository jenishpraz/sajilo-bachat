<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Version-stamped cache. Every admin save/delete bumps the version, which
 * instantly invalidates all cached public content (works on any cache driver,
 * including `database` and `file`, which do not support tags).
 */
class ContentCache
{
    private const VERSION_KEY = 'sajilo.content_version';

    public static function version(): int
    {
        return (int) Cache::rememberForever(self::VERSION_KEY, fn () => 1);
    }

    public static function bump(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
    }

    public static function remember(string $key, Closure $callback, int $ttlSeconds = 3600): mixed
    {
        return Cache::remember('sajilo.v'.self::version().'.'.$key, $ttlSeconds, $callback);
    }
}
