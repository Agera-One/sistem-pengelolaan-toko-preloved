(function () {
    document.addEventListener('click', function (e) {
        var el = e.target;
        if (el && el.matches && el.matches('input[data-date-picker]') && typeof el.showPicker === 'function') {
            try { el.showPicker(); } catch (err) {}
        }
    });

    document.addEventListener('change', function (e) {
        var root = e.target.closest ? e.target.closest('[data-daterange]') : null;
        if (!root) return;

        var s = root.querySelector('[data-date-start]');
        var en = root.querySelector('[data-date-end]');
        en.min = s.value || '';
        s.max = en.value || '';

        if (root.hasAttribute('data-daterange-auto')) {
            var both = s.value && en.value;
            var none = !s.value && !en.value;
            var form = root.closest('form');
            if (form && (both || none)) form.submit();
        }
    });
})();
