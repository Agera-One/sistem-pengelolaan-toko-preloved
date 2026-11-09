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

(function () {
    var SELECTOR = 'details[data-row-menu]';
    var GAP = 4;
    var MARGIN = 8;

    function getMenus() {
        return document.querySelectorAll(SELECTOR);
    }

    function closeAll(except) {
        getMenus().forEach(function (menu) {
            if (menu !== except) menu.removeAttribute('open');
        });
    }

    function positionPanel(menu) {
        var trigger = menu.querySelector('summary');
        var panel = menu.querySelector('[data-row-menu-panel]');
        var rect = trigger.getBoundingClientRect();
        var height = panel.offsetHeight;
        var width = panel.offsetWidth;

        var spaceBelow = window.innerHeight - rect.bottom - MARGIN;
        var spaceAbove = rect.top - MARGIN;
        var openUp = height > spaceBelow && height <= spaceAbove;

        var top = openUp ? rect.top - height - GAP : rect.bottom + GAP;
        var left = Math.max(MARGIN, rect.right - width);

        panel.style.top = top + 'px';
        panel.style.left = left + 'px';
    }

    document.addEventListener('toggle', function (e) {
        var menu = e.target;
        if (!menu.matches || !menu.matches(SELECTOR) || !menu.open) return;

        closeAll(menu);
        positionPanel(menu);
    }, true);

    document.addEventListener('click', function (e) {
        var menu = e.target.closest(SELECTOR);

        if (!menu) {
            closeAll();
            return;
        }
        if (e.target.closest('[role="menuitem"]')) {
            menu.removeAttribute('open');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;

        var openMenu = document.querySelector(SELECTOR + '[open]');
        if (!openMenu) return;

        openMenu.removeAttribute('open');
        openMenu.querySelector('summary')?.focus();
    });

    window.addEventListener('resize', function () { closeAll(); });
    window.addEventListener('scroll', function () { closeAll(); }, true);
})();

// Form Tambah / Ubah Pengiriman
(function () {
    var form = document.getElementById('form-pengiriman');
    if (!form) return;

    var counter = document.getElementById('selected-count');
    var btnSimpan = document.getElementById('btn-simpan');
    var resiInput = document.getElementById('nomor_resi');
    var ongkirDisplay = document.getElementById('ongkir_display');
    var ongkirHidden = document.getElementById('ongkir');

    function digitsOnly(v) { return String(v).replace(/\D/g, ''); }

    function formatRupiah(v) {
        var d = digitsOnly(v);
        return d ? Number(d).toLocaleString('id-ID') : '';
    }

    function selectedTotal() {
        return form.querySelectorAll('.item-checkbox:not(:disabled):checked').length;
    }

    function refresh() {
        var total = selectedTotal();
        counter.textContent = total;
        btnSimpan.disabled = total === 0 || resiInput.value.trim() === '';
    }

    ongkirDisplay.addEventListener('input', function () {
        ongkirHidden.value = digitsOnly(ongkirDisplay.value) || '0';
        ongkirDisplay.value = formatRupiah(ongkirDisplay.value);
    });

    resiInput.addEventListener('input', function () {
        resiInput.value = resiInput.value.toUpperCase().replace(/\s+/g, '');
        refresh();
    });

    form.addEventListener('change', function (e) {
        if (e.target.classList.contains('item-checkbox')) refresh();
    });

    form.addEventListener('submit', function (e) {
        if (selectedTotal() === 0) {
            e.preventDefault();
            alert('Pilih minimal satu barang untuk dikirim.');
            return;
        }
        if (resiInput.value.trim() === '') {
            e.preventDefault();
            resiInput.focus();
            return;
        }
        btnSimpan.disabled = true;
        btnSimpan.textContent = 'Menyimpan...';
    });

    refresh();
})();
