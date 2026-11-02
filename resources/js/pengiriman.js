(function () {
    var root = document.querySelector('[data-filter-root]');
    if (!root) return;
    var toggle = root.querySelector('[data-filter-toggle]');
    var panel = document.getElementById('panel-filter');

    function setOpen(open) {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    toggle.addEventListener('click', function () { setOpen(panel.hidden); });
    document.addEventListener('click', function (e) {
        if (!panel.hidden && !root.contains(e.target)) setOpen(false);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !panel.hidden) { setOpen(false); toggle.focus(); }
    });

    panel.querySelectorAll('[data-filter-reset]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-filter-reset');
            var targets = key === 'tanggal'
                ? panel.querySelectorAll('[name="tanggal_mulai"], [name="tanggal_selesai"]')
                : panel.querySelectorAll('[name="' + key + '"]');
            targets.forEach(function (el) {
                el.value = '';
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    });
})();

(function () {
    var input = document.getElementById('q');
    var btn = document.querySelector('[data-search-clear]');
    if (!input || !btn) return;

    function sync() { btn.classList.toggle('hidden', input.value === ''); }
    input.addEventListener('input', sync);
    btn.addEventListener('click', function () {
        var hadValue = input.value !== '';
        input.value = '';
        sync();
        if (hadValue && input.defaultValue !== '') {
            input.form.submit();
        } else {
            input.focus();
        }
    });
})();

document.querySelectorAll('[data-multiselect]').forEach(function (dropdown) {
    dropdown.querySelector('[data-multiselect-clear]')?.addEventListener('click', function () {
        dropdown.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
            cb.checked = false;
        });
    });

    document.addEventListener('click', function (e) {
        if (dropdown.open && !dropdown.contains(e.target)) {
            dropdown.removeAttribute('open');
        }
    });

    dropdown.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            dropdown.removeAttribute('open');
            dropdown.querySelector('summary')?.focus();
        }
    });
});
