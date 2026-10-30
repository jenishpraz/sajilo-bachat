<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Official contact details were not supplied, so they are left blank (hidden on the
        // website) rather than invented. Fill them in at Admin → Website Settings.
        $defaults = [
            'general' => [
                'site_name_en' => 'Sajilo Bachat Savings & Credit Cooperative Ltd.',
                'site_name_ne' => 'सजिलो बचत तथा ऋण सहकारी संस्था लि.',
                'tagline_en' => 'Saving together, growing together',
                'tagline_ne' => 'सँगै बचत, सँगै उन्नति',
            ],
            'footer' => [
                'footer_about_en' => 'A member-owned savings and credit cooperative that encourages the habit of saving and provides fair, accessible credit to its members.',
                'footer_about_ne' => 'सदस्यहरूमा बचतको बानी विकास गर्ने र सदस्यहरूलाई सरल तथा सुलभ ऋण उपलब्ध गराउने सदस्य-स्वामित्वको बचत तथा ऋण सहकारी संस्था।',
                'copyright_en' => 'Sajilo Bachat Savings & Credit Cooperative Ltd. All rights reserved.',
                'copyright_ne' => 'सजिलो बचत तथा ऋण सहकारी संस्था लि. सर्वाधिकार सुरक्षित।',
                'show_draft_badges' => '1',
            ],
            'seo' => [
                'meta_description_en' => 'Sajilo Bachat Savings & Credit Cooperative Ltd. — saving plans, loan services, notices and membership.',
                'meta_description_ne' => 'सजिलो बचत तथा ऋण सहकारी संस्था लि. — बचत योजना, ऋण सेवा, सूचना तथा सदस्यता।',
                'meta_keywords' => 'sajilo bachat, saving and credit cooperative, savings, loan, sahakari, सहकारी',
            ],
        ];

        foreach ($defaults as $group => $items) {
            foreach ($items as $key => $value) {
                // Never overwrite values an administrator has already edited.
                Setting::firstOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
            }
        }
    }
}
