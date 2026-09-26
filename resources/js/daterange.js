(function () {
    const BULAN_PENDEK = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const BULAN_PANJANG = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function toKey(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function parseKey(str) {
        if (!str) return null;
        const [y, m, d] = str.split('-').map(Number);
        if (!y || !m || !d) return null;
        return new Date(y, m - 1, d);
    }

    function fmtShort(d) {
        return d.getDate() + ' ' + BULAN_PENDEK[d.getMonth()] + ' ' + d.getFullYear();
    }

    function sameDay(a, b) {
        return a && b && a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
    }

    function initDateRangePicker(wrapper) {
        const btn       = wrapper.querySelector('[data-role="btn"]');
        const label     = wrapper.querySelector('[data-role="label"]');
        const panel     = wrapper.querySelector('[data-role="panel"]');
        const startIn   = wrapper.querySelector('[data-role="input-start"]');
        const endIn     = wrapper.querySelector('[data-role="input-end"]');
        const startDisp = wrapper.querySelector('[data-role="display-start"]');
        const endDisp   = wrapper.querySelector('[data-role="display-end"]');
        const monthLbl  = wrapper.querySelector('[data-role="month-label"]');
        const daysGrid  = wrapper.querySelector('[data-role="days"]');
        const prevBtn   = wrapper.querySelector('[data-role="prev"]');
        const nextBtn   = wrapper.querySelector('[data-role="next"]');
        const applyBtn  = wrapper.querySelector('[data-role="apply"]');
        const cancelBtn = wrapper.querySelector('[data-role="cancel"]');
        const form      = wrapper.closest('form');

        if (!btn || !panel || !startIn || !endIn || !daysGrid) return;

        let rangeStart = parseKey(startIn.value);
        let rangeEnd   = parseKey(endIn.value);
        let viewDate   = rangeStart ? new Date(rangeStart.getFullYear(), rangeStart.getMonth(), 1) : new Date();
        viewDate.setDate(1);

        function updateStaticLabel() {
            if (!label) return;
            if (rangeStart && rangeEnd) {
                label.textContent = fmtShort(rangeStart) + ' – ' + fmtShort(rangeEnd);
                label.classList.remove('text-stone-500');
                label.classList.add('text-stone-900');
            } else {
                label.textContent = 'Pilih tanggal';
                label.classList.add('text-stone-500');
                label.classList.remove('text-stone-900');
            }
        }

        function updateDisplays() {
            if (startDisp) startDisp.value = rangeStart ? fmtShort(rangeStart) : '';
            if (endDisp) endDisp.value = rangeEnd ? fmtShort(rangeEnd) : '';
        }

        function renderCalendar() {
            monthLbl.textContent = BULAN_PANJANG[viewDate.getMonth()] + ' ' + viewDate.getFullYear();
            daysGrid.innerHTML = '';

            const firstOfMonth = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1);
            const startOffset = firstOfMonth.getDay(); // Monday-first
            const daysInMonth = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 0).getDate();
            const today = new Date();

            for (let i = 0; i < startOffset; i++) {
                daysGrid.appendChild(document.createElement('span'));
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const cellDate = new Date(viewDate.getFullYear(), viewDate.getMonth(), d);
                const cell = document.createElement('button');
                cell.type = 'button';
                cell.textContent = d;

                let cls = 'h-8 w-8 mx-auto flex items-center justify-center rounded-full transition text-stone-700 hover:bg-stone-100';

                const inRange = rangeStart && rangeEnd && cellDate > rangeStart && cellDate < rangeEnd;
                const isStart = sameDay(cellDate, rangeStart);
                const isEnd = sameDay(cellDate, rangeEnd);

                if (isStart || isEnd) {
                    cls = 'h-8 w-8 mx-auto flex items-center justify-center rounded-full bg-stone-900 text-white font-medium';
                } else if (inRange) {
                    cls = 'h-8 w-8 mx-auto flex items-center justify-center rounded-full bg-brand/10 text-brand';
                } else if (sameDay(cellDate, today)) {
                    cls += ' ring-1 ring-inset ring-stone-300';
                }

                cell.className = cls;
                cell.addEventListener('click', (e) => {
                    e.stopPropagation();
                    onDayClick(cellDate);
                });
                daysGrid.appendChild(cell);
            }
        }

        function onDayClick(date) {
            if (!rangeStart || (rangeStart && rangeEnd)) {
                rangeStart = date;
                rangeEnd = null;
            } else if (date < rangeStart) {
                rangeEnd = rangeStart;
                rangeStart = date;
            } else {
                rangeEnd = date;
            }
            updateDisplays();
            renderCalendar();
        }

        function openPanel() {
            panel.hidden = false;
            renderCalendar();
            updateDisplays();
        }

        function closePanel() {
            panel.hidden = true;
        }

        btn.addEventListener('click', () => {
            panel.hidden ? openPanel() : closePanel();
        });

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                viewDate.setMonth(viewDate.getMonth() - 1);
                renderCalendar();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                viewDate.setMonth(viewDate.getMonth() + 1);
                renderCalendar();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                rangeStart = parseKey(startIn.value);
                rangeEnd = parseKey(endIn.value);
                closePanel();
            });
        }

        if (applyBtn) {
            applyBtn.addEventListener('click', () => {
                startIn.value = rangeStart ? toKey(rangeStart) : '';
                endIn.value = rangeEnd ? toKey(rangeEnd) : '';
                updateStaticLabel();
                closePanel();
                if (form) form.submit();
            });
        }

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) closePanel();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closePanel();
        });

        updateStaticLabel();
    }

    function initAll() {
        document.querySelectorAll('.js-daterange').forEach(initDateRangePicker);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
