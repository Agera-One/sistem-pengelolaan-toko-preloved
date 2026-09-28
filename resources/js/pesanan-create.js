document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-pesanan');
    if (!form) return;

    const body = form.querySelector('#item-rows');
    const template = form.querySelector('#row-template');
    const addBtn = form.querySelector('#btn-add-row');
    const submitBtn = form.querySelector('#btn-submit');
    const itemsError = form.querySelector('#items-error');
    const totalFooter = form.querySelector('#total-footer');

    const CELLS = ['nama', 'kategori', 'lingkar', 'panjang', 'harga_beli', 'harga_jual'];
    const MONEY = ['harga_beli', 'harga_jual'];

    const digits = (v) => (v || '').replace(/\D/g, '');
    const formatMoney = (v) => digits(v).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    const rupiah = (n) => 'Rp' + n.toLocaleString('id-ID');

    const rows = () => [...body.querySelectorAll('[data-row]')];
    const cell = (row, name) => row.querySelector(`[data-cell="${name}"]`);
    const isFilled = (row) => CELLS.some((n) => {
        const el = cell(row, n);
        return el && el.value.trim() !== '';
    });

    let nextIndex = rows().reduce((max, r) => Math.max(max, Number(r.dataset.index) + 1), 0);

    function refresh() {
        let total = 0;

        rows().forEach((row) => {
            if (isFilled(row)) {
                const hargaBeliEl = cell(row, 'harga_beli');
                total += Number(digits(hargaBeliEl ? hargaBeliEl.value : '') || 0);
            }
        });

        if (totalFooter) totalFooter.textContent = rupiah(total);
    }

    function addRow() {
        const html = template.innerHTML.replaceAll('__INDEX__', nextIndex++).trim();
        const holder = document.createElement('tbody');
        holder.innerHTML = html;
        const row = holder.firstElementChild;
        body.appendChild(row);
        refresh();
        return row;
    }

    // Format nominal awal
    body.querySelectorAll('[data-money]').forEach((el) => (el.value = formatMoney(el.value)));
    refresh();

    if (addBtn) {
        addBtn.addEventListener('click', () => {
            const row = addRow();
            const namaEl = cell(row, 'nama');
            if (namaEl) namaEl.focus();
        });
    }

    body.addEventListener('input', (e) => {
        if (e.target.matches('[data-money]')) e.target.value = formatMoney(e.target.value);
        e.target.removeAttribute('aria-invalid');
        if (itemsError) itemsError.hidden = true;
        refresh();
    });

    body.addEventListener('change', refresh);

    // Enter pindah kolom / tambah baris
    body.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter' || !e.target.matches('[data-cell]')) return;
        e.preventDefault();

        const row = e.target.closest('[data-row]');
        const pos = CELLS.indexOf(e.target.dataset.cell);

        if (pos < CELLS.length - 1) {
            const nextCell = cell(row, CELLS[pos + 1]);
            if (nextCell) nextCell.focus();
            return;
        }

        const nextRow = row.nextElementSibling ?? addRow();
        const firstCell = cell(nextRow, 'nama');
        if (firstCell) firstCell.focus();
    });

    body.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove]');
        if (!btn) return;

        const row = btn.closest('[data-row]');
        if (rows().length === 1) {
            CELLS.forEach((n) => {
                const el = cell(row, n);
                if (el) {
                    el.value = '';
                    el.removeAttribute('aria-invalid');
                }
            });
            const namaEl = cell(row, 'nama');
            if (namaEl) namaEl.focus();
        } else {
            row.remove();
        }
        refresh();
    });

    form.addEventListener('submit', (e) => {
        let firstInvalid = null;
        const mark = (el, bad) => {
            if (!el) return;
            if (bad) {
                el.setAttribute('aria-invalid', 'true');
                firstInvalid ??= el;
            } else {
                el.removeAttribute('aria-invalid');
            }
        };

        const tanggal = form.querySelector('#tanggal');
        const supplier = form.querySelector('#supplier_id');
        if (tanggal) mark(tanggal, !tanggal.value);
        if (supplier) mark(supplier, !supplier.value);

        const filled = rows().filter(isFilled);
        if (itemsError) {
            itemsError.hidden = filled.length > 0;
            if (!filled.length) {
                itemsError.textContent = 'Tambahkan minimal satu barang.';
                const firstRowName = cell(rows()[0], 'nama');
                if (firstRowName) firstInvalid ??= firstRowName;
            }
        }

        filled.forEach((row) => {
            CELLS.forEach((n) => {
                const el = cell(row, n);
                if (!el) return;
                const v = el.value.trim();
                const bad = v === '' || (MONEY.includes(n) && Number(digits(v)) < 1);
                mark(el, bad);
            });
        });

        if (firstInvalid) {
            e.preventDefault();
            firstInvalid.focus();
            return;
        }

        // Disable baris kosong & bersihkan format money sebelum dikirim
        rows().forEach((row) => {
            if (!isFilled(row)) {
                row.querySelectorAll('input, select').forEach((el) => (el.disabled = true));
            } else {
                MONEY.forEach((n) => {
                    const el = cell(row, n);
                    if (el) el.value = digits(el.value);
                });
            }
        });

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        }
    });
});
