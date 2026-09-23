(function () {
    'use strict';

    var activeToggle = null;

    function menuOf(toggle) {
        return document.getElementById(toggle.dataset.menuToggle);
    }

    function closeMenu() {
        if (!activeToggle) return;

        menuOf(activeToggle).hidden = true;
        activeToggle.setAttribute('aria-expanded', 'false');
        activeToggle = null;
    }

    function openMenu(toggle, focusFirst) {
        closeMenu();

        var menu = menuOf(toggle);
        menu.hidden = false;

        var rect = toggle.getBoundingClientRect();
        var top = rect.bottom + 4;

        if (top + menu.offsetHeight > window.innerHeight - 8) {
            top = rect.top - menu.offsetHeight - 4;
        }

        var left = Math.min(rect.right - menu.offsetWidth, window.innerWidth - menu.offsetWidth - 8);

        menu.style.top = Math.max(8, top) + 'px';
        menu.style.left = Math.max(8, left) + 'px';

        toggle.setAttribute('aria-expanded', 'true');
        activeToggle = toggle;

        if (focusFirst) {
            var first = menu.querySelector('[role="menuitem"]');
            if (first) first.focus({ preventScroll: true });
        }
    }

    document.addEventListener('click', function (e) {
        var toggle = e.target.closest('[data-menu-toggle]');

        if (toggle) {
            toggle === activeToggle ? closeMenu() : openMenu(toggle, e.detail === 0);
            return;
        }

        if (activeToggle && !e.target.closest('[role="menu"]')) {
            closeMenu();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (!activeToggle) return;

        if (e.key === 'Escape') {
            var toggle = activeToggle;
            closeMenu();
            toggle.focus();
            return;
        }

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();

            var items = Array.prototype.slice.call(
                menuOf(activeToggle).querySelectorAll('[role="menuitem"]')
            );
            var index = items.indexOf(document.activeElement);
            var next = index === -1
                ? (e.key === 'ArrowDown' ? 0 : items.length - 1)
                : (e.key === 'ArrowDown' ? index + 1 : index - 1);

            items[(next + items.length) % items.length].focus();
        }
    });

    window.addEventListener('resize', closeMenu);
    window.addEventListener('scroll', closeMenu, true);

    document.addEventListener('submit', function (e) {
        var message = e.target.dataset ? e.target.dataset.confirm : null;

        if (message && !window.confirm(message)) {
            e.preventDefault();
        }
    });

    /* ------------------------------------------------------------------
     * Validasi form di sisi klien
     *
     * Cara pakai di Blade, pada setiap input:
     *   data-label="Nama lengkap"      -> nama field di pesan error
     *   data-rules="required|max:15"   -> aturan, dipisah tanda |
     * Dan sediakan elemen pesan dengan id "err-{id input}".
     * ---------------------------------------------------------------- */
    var ruleTests = {
        required: function (value) {
            return value.trim() !== '';
        },
        min: function (value, n) {
            return value.trim().length >= Number(n);
        },
        max: function (value, n) {
            return value.length <= Number(n);
        }
    };

    var ruleMessages = {
        required: function (label) {
            return label + ' wajib diisi.';
        },
        min: function (label, n) {
            return label + ' minimal ' + n + ' karakter.';
        },
        max: function (label, n) {
            return label + ' maksimal ' + n + ' karakter.';
        }
    };

    function setFieldError(field, message) {
        var errorEl = document.getElementById('err-' + field.id);

        if (message) {
            field.setAttribute('aria-invalid', 'true');
        } else {
            field.removeAttribute('aria-invalid');
        }

        if (errorEl) {
            errorEl.textContent = message;
            errorEl.hidden = !message;
        }
    }

    function validateField(field) {
        var rules = (field.dataset.rules || '').split('|').filter(Boolean);
        var label = field.dataset.label || field.name;
        var message = '';

        for (var i = 0; i < rules.length; i++) {
            var parts = rules[i].split(':');
            var name = parts[0];
            var param = parts[1];

            if (ruleTests[name] && !ruleTests[name](field.value, param)) {
                message = ruleMessages[name](label, param);
                break;
            }
        }

        setFieldError(field, message);
        return message === '';
    }

    function validateForm(form) {
        var firstInvalid = null;

        form.querySelectorAll('[data-rules]').forEach(function (field) {
            if (!validateField(field) && !firstInvalid) {
                firstInvalid = field;
            }
        });

        if (firstInvalid) firstInvalid.focus();

        return firstInvalid === null;
    }

    function clearErrors(form) {
        form.querySelectorAll('[data-rules]').forEach(function (field) {
            setFieldError(field, '');
        });
    }

    function openModal(dialog) {
        closeMenu();
        dialog.showModal();
        document.body.classList.add('overflow-hidden');
    }

    document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal(document.getElementById(btn.dataset.modalOpen));
        });
    });

    var detailStatusStyles = {
        draft: { badge: 'bg-stone-100 text-stone-600 ring-1 ring-inset ring-stone-300', dot: 'bg-stone-400' },
        tersedia: { badge: 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200', dot: 'bg-emerald-500' },
        terjual: { badge: 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200', dot: 'bg-blue-500' }
    };
    var detailSizeFields = { lingkar: true, panjang: true };

    document.querySelectorAll('[data-detail-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var dialog = document.getElementById(btn.dataset.detailOpen);

            dialog.querySelectorAll('[data-detail]').forEach(function (el) {
                var key = el.dataset.detail;
                var value = (btn.dataset[key] || '').trim();

                if (value !== '' && detailSizeFields[key]) {
                    value += ' cm';
                }

                el.textContent = value !== '' ? value : '-';
            });

            var badge = dialog.querySelector('[data-detail-status-badge]');
            var dot = dialog.querySelector('[data-detail-status-dot]');

            if (badge && dot) {
                var statusKey = (btn.dataset.status || '').trim().toLowerCase();
                var style = detailStatusStyles[statusKey] || detailStatusStyles.draft;

                badge.className = 'mt-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ' + style.badge;
                dot.className = 'h-1.5 w-1.5 rounded-full ' + style.dot;
            }

            openModal(dialog);
        });
    });

    /* Modal ubah: isi form dari data-* tombol, lalu arahkan action ke rute update.
     *   data-edit-open="{id dialog}"
     *   data-action="{url update}"
     *   data-{nama}="..." -> diisi ke input yang punya data-fill="{nama}"
     */
    document.querySelectorAll('[data-edit-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var dialog = document.getElementById(btn.dataset.editOpen);
            var form = dialog.querySelector('form');

            form.setAttribute('action', btn.dataset.action);

            form.querySelectorAll('[data-fill]').forEach(function (field) {
                field.value = btn.dataset[field.dataset.fill] || '';
            });

            openModal(dialog);
        });
    });

    document.querySelectorAll('dialog').forEach(function (dialog) {
        var form = dialog.querySelector('form');

        dialog.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                dialog.close();
            });
        });

        dialog.addEventListener('click', function (e) {
            if (e.target === dialog) dialog.close();
        });

        dialog.addEventListener('close', function () {
            document.body.classList.remove('overflow-hidden');

            if (!form) return;

            form.reset();
            clearErrors(form);

            var submit = form.querySelector('[type="submit"]');
            if (submit) submit.disabled = false;
        });

        if (!form) return;

        form.querySelectorAll('[data-rules]').forEach(function (field) {
            field.addEventListener('change', function () {
                validateField(field);
            });

            field.addEventListener('input', function () {
                if (field.hasAttribute('aria-invalid')) validateField(field);
            });
        });

        form.addEventListener('submit', function (e) {
            if (!validateForm(form)) {
                e.preventDefault();
                return;
            }

            var submit = form.querySelector('[type="submit"]');
            if (submit) submit.disabled = true;
        });
    });
})();
