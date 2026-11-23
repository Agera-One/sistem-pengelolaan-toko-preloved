/**
 * money-input.js
 * Formater angka (pemisah ribuan titik) yang bisa dipakai ulang di halaman mana pun.
 *
 * Cara pakai:
 *   1. Muat file ini sebelum script halaman:
 *        <script src="/js/money-input.js"></script>
 *   2. Pada input, cukup tambahkan atribut data-money:
 *        <input type="text" inputmode="numeric" name="harga_beli" data-money>
 *      Input yang ditambahkan dinamis (template/row baru) juga otomatis ikut terformat.
 *
 * Helper yang tersedia lewat window.MoneyInput:
 *   MoneyInput.digits('1.500.000')   -> '1500000'   (hanya angka, string)
 *   MoneyInput.parse('1.500.000')    -> 1500000     (number)
 *   MoneyInput.format('1500000')     -> '1.500.000' (untuk isi input)
 *   MoneyInput.rupiah(1500000)       -> 'Rp1.500.000' (untuk teks tampilan)
 *   MoneyInput.apply(el)             -> format nilai satu input sekarang
 *   MoneyInput.applyAll(root)        -> format semua [data-money] di dalam root
 *   MoneyInput.strip(form)           -> ubah semua [data-money] di form jadi angka murni
 *
 * Saat form disubmit secara normal (tidak di-preventDefault oleh validasi),
 * nilai [data-money] otomatis dikembalikan ke angka murni agar server menerima "1500000".
 * Untuk submit via fetch/FormData, panggil MoneyInput.strip(form) atau MoneyInput.parse().
 */

(function (global) {
    'use strict';

    var SELECTOR = '[data-money]';

    function digits(value) {
        return String(value == null ? '' : value).replace(/\D/g, '');
    }

    function format(value) {
        return digits(value).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function parse(value) {
        return Number(digits(value)) || 0;
    }

    function rupiah(value) {
        return 'Rp' + (Number(value) || 0).toLocaleString('id-ID');
    }

    function apply(el) {
        if (!el) return;

        var before = el.value;
        var after = format(before);
        if (before === after) return;

        var caret = el.selectionStart;
        var isFocused = document.activeElement === el;
        var digitsBeforeCaret = caret == null ? null : digits(before.slice(0, caret)).length;

        el.value = after;

        if (isFocused && digitsBeforeCaret !== null && el.setSelectionRange) {
            var pos = 0;
            var seen = 0;
            while (pos < after.length && seen < digitsBeforeCaret) {
                if (/\d/.test(after.charAt(pos))) seen++;
                pos++;
            }
            try {
                el.setSelectionRange(pos, pos);
            } catch (e) {
            }
        }
    }

    function applyAll(root) {
        (root || document).querySelectorAll(SELECTOR).forEach(apply);
    }

    function strip(form) {
        if (!form) return;
        form.querySelectorAll(SELECTOR).forEach(function (el) {
            el.value = digits(el.value);
        });
    }

    document.addEventListener('input', function (e) {
        if (e.target && e.target.matches && e.target.matches(SELECTOR)) {
            apply(e.target);
        }
    });

    document.addEventListener('submit', function (e) {
        if (!e.defaultPrevented) strip(e.target);
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            applyAll();
        });
    } else {
        applyAll();
    }

    global.MoneyInput = {
        digits: digits,
        format: format,
        parse: parse,
        rupiah: rupiah,
        apply: apply,
        applyAll: applyAll,
        strip: strip,
    };
})(window);
