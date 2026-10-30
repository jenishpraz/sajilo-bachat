import test from 'node:test';
import assert from 'node:assert/strict';
import { emi, epi, savings } from '../../resources/js/modules/calculators.js';

const close = (a, b, eps = 0.01) => assert.ok(Math.abs(a - b) <= eps, `${a} !~ ${b}`);

test('EMI matches the standard formula (Rs 100,000 @ 12% for 12 months)', () => {
    const r = emi(100000, 12, 12);
    close(r.emi, 8884.88);
    close(r.totalPayment, 106618.55, 0.05);
    close(r.totalInterest, 6618.55, 0.05);
    assert.equal(r.schedule.length, 12);
    close(r.schedule.at(-1).balance, 0);
});

test('EMI with zero interest splits the principal evenly', () => {
    const r = emi(120000, 0, 12);
    close(r.emi, 10000);
    close(r.totalInterest, 0);
});

test('EPI: equal principal, interest on reducing balance', () => {
    const r = epi(120000, 12, 12);
    close(r.principalPerMonth, 10000);
    close(r.firstInstalment, 11200); // 10000 + 120000*1%
    close(r.lastInstalment, 10100); // 10000 + 10000*1%
    close(r.totalInterest, 7800); // 1% * (120000+110000+...+10000) = 1% * 780000
    close(r.totalPayment, 127800);
});

test('Lump-sum savings, yearly compounding', () => {
    const r = savings({ mode: 'lump', amount: 100000, annualRate: 10, months: 24, compounding: 'yearly' });
    close(r.maturity, 121000);
    close(r.interest, 21000);
});

test('Lump-sum savings, simple interest', () => {
    const r = savings({ mode: 'lump', amount: 50000, annualRate: 8, months: 12, compounding: 'simple' });
    close(r.maturity, 54000);
});

test('Recurring savings, monthly compounding', () => {
    const r = savings({ mode: 'recurring', amount: 1000, annualRate: 12, months: 12 });
    close(r.invested, 12000);
    close(r.maturity, 12682.5, 0.05);
});

test('Invalid input returns null', () => {
    assert.equal(emi(0, 10, 12), null);
    assert.equal(epi(1000, -1, 12), null);
    assert.equal(savings({ mode: 'lump', amount: 1000, annualRate: 5, months: 0 }), null);
});
