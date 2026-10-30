import '../css/app.css';
import { emi, epi, savings } from './modules/calculators.js';

document.documentElement.classList.add('js');

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
const lang = document.documentElement.lang || 'en';
const isNe = lang === 'ne';
const DEV = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
const digits = (s) => (isNe ? String(s).replace(/\d/g, (d) => DEV[d]) : String(s));
const fmt = (n, dec = 0) => digits(new Intl.NumberFormat('en-IN', { minimumFractionDigits: dec, maximumFractionDigits: dec }).format(n));
const money = (n) => (isNe ? 'रू ' : 'Rs. ') + fmt(n, 2);
const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const readJson = (el, attr) => { try { return JSON.parse(el.getAttribute(attr) || 'null'); } catch { return null; } };

/* ---------------------------------------------------------------- navigation */
(function nav() {
    const toggle = $('[data-mobile-toggle]');
    const panel = $('[data-mobile-panel]');
    if (toggle && panel) {
        toggle.addEventListener('click', () => {
            const open = panel.classList.toggle('hidden') === false;
            toggle.setAttribute('aria-expanded', String(open));
            document.body.classList.toggle('overflow-hidden', open);
        });
    }
    // Mobile accordions
    $$('[data-accordion]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.getAttribute('aria-controls'));
            const open = target.classList.toggle('hidden') === false;
            btn.setAttribute('aria-expanded', String(open));
            btn.querySelector('[data-chevron]')?.classList.toggle('rotate-180', open);
        });
    });
    // Desktop dropdowns: hover via CSS, click/keyboard via JS (touch laptops, a11y)
    const items = $$('[data-dropdown]');
    const closeAll = (except) => items.forEach((i) => {
        if (i !== except) { i.classList.remove('is-open'); $('[data-dropdown-btn]', i)?.setAttribute('aria-expanded', 'false'); }
    });
    items.forEach((item) => {
        const btn = $('[data-dropdown-btn]', item);
        btn?.addEventListener('click', (e) => {
            e.preventDefault();
            const open = item.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', String(open));
            closeAll(item);
        });
    });
    document.addEventListener('click', (e) => { if (!e.target.closest('[data-dropdown]')) closeAll(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAll(); });

    const sticky = $('[data-sticky-header]');
    const top = $('[data-back-top]');
    const onScroll = () => {
        sticky?.classList.toggle('shadow-md', window.scrollY > 8);
        top?.classList.toggle('hidden', window.scrollY < 500);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    top?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();

/* ---------------------------------------------------------------- hero slider */
(function hero() {
    const root = $('[data-hero]');
    if (!root) return;
    const slides = $$('[data-slide]', root);
    const dots = $$('[data-dot]', root);
    if (slides.length < 2) return;
    let i = 0;
    let timer;
    const show = (n) => {
        i = (n + slides.length) % slides.length;
        slides.forEach((s, k) => s.classList.toggle('is-active', k === i));
        dots.forEach((d, k) => d.setAttribute('aria-current', k === i ? 'true' : 'false'));
    };
    const play = () => { stop(); if (!matchMedia('(prefers-reduced-motion: reduce)').matches) timer = setInterval(() => show(i + 1), 6500); };
    const stop = () => clearInterval(timer);
    $('[data-prev]', root)?.addEventListener('click', () => { show(i - 1); play(); });
    $('[data-next]', root)?.addEventListener('click', () => { show(i + 1); play(); });
    dots.forEach((d, k) => d.addEventListener('click', () => { show(k); play(); }));
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', play);
    show(0);
    play();
})();

/* ---------------------------------------------------------------- counters + reveal */
(function motion() {
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const run = (el) => {
        const target = parseFloat(el.dataset.count);
        if (!isFinite(target)) return;
        const dec = (el.dataset.count.split('.')[1] || '').length;
        if (reduce) { el.textContent = fmt(target, dec); return; }
        const start = performance.now();
        const dur = 1400;
        const step = (t) => {
            const p = Math.min(1, (t - start) / dur);
            el.textContent = fmt(target * (1 - Math.pow(1 - p, 3)), dec);
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };
    if (!('IntersectionObserver' in window)) {
        $$('.reveal').forEach((e) => e.classList.add('is-visible'));
        $$('[data-count]').forEach((e) => { e.textContent = fmt(parseFloat(e.dataset.count), (e.dataset.count.split('.')[1] || '').length); });
        return;
    }
    const io = new IntersectionObserver((entries) => entries.forEach((en) => {
        if (!en.isIntersecting) return;
        en.target.classList.add('is-visible');
        if (en.target.hasAttribute('data-count')) run(en.target);
        io.unobserve(en.target);
    }), { threshold: 0.2 });
    $$('.reveal, [data-count]').forEach((e) => io.observe(e));
})();

/* ---------------------------------------------------------------- lightbox + video modal */
(function overlays() {
    const make = () => {
        const el = document.createElement('div');
        el.className = 'overlay';
        el.setAttribute('role', 'dialog');
        el.setAttribute('aria-modal', 'true');
        document.body.appendChild(el);
        return el;
    };
    const box = make();
    let group = [];
    let idx = 0;
    const close = () => { box.classList.remove('is-open'); box.innerHTML = ''; document.body.classList.remove('overflow-hidden'); };
    const renderImg = () => {
        const a = group[idx];
        const cap = a.dataset.caption ? `<p class="mt-3 text-center text-sm text-white/90">${esc(a.dataset.caption)}</p>` : '';
        box.innerHTML = `
            <button type="button" data-close class="absolute right-4 top-4 rounded-full bg-white/15 p-2 text-white hover:bg-white/25" aria-label="Close">✕</button>
            ${group.length > 1 ? '<button type="button" data-lb-prev class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/15 px-3 py-2 text-xl text-white hover:bg-white/25" aria-label="Previous">‹</button><button type="button" data-lb-next class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/15 px-3 py-2 text-xl text-white hover:bg-white/25" aria-label="Next">›</button>' : ''}
            <figure class="max-h-full max-w-5xl"><img src="${esc(a.getAttribute('href'))}" alt="${esc(a.dataset.caption || '')}" class="mx-auto max-h-[80vh] rounded-lg object-contain">${cap}</figure>`;
    };
    const open = () => { renderImg(); box.classList.add('is-open'); document.body.classList.add('overflow-hidden'); };

    document.addEventListener('click', (e) => {
        const a = e.target.closest('[data-lightbox]');
        if (a) {
            e.preventDefault();
            group = $$(`[data-lightbox="${a.dataset.lightbox}"]`);
            idx = group.indexOf(a);
            open();
            return;
        }
        const v = e.target.closest('[data-video]');
        if (v) {
            e.preventDefault();
            box.innerHTML = `<button type="button" data-close class="absolute right-4 top-4 rounded-full bg-white/15 p-2 text-white hover:bg-white/25" aria-label="Close">✕</button>
                <div class="aspect-video w-full max-w-4xl overflow-hidden rounded-xl bg-black"><iframe src="${esc(v.dataset.video)}?autoplay=1&rel=0" class="h-full w-full" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen title="${esc(v.dataset.title || 'Video')}"></iframe></div>`;
            box.classList.add('is-open');
            document.body.classList.add('overflow-hidden');
            return;
        }
        if (!box.classList.contains('is-open')) return;
        if (e.target === box || e.target.closest('[data-close]')) close();
        else if (e.target.closest('[data-lb-prev]')) { idx = (idx - 1 + group.length) % group.length; renderImg(); }
        else if (e.target.closest('[data-lb-next]')) { idx = (idx + 1) % group.length; renderImg(); }
    });
    document.addEventListener('keydown', (e) => {
        if (!box.classList.contains('is-open')) return;
        if (e.key === 'Escape') close();
        if (group.length > 1 && box.querySelector('img')) {
            if (e.key === 'ArrowLeft') { idx = (idx - 1 + group.length) % group.length; renderImg(); }
            if (e.key === 'ArrowRight') { idx = (idx + 1) % group.length; renderImg(); }
        }
    });
})();

/* ---------------------------------------------------------------- digital calendar */
function initCalendar(root) {
    const items = readJson(root, 'data-events') || [];
    const typeLabels = readJson(root, 'data-types') || {};
    const empty = root.dataset.empty || 'No events on this day.';
    const locale = isNe ? 'ne-NP-u-nu-deva' : 'en-US';
    const monthFmt = new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' });
    const dayFmt = new Intl.DateTimeFormat(locale, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    const wk = new Intl.DateTimeFormat(locale, { weekday: 'short' });
    const iso = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;

    // Expand multi-day items to every day they cover.
    const byDay = {};
    items.forEach((it) => {
        const s = new Date(it.date + 'T00:00:00');
        const e = new Date((it.end || it.date) + 'T00:00:00');
        for (let d = new Date(s), n = 0; d <= e && n < 62; d.setDate(d.getDate() + 1), n++) (byDay[iso(d)] ||= []).push(it);
    });

    const grid = $('[data-cal-grid]', root);
    const title = $('[data-cal-title]', root);
    const list = $('[data-cal-list]', root);
    const heading = $('[data-cal-day]', root);
    const today = iso(new Date());
    let view = new Date(); view.setDate(1);
    let selected = today;

    const renderList = () => {
        heading.textContent = dayFmt.format(new Date(selected + 'T00:00:00'));
        const evs = byDay[selected] || [];
        list.innerHTML = evs.length ? evs.map((ev) => `
            <li class="rounded-lg border border-slate-200 bg-white p-3">
                <span class="badge badge-green">${esc(typeLabels[ev.type] || ev.type)}</span>
                <p class="mt-1 font-semibold text-ink">${ev.url ? `<a class="hover:text-brand-700" href="${esc(ev.url)}">${esc(ev.title)}</a>` : esc(ev.title)}</p>
                ${ev.note ? `<p class="text-sm text-slate-500">${esc(ev.note)}</p>` : ''}
            </li>`).join('') : `<li class="rounded-lg bg-slate-50 p-3 text-sm text-slate-500">${esc(empty)}</li>`;
    };

    const render = () => {
        title.textContent = monthFmt.format(view);
        const first = new Date(view.getFullYear(), view.getMonth(), 1);
        const days = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
        let html = '';
        for (let i = 0; i < 7; i++) html += `<div class="py-2 text-center text-xs font-bold text-slate-500">${esc(wk.format(new Date(2023, 0, 1 + i)))}</div>`;
        for (let i = 0; i < first.getDay(); i++) html += '<div></div>';
        for (let d = 1; d <= days; d++) {
            const key = iso(new Date(view.getFullYear(), view.getMonth(), d));
            const evs = byDay[key] || [];
            const cls = ['cal-cell', evs.length ? 'has-event' : '', key === today ? 'is-today' : '', key === selected ? 'is-selected' : ''].join(' ');
            html += `<button type="button" class="${cls}" data-day="${key}" aria-label="${esc(dayFmt.format(new Date(key + 'T00:00:00')))}${evs.length ? ', ' + evs.length : ''}"><span class="font-semibold">${digits(d)}</span>${evs.length ? '<span class="absolute bottom-1.5 left-1/2 h-1.5 w-1.5 -translate-x-1/2 rounded-full bg-accent-500"></span>' : ''}</button>`;
        }
        grid.innerHTML = html;
        renderList();
    };

    grid.addEventListener('click', (e) => {
        const b = e.target.closest('[data-day]');
        if (!b) return;
        selected = b.dataset.day;
        render();
    });
    $('[data-cal-prev]', root).addEventListener('click', () => { view.setMonth(view.getMonth() - 1); render(); });
    $('[data-cal-next]', root).addEventListener('click', () => { view.setMonth(view.getMonth() + 1); render(); });
    $('[data-cal-today]', root)?.addEventListener('click', () => { view = new Date(); view.setDate(1); selected = today; render(); });
    render();
}
$$('[data-calendar]').forEach(initCalendar);

/* ---------------------------------------------------------------- calculators */
function initCalculators(root) {
    const labels = readJson(root, 'data-labels') || {};
    const rates = { loan: readJson(root, 'data-loan-rates') || [], saving: readJson(root, 'data-saving-rates') || [] };

    // Tabs
    const tabs = $$('[data-tab]', root);
    const panels = $$('[data-panel]', root);
    const activate = (name) => {
        tabs.forEach((t) => {
            const on = t.dataset.tab === name;
            t.setAttribute('aria-selected', String(on));
            t.classList.toggle('bg-brand-600', on);
            t.classList.toggle('text-white', on);
            t.classList.toggle('text-slate-600', !on);
        });
        panels.forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== name));
        if (history.replaceState) history.replaceState(null, '', '#' + name);
    };
    tabs.forEach((t) => t.addEventListener('click', () => activate(t.dataset.tab)));
    const initial = location.hash.replace('#', '');
    activate(tabs.some((t) => t.dataset.tab === initial) ? initial : tabs[0]?.dataset.tab);

    const stat = (label, value, strong = false) => `
        <div class="rounded-xl ${strong ? 'bg-brand-600 text-white' : 'bg-brand-50 text-ink'} p-4">
            <p class="text-xs font-semibold ${strong ? 'text-brand-100' : 'text-slate-500'}">${esc(label)}</p>
            <p class="mt-1 text-xl font-extrabold">${esc(value)}</p>
        </div>`;

    const months = (panel) => {
        const v = parseFloat($('[name=duration]', panel).value);
        return $('[name=unit]', panel).value === 'years' ? Math.round(v * 12) : Math.round(v);
    };

    const scheduleHtml = (rows) => `
        <table class="w-full text-sm"><thead class="sticky top-0 bg-white"><tr class="text-left text-xs uppercase text-slate-500">
            <th class="py-2 pr-2">${esc(labels.month)}</th><th class="py-2 pr-2 text-right">${esc(labels.instalment)}</th>
            <th class="py-2 pr-2 text-right">${esc(labels.principal)}</th><th class="py-2 pr-2 text-right">${esc(labels.interest)}</th><th class="py-2 text-right">${esc(labels.balance)}</th></tr></thead>
        <tbody>${rows.map((r) => `<tr class="border-t border-slate-100"><td class="py-1.5 pr-2">${digits(r.month)}</td><td class="py-1.5 pr-2 text-right">${fmt(r.instalment, 2)}</td><td class="py-1.5 pr-2 text-right">${fmt(r.principal, 2)}</td><td class="py-1.5 pr-2 text-right">${fmt(r.interest, 2)}</td><td class="py-1.5 text-right">${fmt(r.balance, 2)}</td></tr>`).join('')}</tbody></table>`;

    const compute = {
        emi(panel) {
            const r = emi($('[name=amount]', panel).value, $('[name=rate]', panel).value, months(panel));
            if (!r) return null;
            return {
                stats: stat(labels.monthlyEmi, money(r.emi), true) + stat(labels.totalInterest, money(r.totalInterest)) + stat(labels.totalPayment, money(r.totalPayment)),
                schedule: scheduleHtml(r.schedule),
            };
        },
        epi(panel) {
            const r = epi($('[name=amount]', panel).value, $('[name=rate]', panel).value, months(panel));
            if (!r) return null;
            return {
                stats: stat(labels.firstInstalment, money(r.firstInstalment), true) + stat(labels.lastInstalment, money(r.lastInstalment)) + stat(labels.principalPerMonth, money(r.principalPerMonth)) + stat(labels.totalInterest, money(r.totalInterest)) + stat(labels.totalPayment, money(r.totalPayment)),
                schedule: scheduleHtml(r.schedule),
            };
        },
        savings(panel) {
            const mode = $('[name=mode]', panel).value;
            const r = savings({ mode, amount: $('[name=amount]', panel).value, annualRate: $('[name=rate]', panel).value, months: months(panel), compounding: $('[name=compounding]', panel).value });
            if (!r) return null;
            return { stats: stat(labels.maturityAmount, money(r.maturity), true) + stat(labels.totalDeposited, money(r.invested)) + stat(labels.interestEarned, money(r.interest)), schedule: '' };
        },
    };

    panels.forEach((panel) => {
        const kind = panel.dataset.panel;
        const out = $('[data-results]', panel);
        const sched = $('[data-schedule]', panel);
        const err = $('[data-calc-error]', panel);

        // Rate picker from active plans
        const preset = $('[name=preset]', panel);
        if (preset) {
            const list = kind === 'savings' ? rates.saving : rates.loan;
            preset.innerHTML = `<option value="">${esc(labels.customRate)}</option>` + list.map((p) => `<option value="${p.rate}">${esc(p.name)} — ${digits(p.rate)}%</option>`).join('');
            preset.addEventListener('change', () => { if (preset.value !== '') { $('[name=rate]', panel).value = preset.value; run(); } });
        }
        if (kind === 'savings') {
            const mode = $('[name=mode]', panel);
            const comp = $('[data-comp]', panel);
            const sync = () => { comp.classList.toggle('hidden', mode.value === 'recurring'); $('[data-amount-label]', panel).textContent = mode.value === 'recurring' ? labels.monthlyDeposit : labels.depositAmount; };
            mode.addEventListener('change', () => { sync(); run(); });
            sync();
        }

        function run() {
            const result = compute[kind](panel);
            if (!result) {
                out.innerHTML = '';
                sched.innerHTML = '';
                err.classList.remove('hidden');
                return;
            }
            err.classList.add('hidden');
            out.innerHTML = result.stats;
            sched.innerHTML = result.schedule;
            $('[data-schedule-wrap]', panel)?.classList.toggle('hidden', !result.schedule);
        }
        panel.addEventListener('input', (e) => { if (e.target.name !== 'preset') { if (preset && e.target.name === 'rate') preset.value = ''; run(); } });
        panel.addEventListener('submit', (e) => { e.preventDefault(); run(); });
        run();
    });
}
$$('[data-calculators]').forEach(initCalculators);

/* ---------------------------------------------------------------- forms */
$$('form[data-loading]').forEach((form) => {
    form.addEventListener('submit', () => {
        if (!form.checkValidity()) return;
        const btn = $('[type=submit]', form);
        if (!btn) return;
        btn.disabled = true;
        btn.dataset.label = btn.innerHTML;
        btn.innerHTML = `<svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> ${esc(form.dataset.loading)}`;
    });
});
// Auto-dismiss success toasts
$$('[data-toast]').forEach((t) => setTimeout(() => t.remove(), 7000));
