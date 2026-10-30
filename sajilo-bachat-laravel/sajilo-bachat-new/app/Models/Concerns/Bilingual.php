<?php

namespace App\Models\Concerns;

/** Locale-aware access to `<field>_en` / `<field>_ne` column pairs. */
trait Bilingual
{
    public function tr(string $field): ?string
    {
        $en = $this->getAttribute($field.'_en');
        $ne = $this->getAttribute($field.'_ne');

        return app()->getLocale() === 'ne' ? ($ne ?: $en) : ($en ?: $ne);
    }
}
