<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\LoanService;
use App\Models\SavingPlan;

class ServiceController extends Controller
{
    public function savingIndex()
    {
        return view('site.saving.index', ['plans' => SavingPlan::active()->ordered()->get()]);
    }

    public function savingShow(string $slug)
    {
        $plan = SavingPlan::active()->where('slug', $slug)->firstOrFail();
        $others = SavingPlan::active()->whereKeyNot($plan->id)->ordered()->take(3)->get();

        return view('site.saving.show', compact('plan', 'others'));
    }

    public function loanIndex()
    {
        return view('site.loans.index', ['loans' => LoanService::active()->ordered()->get()]);
    }

    public function loanShow(string $slug)
    {
        $loan = LoanService::active()->where('slug', $slug)->firstOrFail();
        $others = LoanService::active()->whereKeyNot($loan->id)->ordered()->take(3)->get();

        return view('site.loans.show', compact('loan', 'others'));
    }
}
