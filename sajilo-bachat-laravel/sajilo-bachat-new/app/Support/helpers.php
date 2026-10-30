<?php

use App\Services\MediaService;
use App\Services\SettingsService;
use App\Services\UiTextService;
use Carbon\Carbon;
use Carbon\CarbonInterface;

if (! function_exists('setting')) {
    /** Read a website setting (cached). */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingsService::class)->get($key, $default);
    }
}

if (! function_exists('setting_t')) {
    /** Read a bilingual website setting using the `<base>_en` / `<base>_ne` pair. */
    function setting_t(string $base, mixed $default = null): mixed
    {
        return app(SettingsService::class)->trans($base, $default);
    }
}

if (! function_exists('ui')) {
    /** Translate an interface string (lang files, overridable from the admin). */
    function ui(string $key, array $replace = []): string
    {
        return app(UiTextService::class)->get($key, $replace);
    }
}

if (! function_exists('is_ne')) {
    function is_ne(): bool
    {
        return app()->getLocale() === 'ne';
    }
}

if (! function_exists('devanagari_digits')) {
    function devanagari_digits(string $value): string
    {
        return strtr($value, ['0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४',
            '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९']);
    }
}

if (! function_exists('num')) {
    /** Format a number for display; Nepali locale shows Devanagari digits. */
    function num(int|float|string|null $value, int $decimals = 0): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        // Values such as "1,200+" or "25" are shown as typed (digits converted only).
        if (! is_numeric($value)) {
            return is_ne() ? devanagari_digits((string) $value) : (string) $value;
        }
        $s = number_format((float) $value, $decimals);

        return is_ne() ? devanagari_digits($s) : $s;
    }
}

if (! function_exists('money')) {
    /** Rupee amount with Nepali/Indian lakh-crore digit grouping, e.g. Rs. 12,50,000. */
    function money(int|float|string|null $amount, int $decimals = 0): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }
        $negative = (float) $amount < 0;
        $fixed = number_format(abs((float) $amount), $decimals, '.', '');
        [$int, $frac] = array_pad(explode('.', $fixed), 2, null);

        if (strlen($int) > 3) {
            $last3 = substr($int, -3);
            $rest = substr($int, 0, -3);
            $int = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest).','.$last3;
        }

        $out = ($negative ? '-' : '').(is_ne() ? 'रू ' : 'Rs. ').$int.($frac !== null ? '.'.$frac : '');

        return is_ne() ? devanagari_digits($out) : $out;
    }
}

if (! function_exists('fdate')) {
    /** Locale-aware date, e.g. "12 Jan 2026" / "१२ जनवरी २०२६". */
    function fdate(CarbonInterface|string|null $date, bool $withTime = false): string
    {
        if (! $date) {
            return '';
        }
        $d = $date instanceof CarbonInterface ? $date : Carbon::parse($date);

        if (is_ne()) {
            $months = ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन', 'जुलाई', 'अगस्ट', 'सेप्टेम्बर', 'अक्टोबर', 'नोभेम्बर', 'डिसेम्बर'];
            $s = $d->day.' '.$months[$d->month - 1].' '.$d->year.($withTime ? ', '.$d->format('H:i') : '');

            return devanagari_digits($s);
        }

        return $d->format($withTime ? 'd M Y, H:i' : 'd M Y');
    }
}

if (! function_exists('public_url')) {
    function public_url(?string $path): ?string
    {
        return MediaService::url($path);
    }
}

if (! function_exists('storage_url')) {
    function storage_url(?string $path): ?string
    {
        return public_url($path);
    }
}

if (! function_exists('lines')) {
    /** Split a textarea into non-empty trimmed lines. */
    function lines(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\R/', $text)), fn ($l) => $l !== ''));
    }
}

if (! function_exists('video_embed_url')) {
    /** Convert a YouTube / Vimeo link into an embeddable URL (null when unsupported). */
    function video_embed_url(?string $url): ?string
    {
        if (! $url) {
            return null;
        }
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1];
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return null;
    }
}

if (! function_exists('video_thumbnail')) {
    function video_thumbnail(?string $url): ?string
    {
        if ($url && preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m)) {
            return 'https://i.ytimg.com/vi/'.$m[1].'/hqdefault.jpg';
        }

        return null;
    }
}

if (! function_exists('safe_url')) {
    /** Only allow http(s) URLs or site-relative paths in admin-supplied links. */
    function safe_url(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '#';
        }
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        return preg_match('~^https?://~i', $url) ? $url : '#';
    }
}

if (! function_exists('site_logo')) {
    function site_logo(): string
    {
        return public_url(setting('logo')) ?: asset('images/logo-default.svg');
    }
}

if (! function_exists('site_favicon')) {
    function site_favicon(): string
    {
        return public_url(setting('favicon')) ?: asset('images/favicon.svg');
    }
}

if (! function_exists('initials')) {
    function initials(?string $name): string
    {
        $parts = preg_split('/\s+/u', trim((string) $name));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

        return mb_strtoupper($first.$last);
    }
}
