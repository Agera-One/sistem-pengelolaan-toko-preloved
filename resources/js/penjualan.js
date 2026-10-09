(function () {
    'use strict';

    var form = document.getElementById('form-penjualan');
    if (!form || !window.MoneyInput) return;

    var M = window.MoneyInput;
    var tbody = document.getElementById('item-rows');
    var template = document.getElementById('row-template');
    var itemError = document.getElementById('item-error');
    var btnSimpan = document.getElementById('btn-simpan');
    var ongkirInput = document.getElementById('ongkir');

    var barang = JSON.parse(document.getElementById('barang-data').textContent || '[]');
    var oldItems = JSON.parse(document.getElementById('old-items').textContent || '[]');
    var byId = {};
    barang.forEach(function (b) { byId[String(b.id)] = b; });

    var counter = 0;

    function field(row, name) {
        return row.querySelector('[data-field="' + name + '"]');
    }

    function rows() {
        return Array.prototype.slice.call(tbody.querySelectorAll('[data-row]'));
    }

    function fillDetail(row, b) {
        field(row, 'kategori').value = b ? (b.kategori || '') : '';
        field(row, 'lingkar').value = b ? (b.lingkar == null ? '' : b.lingkar) : '';
        field(row, 'panjang').value = b ? (b.panjang == null ? '' : b.panjang) : '';
    }

    function addRow(item) {
        var html = template.innerHTML.replace(/__INDEX__/g, String(counter++));
        var holder = document.createElement('tbody');
        holder.innerHTML = html.trim();
        var row = holder.firstElementChild;

        var select = field(row, 'barang');
        barang.forEach(function (b) {
            var opt = document.createElement('option');
            opt.value = b.id;
            opt.textContent = b.kode + ' — ' + b.nama;
            select.appendChild(opt);
        });

        tbody.appendChild(row);

        if (item && item.barang_id && byId[String(item.barang_id)]) {
            select.value = String(item.barang_id);
            fillDetail(row, byId[select.value]);
            var harga = item.harga_jual != null && item.harga_jual !== ''
                ? item.harga_jual
                : byId[select.value].harga_jual;
            field(row, 'harga').value = M.format(harga);
        }

        refreshOptions();
        recalc();
        return row;
    }

    function refreshOptions() {
        var chosen = {};
        rows().forEach(function (row) {
            var v = field(row, 'barang').value;
            if (v) chosen[v] = true;
        });
        rows().forEach(function (row) {
            var select = field(row, 'barang');
            Array.prototype.forEach.call(select.options, function (opt) {
                if (!opt.value) return;
                var taken = chosen[opt.value] && opt.value !== select.value;
                opt.disabled = taken;
                opt.hidden = taken;
            });
        });
    }

    function recalc() {
        var subtotal = 0;
        rows().forEach(function (row) {
            if (field(row, 'barang').value) subtotal += M.parse(field(row, 'harga').value);
        });
        var ongkir = M.parse(ongkirInput.value);
        document.getElementById('sum-subtotal').textContent = M.rupiah(subtotal);
        document.getElementById('sum-ongkir').textContent = M.rupiah(ongkir);
        document.getElementById('sum-total').textContent = M.rupiah(subtotal + ongkir);
    }

    function setItemError(message) {
        itemError.textContent = message || '';
        itemError.classList.toggle('hidden', !message);
    }

    function setInvalid(el, invalid) {
        if (!el) return;
        if (invalid) {
            el.setAttribute('aria-invalid', 'true');
        } else {
            el.removeAttribute('aria-invalid');
        }
    }

    function setFieldError(name, message) {
        var el = form.querySelector('[data-error-for="' + name + '"]');
        var input = form.elements[name];
        if (el) {
            el.textContent = message || '';
            el.classList.toggle('hidden', !message);
        }
        var wrap = form.querySelector('[data-wrap-for="' + name + '"]');
        [input, wrap].forEach(function (target) {
            if (!target) return;
            if (message) {
                target.setAttribute('aria-invalid', 'true');
            } else {
                target.removeAttribute('aria-invalid');
            }
        });
    }

    ['tanggal', 'pelanggan_id', 'ongkir'].forEach(function (name) {
        var input = form.elements[name];
        if (!input) return;
        ['input', 'change'].forEach(function (evt) {
            input.addEventListener(evt, function () {
                if (input.hasAttribute('aria-invalid')) setFieldError(name, '');
            });
        });
    });

    tbody.addEventListener('change', function (e) {
        var row = e.target.closest('[data-row]');
        if (!row || e.target.getAttribute('data-field') !== 'barang') return;

        var b = byId[e.target.value];
        fillDetail(row, b);
        field(row, 'harga').value = b ? M.format(b.harga_jual) : '';
        setInvalid(e.target, false);
        setInvalid(field(row, 'harga'), false);
        setInvalid(field(row, 'harga').parentElement, false);
        setItemError('');
        refreshOptions();
        recalc();
    });

    tbody.addEventListener('input', function (e) {
        if (e.target.getAttribute('data-field') !== 'harga') return;

        if (e.target.hasAttribute('aria-invalid')) {
            setInvalid(e.target, false);
            setInvalid(e.target.parentElement, false);
            setItemError('');
        }
        recalc();
    });

    tbody.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-remove]');
        if (!btn) return;
        var row = btn.closest('[data-row]');

        if (rows().length === 1) {
            field(row, 'barang').value = '';
            fillDetail(row, null);
            field(row, 'harga').value = '';
        } else {
            row.remove();
        }
        setItemError('');
        refreshOptions();
        recalc();
    });

    document.getElementById('btn-add-row').addEventListener('click', function () {
        var row = addRow();
        field(row, 'barang').focus();
    });

    ongkirInput.addEventListener('input', recalc);

    form.addEventListener('submit', function (e) {
        var valid = true;

        var tanggal = form.elements['tanggal'];
        var pelanggan = form.elements['pelanggan_id'];
        var ongkir = form.elements['ongkir'];
        setFieldError('tanggal', !tanggal.value ? 'Tanggal penjualan wajib diisi.' : '');
        setFieldError('pelanggan_id', !pelanggan.value ? 'Pelanggan wajib dipilih.' : '');
        setFieldError('ongkir', !ongkir.value.trim() ? 'Ongkir wajib diisi.' : '');
        if (!tanggal.value || !pelanggan.value || !ongkir.value.trim()) valid = false;

        var message = '';
        var list = rows();
        if (!list.length) message = 'Tambahkan minimal satu barang.';

        list.forEach(function (row) {
            var select = field(row, 'barang');
            var harga = M.parse(field(row, 'harga').value);
            var hargaInput = field(row, 'harga');
            var badBarang = !select.value;
            var badHarga = !badBarang && harga <= 0;

            if (badBarang) {
                message = message || 'Pilih barang pada setiap baris, atau hapus baris yang kosong.';
            } else if (badHarga) {
                message = message || 'Harga jual minimal 1.';
            }

            setInvalid(select, badBarang);
            setInvalid(hargaInput, badHarga);
            setInvalid(hargaInput.parentElement, badHarga);
        });

        setItemError(message);
        if (message) valid = false;

        if (!valid) {
            e.preventDefault();
            var firstError = form.querySelector('input[aria-invalid="true"], select[aria-invalid="true"]');
            if (firstError) firstError.focus();
            return;
        }

        btnSimpan.disabled = true;
    });

    if (oldItems.length) {
        oldItems.forEach(function (item) { addRow(item); });
    } else {
        addRow();
    }
    M.applyAll(form);
    recalc();
})();
