document.addEventListener('DOMContentLoaded', () => {
    const dialog = document.getElementById('modal-tambah');
    if (!dialog) return;

    const form = document.getElementById('form-tambah-pesanan');
    const rowsContainer = document.getElementById('item-rows');
    const template = document.getElementById('item-row-template');
    const btnTambahBarang = document.getElementById('btn-tambah-barang');
    const totalDisplay = document.getElementById('total-display');
    const errItems = document.getElementById('err-items');

    const formatRupiah = (value) => {
        const number = Number(value) || 0;
        return 'Rp' + number.toLocaleString('id-ID');
    };

    const getRows = () => Array.from(rowsContainer.querySelectorAll('[data-item-row]'));

    const recalculateTotal = () => {
        const total = getRows().reduce((sum, row) => {
            const hargaBeli = row.querySelector('.item-harga-beli');
            return sum + (Number(hargaBeli?.value) || 0);
        }, 0);
        totalDisplay.textContent = formatRupiah(total);
    };

    const setRowInvalid = (el, invalid) => {
        if (!el) return;
        el.setAttribute('aria-invalid', invalid ? 'true' : 'false');
    };

    const isRowValid = (row) => {
        const barangSelect = row.querySelector('.item-barang');
        const hargaBeliInput = row.querySelector('.item-harga-beli');
        const hargaJualInput = row.querySelector('.item-harga-jual');
        return Boolean(barangSelect.value) && hargaBeliInput.value !== '' && hargaJualInput.value !== '';
    };

    const validateAllRows = ({ markInvalid = false } = {}) => {
        let hasError = false;

        getRows().forEach((row) => {
            const barangSelect = row.querySelector('.item-barang');
            const hargaBeliInput = row.querySelector('.item-harga-beli');
            const hargaJualInput = row.querySelector('.item-harga-jual');
            const rowValid = isRowValid(row);

            if (!rowValid) hasError = true;

            if (markInvalid) {
                setRowInvalid(barangSelect, !barangSelect.value);
                setRowInvalid(hargaBeliInput, hargaBeliInput.value === '');
                setRowInvalid(hargaJualInput, hargaJualInput.value === '');
            } else if (rowValid) {
                setRowInvalid(barangSelect, false);
                setRowInvalid(hargaBeliInput, false);
                setRowInvalid(hargaJualInput, false);
            }
        });

        errItems.hidden = !hasError;
        return !hasError;
    };

    const bindRow = (row) => {
        const barangSelect = row.querySelector('.item-barang');
        const hargaBeliInput = row.querySelector('.item-harga-beli');
        const hargaJualInput = row.querySelector('.item-harga-jual');
        const btnHapus = row.querySelector('.btn-hapus-item');

        barangSelect.addEventListener('change', () => {
            const selected = barangSelect.selectedOptions[0];
            const hargaBeli = selected?.dataset.hargaBeli;
            const hargaJual = selected?.dataset.hargaJual;

            if (hargaBeli && !hargaBeliInput.value) {
                hargaBeliInput.value = hargaBeli;
            }
            if (hargaJual && !hargaJualInput.value) {
                hargaJualInput.value = hargaJual;
            }

            recalculateTotal();
            validateAllRows();
        });

        hargaBeliInput.addEventListener('input', () => {
            recalculateTotal();
            validateAllRows();
        });

        hargaJualInput.addEventListener('input', () => {
            validateAllRows();
        });

        btnHapus.addEventListener('click', () => {
            if (getRows().length <= 1) return;
            row.remove();
            recalculateTotal();
            updateRemoveButtons();
            validateAllRows();
        });
    };

    const updateRemoveButtons = () => {
        const rows = getRows();
        rows.forEach((row) => {
            const btnHapus = row.querySelector('.btn-hapus-item');
            btnHapus.disabled = rows.length <= 1;
            btnHapus.classList.toggle('opacity-40', rows.length <= 1);
            btnHapus.classList.toggle('cursor-not-allowed', rows.length <= 1);
        });
    };

    const addRow = () => {
        const fragment = template.content.cloneNode(true);
        const row = fragment.querySelector('[data-item-row]');
        rowsContainer.appendChild(fragment);
        bindRow(row);
        updateRemoveButtons();
    };

    const resetRows = () => {
        const rows = getRows();
        rows.slice(1).forEach((row) => row.remove());

        const firstRow = rowsContainer.querySelector('[data-item-row]');
        if (firstRow) {
            firstRow.querySelector('.item-barang').value = '';
            firstRow.querySelector('.item-harga-beli').value = '';
            firstRow.querySelector('.item-harga-jual').value = '';
        }

        errItems.hidden = true;
        recalculateTotal();
        updateRemoveButtons();
    };

    getRows().forEach(bindRow);
    updateRemoveButtons();
    recalculateTotal();

    btnTambahBarang.addEventListener('click', addRow);

    dialog.addEventListener('close', resetRows);

    form.addEventListener('submit', (event) => {
        const isValid = validateAllRows({ markInvalid: true });

        if (!isValid) {
            event.preventDefault();
        }
    });

    // --- Modal Detail: item list + status badge -------------------------

    const formatRupiahFull = (value) => 'Rp' + (Number(value) || 0).toLocaleString('id-ID');

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
    }[char]));

    const statusStyles = {
        'belum bayar': {
            badge: 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-300',
            dot: 'bg-red-500',
        },
        'sudah bayar': {
            badge: 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-300',
            dot: 'bg-emerald-500',
        },
        default: {
            badge: 'bg-stone-100 text-stone-600 ring-1 ring-inset ring-stone-300',
            dot: 'bg-stone-400',
        },
    };

    const getInitials = (value) => {
        const words = String(value || '').trim().split(/\s+/).filter(Boolean);
        if (!words.length) return '?';
        return (words[0][0] + (words[1]?.[0] || '')).toUpperCase();
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-detail-open="modal-detail"]');
        if (!trigger) return;

        const itemsBody = document.getElementById('detail-items-body');
        const statusBadge = document.getElementById('detail-status-badge');
        const statusDot = document.getElementById('detail-status-dot');
        const supplierInitial = document.getElementById('detail-supplier-initial');
        const userInitial = document.getElementById('detail-user-initial');

        if (supplierInitial) supplierInitial.textContent = getInitials(trigger.dataset.suppliernama);
        if (userInitial) userInitial.textContent = getInitials(trigger.dataset.usernama);

        if (itemsBody) {
            let items = [];
            try {
                items = JSON.parse(trigger.dataset.items || '[]');
            } catch (e) {
                items = [];
            }

            itemsBody.innerHTML = items.length
                ? items.map((item) => `
                    <div class="flex items-center gap-3 px-4 py-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shirt preview-icon"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium text-stone-900">${escapeHtml(item.nama)}</p>
                            <p class="text-stone-500">Harga jual ${formatRupiahFull(item.harga_jual)}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="font-medium text-stone-900">${formatRupiahFull(item.harga_beli)}</p>
                            <p class="text-xs text-stone-400">Harga beli</p>
                        </div>
                    </div>
                `).join('')
                : `<p class="px-4 py-6 text-center text-stone-400">Tidak ada barang.</p>`;
        }

        if (statusBadge && statusDot) {
            const statusKey = (trigger.dataset.status || '').toLowerCase();
            const style = statusStyles[statusKey] || statusStyles.default;

            statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ' + style.badge;
            statusDot.className = 'h-1.5 w-1.5 rounded-full ' + style.dot;
        }
    });
});
