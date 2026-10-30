<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    /** @var array<string,?string>|null */
    private ?array $loaded = null;

    /** @return array<string,?string> */
    public function all(): array
    {
        if ($this->loaded === null) {
            $this->loaded = ContentCache::remember('settings', function () {
                return Setting::query()->pluck('value', 'key')->all();
            }, 86400);
        }

        return $this->loaded;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->all()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /** Locale-aware read of a `<base>_en` / `<base>_ne` pair. */
    public function trans(string $base, mixed $default = null): mixed
    {
        $en = $this->get($base.'_en');
        $ne = $this->get($base.'_ne');
        $value = app()->getLocale() === 'ne' ? ($ne ?: $en) : ($en ?: $ne);

        return $value ?: $default;
    }

    public function flush(): void
    {
        $this->loaded = null;
    }
}
