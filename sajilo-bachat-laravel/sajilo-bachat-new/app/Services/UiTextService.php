<?php

namespace App\Services;

use App\Models\UiText;

/**
 * Interface strings. Defaults live in lang/{en,ne}/site.php; the admin can
 * override any of them ("Language & Website Content") and the override is
 * stored in the ui_texts table.
 */
class UiTextService
{
    /** @var array<string,array{en:?string,ne:?string}>|null */
    private ?array $overrides = null;

    private function overrides(): array
    {
        if ($this->overrides === null) {
            $this->overrides = ContentCache::remember('ui_texts', function () {
                $out = [];
                foreach (UiText::all(['key', 'en', 'ne']) as $row) {
                    $out[$row->key] = ['en' => $row->en, 'ne' => $row->ne];
                }

                return $out;
            }, 86400);
        }

        return $this->overrides;
    }

    public function get(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $override = $this->overrides()[$key][$locale] ?? null;

        if ($override !== null && $override !== '') {
            foreach ($replace as $k => $v) {
                $override = str_replace(':'.$k, (string) $v, $override);
            }

            return $override;
        }

        return __('site.'.$key, $replace, $locale);
    }

    /** Flattened list of every default key => string for a locale. */
    public function defaults(string $locale): array
    {
        $file = lang_path($locale.'/site.php');
        $data = is_file($file) ? require $file : [];

        return self::flatten($data);
    }

    public static function flatten(array $data, string $prefix = ''): array
    {
        $out = [];
        foreach ($data as $k => $v) {
            $key = $prefix === '' ? (string) $k : $prefix.'.'.$k;
            if (is_array($v)) {
                $out += self::flatten($v, $key);
            } else {
                $out[$key] = (string) $v;
            }
        }

        return $out;
    }
}
