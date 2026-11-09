(function () {
    'use strict';

    var GAP = 4;
    var MARGIN = 8;

    var activeToggle = null;

    function menuOf(toggle) {
        return document.getElementById(toggle.dataset.menuToggle);
    }

    function menuItems(toggle) {
        return Array.prototype.slice.call(
            menuOf(toggle).querySelectorAll('[role="menuitem"]')
        );
    }

    function closeMenu() {
        if (!activeToggle) return;

        menuOf(activeToggle).hidden = true;
        activeToggle.setAttribute('aria-expanded', 'false');
        activeToggle = null;
    }

    function positionMenu(toggle, menu) {
        var rect = toggle.getBoundingClientRect();
        var top = rect.bottom + GAP;

        if (top + menu.offsetHeight > window.innerHeight - MARGIN) {
            top = rect.top - menu.offsetHeight - GAP;
        }

        var left = Math.min(
            rect.right - menu.offsetWidth,
            window.innerWidth - menu.offsetWidth - MARGIN
        );

        menu.style.top = Math.max(MARGIN, top) + 'px';
        menu.style.left = Math.max(MARGIN, left) + 'px';
    }

    function openMenu(toggle, focusFirst) {
        closeMenu();

        var menu = menuOf(toggle);
        menu.hidden = false;
        positionMenu(toggle, menu);

        toggle.setAttribute('aria-expanded', 'true');
        activeToggle = toggle;

        if (focusFirst) {
            var first = menu.querySelector('[role="menuitem"]');
            if (first) first.focus({ preventScroll: true });
        }
    }

    function moveFocus(key) {
        var items = menuItems(activeToggle);
        var index = items.indexOf(document.activeElement);
        var isDown = key === 'ArrowDown';
        var next;

        if (index === -1) {
            next = isDown ? 0 : items.length - 1;
        } else {
            next = isDown ? index + 1 : index - 1;
        }

        items[(next + items.length) % items.length].focus();
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
            moveFocus(e.key);
        }
    });

    window.addEventListener('resize', closeMenu);
    window.addEventListener('scroll', closeMenu, true);
    document.addEventListener('dropdown:close', closeMenu);
})();
