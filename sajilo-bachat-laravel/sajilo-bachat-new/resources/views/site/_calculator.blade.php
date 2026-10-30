@php
    $labels = [
        'month' => ui('calc.month'), 'instalment' => ui('calc.instalment'), 'principal' => ui('calc.principal'), 'interest' => ui('calc.interest'), 'balance' => ui('calc.balance'),
        'monthlyEmi' => ui('calc.monthly_emi'), 'totalInterest' => ui('calc.total_interest'), 'totalPayment' => ui('calc.total_payment'),
        'firstInstalment' => ui('calc.first_instalment'), 'lastInstalment' => ui('calc.last_instalment'), 'principalPerMonth' => ui('calc.principal_per_month'),
        'maturityAmount' => ui('calc.maturity_amount'), 'totalDeposited' => ui('calc.total_deposited'), 'interestEarned' => ui('calc.interest_earned'),
        'customRate' => ui('calc.custom_rate'), 'monthlyDeposit' => ui('calc.monthly_deposit'), 'depositAmount' => ui('calc.deposit_amount'),
    ];
    $tabs = ['emi' => ['calculator', 'calc.emi'], 'epi' => ['chart', 'calc.epi'], 'savings' => ['savings', 'calc.savings']];
@endphp
<div data-calculators data-labels="{{ json_encode($labels, JSON_UNESCAPED_UNICODE) }}" data-loan-rates="{{ json_encode($loanRates, JSON_UNESCAPED_UNICODE) }}" data-saving-rates="{{ json_encode($savingRates, JSON_UNESCAPED_UNICODE) }}" class="card overflow-hidden">
    <div role="tablist" class="flex flex-wrap gap-2 border-b border-slate-200 bg-slate-50 p-3">
        @foreach ($tabs as $key => [$icon, $label])
            <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="false" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-bold text-slate-600 transition hover:bg-white">
                <x-icon :name="$icon" class="h-4 w-4" />{{ ui($label) }}
            </button>
        @endforeach
    </div>

    @foreach (['emi', 'epi', 'savings'] as $kind)
        <form data-panel="{{ $kind }}" class="hidden grid gap-8 p-5 sm:p-8 lg:grid-cols-5" novalidate onsubmit="return false">
            <div class="space-y-4 lg:col-span-2">
                <p class="text-sm text-slate-600">{{ ui('calc.'.$kind.'_help') }}</p>
                @if ($kind === 'savings')
                    <div>
                        <label class="label" for="{{ $kind }}-mode">{{ ui('calc.deposit_type') }}</label>
                        <select id="{{ $kind }}-mode" name="mode" class="field">
                            <option value="recurring">{{ ui('calc.recurring') }}</option>
                            <option value="lump">{{ ui('calc.lump') }}</option>
                        </select>
                    </div>
                @endif
                <div>
                    <label class="label" for="{{ $kind }}-amount"><span data-amount-label>{{ $kind === 'savings' ? ui('calc.deposit_amount') : ui('calc.loan_amount') }}</span> ({{ is_ne() ? 'रू' : 'Rs.' }})</label>
                    <input id="{{ $kind }}-amount" name="amount" type="number" inputmode="decimal" min="1" step="any" value="{{ $kind === 'savings' ? 5000 : 500000 }}" class="field">
                </div>
                <div>
                    <label class="label" for="{{ $kind }}-preset">{{ $kind === 'savings' ? ui('calc.pick_saving_rate') : ui('calc.pick_loan_rate') }}</label>
                    <select id="{{ $kind }}-preset" name="preset" class="field"></select>
                </div>
                <div>
                    <label class="label" for="{{ $kind }}-rate">{{ ui('calc.rate') }} (% p.a.)</label>
                    <input id="{{ $kind }}-rate" name="rate" type="number" inputmode="decimal" min="0" max="100" step="0.01" value="{{ $kind === 'savings' ? 7 : 12 }}" class="field">
                </div>
                <div class="grid grid-cols-5 gap-3">
                    <div class="col-span-3">
                        <label class="label" for="{{ $kind }}-duration">{{ ui('calc.duration') }}</label>
                        <input id="{{ $kind }}-duration" name="duration" type="number" inputmode="numeric" min="1" step="1" value="{{ $kind === 'savings' ? 2 : 3 }}" class="field">
                    </div>
                    <div class="col-span-2">
                        <label class="label" for="{{ $kind }}-unit">&nbsp;</label>
                        <select id="{{ $kind }}-unit" name="unit" class="field">
                            <option value="years">{{ ui('calc.years') }}</option>
                            <option value="months">{{ ui('calc.months') }}</option>
                        </select>
                    </div>
                </div>
                @if ($kind === 'savings')
                    <div data-comp class="hidden">
                        <label class="label" for="{{ $kind }}-comp">{{ ui('calc.compounding') }}</label>
                        <select id="{{ $kind }}-comp" name="compounding" class="field">
                            <option value="quarterly">{{ ui('calc.quarterly') }}</option>
                            <option value="monthly">{{ ui('calc.monthly') }}</option>
                            <option value="yearly">{{ ui('calc.yearly') }}</option>
                            <option value="simple">{{ ui('calc.simple') }}</option>
                        </select>
                    </div>
                @else
                    <input type="hidden" name="compounding" value="monthly"><input type="hidden" name="mode" value="loan">
                @endif
                <p data-calc-error class="err hidden" role="alert">{{ ui('calc.invalid') }}</p>
            </div>

            <div class="lg:col-span-3">
                <div data-results class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3" aria-live="polite"></div>
                <div data-schedule-wrap class="mt-6 hidden">
                    <h3 class="mb-2 text-sm font-bold">{{ ui('calc.schedule') }}</h3>
                    <div data-schedule class="max-h-72 overflow-auto rounded-lg border border-slate-200 px-3"></div>
                </div>
                <p class="mt-5 flex gap-2 rounded-lg bg-amber-50 p-3 text-xs text-amber-900"><x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0" />{{ ui('calc.disclaimer') }}</p>
            </div>
        </form>
    @endforeach
</div>
