import '../css/app.css';

const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

/* Sidebar (mobile) */
const side = $('[data-sidebar]');
$$('[data-sidebar-toggle]').forEach((b) => b.addEventListener('click', () => {
    side.classList.toggle('-translate-x-full');
    $('[data-sidebar-backdrop]').classList.toggle('hidden');
}));

/* Confirmation dialog for destructive actions: <form data-confirm="Delete this?"> */
const dialog = $('[data-confirm-dialog]');
let pending = null;
document.addEventListener('submit', (e) => {
    const form = e.target.closest('form[data-confirm]');
    if (!form || form.dataset.confirmed === '1') return;
    e.preventDefault();
    pending = form;
    $('[data-confirm-text]', dialog).textContent = form.dataset.confirm;
    dialog.classList.remove('hidden');
    dialog.classList.add('flex');
    $('[data-confirm-yes]', dialog).focus();
});
const closeDialog = () => { dialog.classList.add('hidden'); dialog.classList.remove('flex'); pending = null; };
$('[data-confirm-yes]', dialog)?.addEventListener('click', () => { if (pending) { pending.dataset.confirmed = '1'; pending.submit(); } closeDialog(); });
$('[data-confirm-no]', dialog)?.addEventListener('click', closeDialog);
document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && pending) closeDialog(); });

/* Live preview of a chosen upload */
$$('input[type=file][data-preview]').forEach((input) => {
    input.addEventListener('change', () => {
        const img = document.getElementById(input.dataset.preview);
        const f = input.files[0];
        if (img && f && f.type.startsWith('image/')) { img.src = URL.createObjectURL(f); img.classList.remove('hidden'); }
    });
});

/* Supplied-portrait picker: choose from library, or clear */
$$('[data-library]').forEach((wrap) => {
    const hidden = $('input[type=hidden]', wrap);
    const preview = document.getElementById(wrap.dataset.library);
    $$('[data-pick]', wrap).forEach((btn) => btn.addEventListener('click', () => {
        const on = btn.getAttribute('aria-pressed') === 'true';
        $$('[data-pick]', wrap).forEach((b) => { b.setAttribute('aria-pressed', 'false'); b.classList.remove('ring-4', 'ring-brand-600'); });
        if (on) { hidden.value = ''; return; }
        btn.setAttribute('aria-pressed', 'true');
        btn.classList.add('ring-4', 'ring-brand-600');
        hidden.value = btn.dataset.pick;
        if (preview) { preview.src = btn.dataset.url; preview.classList.remove('hidden'); }
    }));
});

/* Filter selects submit on change */
$$('select[data-autosubmit]').forEach((s) => s.addEventListener('change', () => s.form.submit()));

/* Auto-dismiss toasts */
$$('[data-toast]').forEach((t) => setTimeout(() => t.remove(), 6000));
