<?php

namespace App\Support;

/**
 * Committee / sub-committee sections taken from the supplied
 * "committee & sub-committee members" PDF. Order = display order.
 */
class Committee
{
    public const SECTIONS = [
        'advisors' => ['en' => 'Advisors', 'ne' => 'सल्लाहकार'],
        'board' => ['en' => 'Board of Directors', 'ne' => 'सञ्चालक समिति'],
        'accounts' => ['en' => 'Accounts Committee', 'ne' => 'लेखा समिति'],
        'loan' => ['en' => 'Loan Sub-committee', 'ne' => 'ऋण उपसमिति'],
        'women' => ['en' => 'Women Sub-committee', 'ne' => 'महिला उपसमिति'],
        'expansion' => ['en' => 'Expansion Sub-committee', 'ne' => 'बिस्तार उपसमिति'],
        'education' => ['en' => 'Education Sub-committee', 'ne' => 'शिक्षा उपसमिति'],
        'publicity' => ['en' => 'Publicity Sub-committee', 'ne' => 'प्रचार प्रसार उपसमिति'],
        'youth' => ['en' => 'Youth Sub-committee', 'ne' => 'युवा उपसमिति'],
    ];

    public static function label(string $key): string
    {
        $s = self::SECTIONS[$key] ?? null;

        if (! $s) {
            return $key;
        }

        return app()->getLocale() === 'ne' ? $s['ne'] : $s['en'];
    }

    /** Options for the admin select box: key => "English / नेपाली". */
    public static function options(): array
    {
        $out = [];
        foreach (self::SECTIONS as $k => $s) {
            $out[$k] = $s['en'].' / '.$s['ne'];
        }

        return $out;
    }
}
