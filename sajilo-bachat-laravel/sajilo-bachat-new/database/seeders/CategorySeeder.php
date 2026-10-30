<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count() > 0) {
            return;
        }

        $rows = [
            'notice' => [['General', 'सामान्य'], ['Loan', 'ऋण'], ['Meeting', 'बैठक'], ['Auction', 'लिलाम']],
            'gallery' => [['Programs', 'कार्यक्रम'], ['Meetings', 'बैठक'], ['Office', 'कार्यालय']],
            'video' => [['Programs', 'कार्यक्रम'], ['Awareness', 'जनचेतना']],
            'event' => [['Programs', 'कार्यक्रम'], ['Training', 'तालिम'], ['Meetings', 'बैठक']],
            'download' => [['Membership forms', 'सदस्यता फारम'], ['Loan application forms', 'ऋण निवेदन फारम'], ['Annual reports', 'वार्षिक प्रतिवेदन'], ['Other documents', 'अन्य कागजात']],
            'membership' => [['General member', 'साधारण सदस्य'], ['Group / institutional member', 'समूह / संस्थागत सदस्य']],
        ];

        foreach ($rows as $type => $list) {
            foreach ($list as $i => [$en, $ne]) {
                Category::create(['type' => $type, 'name_en' => $en, 'name_ne' => $ne, 'sort_order' => $i + 1]);
            }
        }
    }
}
