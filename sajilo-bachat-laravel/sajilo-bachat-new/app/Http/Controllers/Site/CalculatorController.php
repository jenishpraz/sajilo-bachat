<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\LoanService;
use App\Models\SavingPlan;

class CalculatorController extends Controller
{
    public function __invoke()
    {
        // Active plans feed the interest-rate pickers in the calculators.
        $loanRates = LoanService::active()->whereNotNull('interest_rate')->ordered()->get()
            ->map(fn ($l) => ['name' => $l->name(), 'rate' => (float) $l->interest_rate])->values();
        $savingRates = SavingPlan::active()->whereNotNull('interest_rate')->ordered()->get()
            ->map(fn ($p) => ['name' => $p->name(), 'rate' => (float) $p->interest_rate])->values();

        return view('site.calculators', compact('loanRates', 'savingRates'));
    }
}
