<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ListItem;
use App\Models\Notice;
use App\Models\SiteContent;
use App\Models\Statistic;
use Illuminate\Database\Seeder;

/**
 * Starter content. Nothing here is an official claim: texts are generic drafts and every
 * figure is flagged "sample" so it is visibly replaceable from the admin panel.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            'about_intro' => [
                'About Sajilo Bachat', 'सजिलो बचतको परिचय',
                "Sajilo Bachat Savings & Credit Cooperative Ltd. is a member-owned cooperative that brings people together to save regularly and to borrow responsibly at fair terms.\n\nOwned and governed by its members, the cooperative works to make saving easy, credit accessible and financial services transparent for every household it serves.",
                "सजिलो बचत तथा ऋण सहकारी संस्था लि. सदस्यहरूको स्वामित्वमा सञ्चालित सहकारी संस्था हो, जसले मानिसहरूलाई नियमित बचत गर्न र उचित सर्तमा जिम्मेवारीपूर्वक ऋण लिन प्रोत्साहन गर्दछ।\n\nसदस्यहरूद्वारा नै स्वामित्व र सञ्चालन गरिने यस सहकारीले बचतलाई सजिलो, ऋणलाई सुलभ र आर्थिक सेवालाई पारदर्शी बनाउन काम गर्दछ।",
            ],
            'history' => [
                'Our Background', 'हाम्रो पृष्ठभूमि',
                "Our history and milestones will be published here.\n\n(Draft text — administrators can replace it from Admin → About Us.)",
                "हाम्रो इतिहास तथा उपलब्धिका कुराहरू यहाँ प्रकाशित गरिनेछन्।\n\n(मस्यौदा पाठ — प्रशासकले एडमिन → हाम्रो बारेमा बाट परिवर्तन गर्न सक्नुहुन्छ।)",
            ],
            'vision' => [
                'Vision', 'दूरदृष्टि',
                'A financially secure and empowered community built on saving, cooperation and mutual trust.',
                'बचत, सहकार्य र आपसी विश्वासमा आधारित आर्थिक रूपमा सुरक्षित र सशक्त समुदायको निर्माण।',
            ],
            'mission' => [
                'Mission', 'ध्येय',
                'To provide reliable saving and credit services to our members through transparent, member-centred and professional cooperative practice.',
                'पारदर्शी, सदस्यमुखी र व्यावसायिक सहकारी अभ्यासमार्फत सदस्यहरूलाई भरपर्दो बचत तथा ऋण सेवा उपलब्ध गराउने।',
            ],
            'org_info' => [
                'Organizational Information', 'संस्थागत जानकारी',
                "Name: Sajilo Bachat Savings & Credit Cooperative Ltd.\nType: Savings and credit cooperative\nRegistration details: to be added by the administrator",
                "नाम: सजिलो बचत तथा ऋण सहकारी संस्था लि.\nप्रकार: बचत तथा ऋण सहकारी संस्था\nदर्ता विवरण: प्रशासकले थप्नेछन्",
            ],
        ];

        foreach ($blocks as $key => [$tEn, $tNe, $bEn, $bNe]) {
            SiteContent::firstOrCreate(['key' => $key], ['title_en' => $tEn, 'title_ne' => $tNe, 'body_en' => $bEn, 'body_ne' => $bNe]);
        }

        if (ListItem::count() === 0) {
            $objectives = [
                ['Encourage the habit of regular saving among members.', 'सदस्यहरूमा नियमित बचतको बानी विकास गर्ने।', 'savings'],
                ['Provide affordable and timely credit for members\' needs.', 'सदस्यहरूको आवश्यकता अनुसार सुलभ र समयमै ऋण उपलब्ध गराउने।', 'loan'],
                ['Support the economic and social development of members.', 'सदस्यहरूको आर्थिक तथा सामाजिक विकासमा सहयोग गर्ने।', 'users'],
                ['Practise transparency, accountability and good governance.', 'पारदर्शिता, जवाफदेहिता र सुशासनको अभ्यास गर्ने।', 'shield'],
            ];
            foreach ($objectives as $i => [$en, $ne, $icon]) {
                ListItem::create(['type' => ListItem::OBJECTIVE, 'title_en' => $en, 'title_ne' => $ne, 'icon' => $icon, 'sort_order' => $i + 1]);
            }

            $values = [
                ['Transparency', 'पारदर्शिता', 'Open and honest dealings with every member.', 'हरेक सदस्यसँग खुला र इमानदार व्यवहार।', 'eye'],
                ['Trust', 'विश्वास', 'Safeguarding member savings with care.', 'सदस्यको बचतको सावधानीपूर्वक सुरक्षा।', 'shield'],
                ['Cooperation', 'सहकार्य', 'Growing stronger by working together.', 'सँगै काम गरेर अझ बलियो बन्दै जाने।', 'handshake'],
                ['Service', 'सेवा', 'Courteous, prompt and member-first service.', 'सदस्यलाई प्राथमिकता दिने विनम्र र छिटो सेवा।', 'heart'],
                ['Inclusion', 'समावेशिता', 'Financial services open to all members of the community.', 'समुदायका सबै सदस्यका लागि खुला आर्थिक सेवा।', 'users'],
            ];
            foreach ($values as $i => [$tEn, $tNe, $dEn, $dNe, $icon]) {
                ListItem::create(['type' => ListItem::VALUE, 'title_en' => $tEn, 'title_ne' => $tNe, 'description_en' => $dEn, 'description_ne' => $dNe, 'icon' => $icon, 'sort_order' => $i + 1]);
            }
        }

        if (Statistic::count() === 0) {
            // SAMPLE figures (is_draft = true) — replace with official numbers.
            $stats = [
                ['Active Members', 'सक्रिय सदस्य', '1000', null, '+', 'users'],
                ['Total Savings', 'कुल बचत', '10', 'Rs.', ' Crore', 'savings'],
                ['Loan Investment', 'कुल ऋण लगानी', '8', 'Rs.', ' Crore', 'loan'],
                ['Share Capital', 'शेयर पुँजी', '2', 'Rs.', ' Crore', 'chart'],
                ['Years of Service', 'सेवाका वर्ष', '10', null, '+', 'award'],
                ['Branches', 'शाखा', '1', null, null, 'building'],
            ];
            foreach ($stats as $i => [$lEn, $lNe, $value, $prefix, $suffix, $icon]) {
                Statistic::create([
                    'label_en' => $lEn, 'label_ne' => $lNe, 'value' => $value, 'prefix' => $prefix,
                    'suffix' => $suffix, 'icon' => $icon, 'sort_order' => $i + 1, 'is_draft' => true,
                ]);
            }
        }

        if (Notice::count() === 0) {
            Notice::create([
                'type' => Notice::GENERAL,
                'category_id' => Category::where('type', 'notice')->where('name_en', 'General')->value('id'),
                'title_en' => 'Welcome to the new Sajilo Bachat website (sample notice)',
                'title_ne' => 'सजिलो बचतको नयाँ वेबसाइटमा स्वागत छ (नमूना सूचना)',
                'body_en' => "This is a sample notice so the Notices section is not empty.\nAdministrators can edit or delete it from Admin → Notices.",
                'body_ne' => "यो नमूना सूचना हो, ताकि सूचना खण्ड खाली नदेखियोस्।\nप्रशासकले एडमिन → सूचना बाट यसलाई सम्पादन वा हटाउन सक्नुहुन्छ।",
                'published_at' => now(),
                'is_published' => true,
            ]);
        }
    }
}
