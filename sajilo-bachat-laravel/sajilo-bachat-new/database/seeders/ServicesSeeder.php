<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\LoanService;
use App\Models\SavingPlan;
use Illuminate\Database\Seeder;

/**
 * Sample saving plans, loans and a head-office entry. Every record is flagged
 * is_draft so the site shows a "sample" tag until the real terms are entered.
 */
class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        if (SavingPlan::count() === 0) {
            $plans = [
                ['Regular Savings', 'नियमित बचत', 'Flexible savings you can add to at any time.', 'जुनसुकै बेला थप्न मिल्ने लचिलो बचत।', 5.00, 100, 'No fixed term', 'निश्चित अवधि छैन', 'savings',
                    "Deposit any time\nWithdraw as per cooperative rules\nEarns interest on balance", "जुनसुकै बेला जम्मा गर्न सकिने\nसहकारीको नियम अनुसार झिक्न सकिने\nमौज्दातमा ब्याज प्राप्त हुने"],
                ['Recurring Savings', 'आवधिक बचत', 'Save a fixed amount every month toward a goal.', 'लक्ष्यका लागि हरेक महिना निश्चित रकम बचत।', 7.00, 500, '12 – 60 months', '१२ – ६० महिना', 'target',
                    "Fixed monthly deposit\nBuilds a habit of saving\nLump sum on maturity", "निश्चित मासिक जम्मा\nबचतको बानी बसाल्छ\nअवधि पूरा भएपछि एकमुष्ट रकम"],
                ['Fixed Deposit', 'मुद्दती निक्षेप', 'Higher return for money you can set aside for a set term.', 'निश्चित अवधिका लागि राख्न सकिने रकममा उच्च प्रतिफल।', 9.00, 10000, '1 – 5 years', '१ – ५ वर्ष', 'shield',
                    "Higher interest rate\nFixed term\nInterest paid as per agreement", "उच्च ब्याजदर\nनिश्चित अवधि\nसम्झौता अनुसार ब्याज भुक्तानी"],
            ];
            foreach ($plans as $i => [$nEn, $nNe, $sEn, $sNe, $rate, $min, $dEn, $dNe, $icon, $fEn, $fNe]) {
                SavingPlan::create([
                    'name_en' => $nEn, 'name_ne' => $nNe, 'summary_en' => $sEn, 'summary_ne' => $sNe,
                    'description_en' => $sEn."\n\nSAMPLE PLAN — rate, minimum deposit and terms are placeholders. Replace them with the cooperative's approved policy in Admin → Saving Plans.",
                    'description_ne' => $sNe."\n\nनमूना योजना — ब्याजदर, न्यूनतम जम्मा र सर्तहरू नमूना मात्र हुन्। एडमिन → बचत योजना मा सहकारीको स्वीकृत नीति अनुसार परिवर्तन गर्नुहोस्।",
                    'interest_rate' => $rate, 'min_deposit' => $min, 'duration_en' => $dEn, 'duration_ne' => $dNe,
                    'eligibility_en' => 'Open to members of the cooperative.', 'eligibility_ne' => 'सहकारीका सदस्यहरूका लागि खुला।',
                    'features_en' => $fEn, 'features_ne' => $fNe, 'icon' => $icon, 'sort_order' => $i + 1, 'is_draft' => true,
                ]);
            }
        }

        if (LoanService::count() === 0) {
            $loans = [
                ['Personal Loan', 'व्यक्तिगत ऋण', 'For members\' personal and household needs.', 'सदस्यका व्यक्तिगत तथा घरायसी आवश्यकताका लागि।', 14.00, 10000, 500000, 'Up to 3 years', '३ वर्षसम्म', 'home'],
                ['Business Loan', 'व्यवसायिक ऋण', 'Capital for starting or growing a small business.', 'साना व्यवसाय सुरु वा विस्तार गर्न पुँजी।', 13.00, 50000, 2000000, 'Up to 5 years', '५ वर्षसम्म', 'business'],
                ['Education Loan', 'शैक्षिक ऋण', 'Support for members\' or their children\'s education.', 'सदस्य वा उनीहरूका सन्तानको शिक्षाका लागि सहयोग।', 12.00, 10000, 1000000, 'Up to 5 years', '५ वर्षसम्म', 'education'],
                ['Loan Against Savings', 'बचत धितो ऋण', 'Quick credit secured against a member\'s own savings.', 'सदस्यको आफ्नै बचतको धितोमा छिटो ऋण।', 10.00, 5000, 500000, 'Up to 2 years', '२ वर्षसम्म', 'savings'],
            ];
            foreach ($loans as $i => [$nEn, $nNe, $sEn, $sNe, $rate, $min, $max, $dEn, $dNe, $icon]) {
                LoanService::create([
                    'name_en' => $nEn, 'name_ne' => $nNe, 'summary_en' => $sEn, 'summary_ne' => $sNe,
                    'description_en' => $sEn."\n\nSAMPLE LOAN — rate, limits and conditions are placeholders. Replace them with the cooperative's approved policy in Admin → Loan Services.",
                    'description_ne' => $sNe."\n\nनमूना ऋण — ब्याजदर, सीमा र सर्तहरू नमूना मात्र हुन्। एडमिन → ऋण सेवा मा सहकारीको स्वीकृत नीति अनुसार परिवर्तन गर्नुहोस्।",
                    'interest_rate' => $rate, 'min_amount' => $min, 'max_amount' => $max,
                    'duration_en' => $dEn, 'duration_ne' => $dNe,
                    'eligibility_en' => "Active member of the cooperative\nRegular savings record\nAbility to repay", 'eligibility_ne' => "सहकारीको सक्रिय सदस्य\nनियमित बचतको रेकर्ड\nतिर्न सक्ने क्षमता",
                    'documents_en' => "Completed loan application form\nCitizenship certificate (copy)\nPassport-size photographs\nCollateral / guarantor documents as required", 'documents_ne' => "भरिएको ऋण निवेदन फारम\nनागरिकताको प्रतिलिपि\nपासपोर्ट साइजका फोटो\nआवश्यकता अनुसार धितो / जमानीका कागजात",
                    'terms_en' => "Repayment as per the approved schedule\nInterest is charged on the outstanding balance\nTerms are subject to the cooperative's policy and may change", 'terms_ne' => "स्वीकृत तालिका अनुसार भुक्तानी\nबाँकी रकममा ब्याज लाग्ने\nसर्तहरू सहकारीको नीति अनुसार परिवर्तन हुन सक्ने",
                    'icon' => $icon, 'sort_order' => $i + 1, 'is_draft' => true,
                ]);
            }
        }

        if (Branch::count() === 0) {
            Branch::create([
                'name_en' => 'Head Office', 'name_ne' => 'प्रधान कार्यालय',
                'address_en' => 'Address to be updated by the administrator', 'address_ne' => 'ठेगाना प्रशासकले अद्यावधिक गर्नेछन्',
                'is_head_office' => true, 'sort_order' => 1, 'is_draft' => true,
            ]);
        }
    }
}
