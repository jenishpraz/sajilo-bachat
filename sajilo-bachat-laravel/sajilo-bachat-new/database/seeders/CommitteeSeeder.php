<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Download;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Imports the supplied committee list (PDF), the portrait library (ZIP) and
 * publishes the PDF in Downloads. Safe to run repeatedly.
 */
class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Portrait library: every supplied photo, cropped of its red frame.
        $libraryDir = public_path('people/library');
        File::ensureDirectoryExists($libraryDir);
        foreach (File::files(database_path('seeders/assets/team/library')) as $file) {
            $target = 'people/library/'.$file->getFilename();
            if (! File::exists(public_path($target))) {
                File::copy($file->getPathname(), public_path($target));
            }
        }

        // 2. Committee profiles (names and designations from the PDF).
        if (Person::where('group', Person::BOARD)->count() === 0) {
            $rows = require database_path('seeders/data/committee.php');
            $photoMap = require database_path('seeders/data/photo_map.php');

            foreach ($rows as $i => [$ref, $section, $nameNe, $nameEn, $desigNe, $desigEn]) {
                $photo = isset($photoMap[$ref]) ? 'people/library/'.basename($photoMap[$ref]) : null;

                Person::create([
                    'group' => Person::BOARD,
                    'section' => $section,
                    'name_en' => $nameEn,
                    'name_ne' => $nameNe,
                    'designation_en' => $desigEn,
                    'designation_ne' => $desigNe,
                    'photo' => $photo && File::exists(public_path($photo)) ? $photo : null,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]);
            }
        }

        // 3. Publish the supplied PDF under Downloads.
        $pdf = 'downloads/committee-subcommittee-members.pdf';
        if (! File::exists(public_path($pdf))) {
            File::ensureDirectoryExists(public_path('downloads'));
            File::copy(database_path('seeders/assets/docs/committee-subcommittee-members.pdf'), public_path($pdf));
        }
        if (! Download::where('file', $pdf)->exists()) {
            Download::create([
                'category_id' => Category::where('type', 'download')->where('name_en', 'Other documents')->value('id'),
                'title_en' => 'Committee & Sub-committee Members',
                'title_ne' => 'समिति तथा उपसमिति सदस्यहरूको विवरण',
                'description_en' => 'List of the board, committee and sub-committee members.',
                'description_ne' => 'सञ्चालक समिति, समिति तथा उपसमिति सदस्यहरूको विवरण।',
                'file' => $pdf,
                'sort_order' => 1,
                'is_active' => true,
            ]);
        }
    }
}
