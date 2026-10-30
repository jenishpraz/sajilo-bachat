<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** Generates a unique, stable, SEO-friendly slug from $slugSource (default title_en). */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (! empty($model->slug)) {
                return;
            }

            $source = $model->slugSource ?? 'title_en';
            $base = Str::slug((string) $model->getAttribute($source)) ?: Str::lower(Str::random(8));
            $slug = $base;
            $i = 2;

            while (static::query()
                ->where('slug', $slug)
                ->when($model->exists, fn ($q) => $q->whereKeyNot($model->getKey()))
                ->exists()) {
                $slug = $base.'-'.$i++;
            }

            $model->slug = $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
