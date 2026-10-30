<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/** Applies database/seeders/data/photo_map.php to the seeded committee profiles. */
class CommitteePhotoSeeder extends Seeder
{
    public function run(): void
    {
        $map = require database_path('seeders/data/photo_map.php');
        $rows = require database_path('seeders/data/committee.php');
        $applied = 0;

        foreach ($rows as $i => [$ref, , $nameNe]) {
            $file = $map[$ref] ?? null;
            $path = $file ? 'people/library/'.basename($file) : null;

            if (! $path || ! File::isFile(public_path($path))) {
                continue;
            }

            // Rows were seeded in file order, so sort_order = index + 1 identifies them.
            Person::where('group', Person::BOARD)->where('sort_order', $i + 1)->where('name_ne', $nameNe)
                ->update(['photo' => $path]);
            $applied++;
        }

        $this->command?->info("Applied {$applied} portrait assignment(s).");
    }
}
