// Pure calculation helpers (no DOM) so they can be unit-tested with `npm run test:js`.

const round2 = (n) => Math.round((n + Number.EPSILON) * 100) / 100;

/**
 * EMI (equated monthly instalment): the same amount every month.
 * @returns {{emi:number,totalInterest:number,totalPayment:number,schedule:Array}}
 */
export function emi(principal, annualRate, months) {
    principal = Number(principal);
    months = Math.round(Number(months));
    annualRate = Number(annualRate);
    if (!(principal > 0) || !(months > 0) || !(annualRate >= 0)) return null;

    const r = annualRate / 12 / 100;
    const payment = r === 0 ? principal / months : (principal * r * Math.pow(1 + r, months)) / (Math.pow(1 + r, months) - 1);

    let balance = principal;
    let totalInterest = 0;
    const schedule = [];
    for (let m = 1; m <= months; m++) {
        const interest = balance * r;
        let principalPart = payment - interest;
        if (m === months) principalPart = balance; // absorb rounding in the final instalment
        balance = Math.max(0, balance - principalPart);
        totalInterest += interest;
        schedule.push({ month: m, instalment: principalPart + interest, principal: principalPart, interest, balance });
    }

    return {
        emi: round2(payment),
        totalInterest: round2(totalInterest),
        totalPayment: round2(principal + totalInterest),
        schedule,
    };
}

/**
 * EPI (equal principal instalment): the principal part is equal each month and
 * interest is charged on the reducing balance, so instalments shrink over time.
 */
export function epi(principal, annualRate, months) {
    principal = Number(principal);
    months = Math.round(Number(months));
    annualRate = Number(annualRate);
    if (!(principal > 0) || !(months > 0) || !(annualRate >= 0)) return null;

    const r = annualRate / 12 / 100;
    const principalPart = principal / months;
    let balance = principal;
    let totalInterest = 0;
    const schedule = [];
    for (let m = 1; m <= months; m++) {
        const interest = balance * r;
        balance = Math.max(0, balance - principalPart);
        totalInterest += interest;
        schedule.push({ month: m, instalment: principalPart + interest, principal: principalPart, interest, balance });
    }

    return {
        firstInstalment: round2(schedule[0].instalment),
        lastInstalment: round2(schedule[months - 1].instalment),
        principalPerMonth: round2(principalPart),
        totalInterest: round2(totalInterest),
        totalPayment: round2(principal + totalInterest),
        schedule,
    };
}

const COMPOUNDING = { simple: 0, monthly: 12, quarterly: 4, yearly: 1 };

/**
 * Savings maturity.
 *  mode "lump":      one deposit kept for `months`
 *  mode "recurring": `amount` deposited at the end of every month (monthly compounding)
 */
export function savings({ mode, amount, annualRate, months, compounding = 'quarterly' }) {
    amount = Number(amount);
    months = Math.round(Number(months));
    annualRate = Number(annualRate);
    if (!(amount > 0) || !(months > 0) || !(annualRate >= 0)) return null;

    let maturity;
    let invested;

    if (mode === 'recurring') {
        const i = annualRate / 12 / 100;
        maturity = i === 0 ? amount * months : (amount * (Math.pow(1 + i, months) - 1)) / i;
        invested = amount * months;
    } else {
        const years = months / 12;
        const n = COMPOUNDING[compounding] ?? 4;
        maturity = n === 0 ? amount * (1 + (annualRate / 100) * years) : amount * Math.pow(1 + annualRate / 100 / n, n * years);
        invested = amount;
    }

    return {
        invested: round2(invested),
        interest: round2(maturity - invested),
        maturity: round2(maturity),
    };
}
