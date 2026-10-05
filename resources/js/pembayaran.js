const rupiah = (n) => 'Rp' + new Intl.NumberFormat('id-ID').format(n);

const tanggalIndo = (iso) =>
    iso
        ? new Date(iso.slice(0, 10) + 'T00:00:00').toLocaleDateString('id-ID', {
              day: 'numeric',
              month: 'long',
              year: 'numeric',
          })
        : '-';

const inisial = (nama) =>
    (nama || '')
        .split(' ')
        .slice(0, 2)
        .map((w) => w[0])
        .join('')
        .toUpperCase();

document.addEventListener('DOMContentLoaded', () => {
    const modalForm = document.getElementById('modal-pembayaran');
    const modalDetail = document.getElementById('modal-detail-pembayaran');
    if (!modalForm || !modalDetail) return;

    const $ = (id) => document.getElementById(id);
    const form = $('form-pembayaran');
    const select = $('pembelian_id');
    const btnSimpan = $('btn-simpan');

    const tutupMenu = () => {
        document.querySelectorAll('[role="menu"]').forEach((menu) => (menu.hidden = true));
        document.querySelectorAll('[data-menu-toggle]').forEach((t) => t.setAttribute('aria-expanded', 'false'));
    };
    const buka = (m) => {
        tutupMenu();
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };
    const tutup = (m) => {
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };
    const tutupSemua = () => [modalForm, modalDetail].forEach(tutup);

    const opsi = (id) => select.querySelector(`option[value="${id}"]`);

    function resetError() {
        form.querySelectorAll('[data-error]').forEach((p) => {
            p.textContent = '';
            p.classList.add('hidden');
        });
    }

    function tampilError(nama, pesan) {
        const p = form.querySelector(`[data-error="${nama}"]`);
        if (!p) return;
        p.textContent = pesan;
        p.classList.remove('hidden');
    }

    function bersihkanOpsiTemporary() {
        const temp = select.querySelector('option[data-temp="true"]');
        if (temp) temp.remove();
    }

    function tampilkanTiket() {
        const o = select.value ? opsi(select.value) : null;
        $('f-kosong').classList.toggle('hidden', !!o);$('f-tiket').classList.toggle('hidden', !o);

        if (!o) {
            $('nominal').value = '';
            return;
        }

        const total = Number(o.dataset.total);
        $('f-inisial').textContent = inisial(o.dataset.supplier);$('f-supplier').textContent = o.dataset.supplier;
        $('f-kode-beli').textContent = o.dataset.kodeBeli;
        $('f-tgl-beli').textContent = tanggalIndo(o.dataset.tanggal);
        $('f-total').textContent = rupiah(total);$('nominal').value = new Intl.NumberFormat('id-ID').format(total);
    }

    function bukaForm(data) {
        const ubah = !!data;
        resetError();
        form.reset();
        bersihkanOpsiTemporary();

        $('modal-pembayaran-judul').textContent = ubah ? 'Ubah pembayaran' : 'Tambah Pembayaran';
        btnSimpan.textContent = 'Simpan';
        form.action = ubah ? data.url_update : form.dataset.urlStore;
        $('field-method').disabled = !ubah;

        if (ubah) {
            if (!opsi(data.pembelian_id) && data.pembelian) {
                const opt = document.createElement('option');
                opt.value = data.pembelian.id;
                opt.dataset.kodeBeli = data.pembelian.kode;
                opt.dataset.tanggal = data.pembelian.tanggal;
                opt.dataset.total = data.pembelian.total;
                opt.dataset.supplier = data.pembelian.supplier;
                opt.dataset.temp = 'true';
                opt.textContent = `${data.pembelian.kode} — ${data.pembelian.supplier}`;
                select.appendChild(opt);
            }

            select.value = data.pembelian_id;
            $('kode-bayar').value = data.kode_bayar;
            $('tanggal').value = data.tanggal;
            const radio = form.querySelector(`[name="metode_pembayaran"][value="${data.metode_pembayaran}"]`);
            if (radio) radio.checked = true;
        } else {
            select.value = '';
            $('kode-bayar').value = form.dataset.kodeBayar;
            $('tanggal').value = new Date().toISOString().slice(0, 10);
        }

        tampilkanTiket();
        buka(modalForm);
    }

    function bukaDetail(data) {
        const p = data.pembelian;

        $('d-nominal').textContent = rupiah(data.nominal);
        $('d-sub').textContent = `${data.kode_bayar} · ${tanggalIndo(data.tanggal)}`;
        $('d-kode-bayar').textContent = data.kode_bayar;
        $('d-tanggal').textContent = tanggalIndo(data.tanggal);
        $('d-metode').textContent = data.metode_pembayaran;

        $('d-supplier').textContent = p ? p.supplier : '-';
        $('d-kode-beli').textContent = p ? p.kode : '-';

        buka(modalDetail);
    }

    document.addEventListener('click', (e) => {
        const toggleBtn = e.target.closest('[data-menu-toggle]');
        if (toggleBtn) {
            e.stopPropagation();
            const targetId = toggleBtn.getAttribute('data-menu-toggle');
            const menu = document.getElementById(targetId);
            const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';

            tutupMenu();

            if (!isExpanded && menu) {
                toggleBtn.setAttribute('aria-expanded', 'true');
                menu.hidden = false;
            }
            return;
        }

        const tambah = e.target.closest('[data-pembayaran-tambah]');
        const ubah = e.target.closest('[data-pembayaran-ubah]');
        const detail = e.target.closest('[data-pembayaran-detail]');

        if (tambah) return bukaForm(null);
        if (ubah) return bukaForm(JSON.parse(ubah.dataset.pembayaran));
        if (detail) return bukaDetail(JSON.parse(detail.dataset.pembayaran));
        if (e.target.closest('[data-modal-tutup]')) tutupSemua();
        if (e.target === modalForm || e.target === modalDetail) tutupSemua();

        if (!e.target.closest('[role="menu"]')) {
            tutupMenu();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') tutupSemua();
    });

    select.addEventListener('change', () => {
        tampilkanTiket();
        const galat = form.querySelector('[data-error="pembelian_id"]');
        if (galat) {
            galat.textContent = '';
            galat.classList.add('hidden');
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        resetError();

        const wajib = [
            ['pembelian_id', 'Pilih pembelian yang dibayar.'],
            ['tanggal', 'Tanggal bayar wajib diisi.'],
            ['metode_pembayaran', 'Pilih metode pembayaran.'],
        ];
        let pertama = null;
        wajib.forEach(([nama, pesan]) => {
            if (!form.elements[nama].value) {
                tampilError(nama, pesan);
                pertama ??= form.querySelector(`[name="${nama}"]`);
            }
        });
        if (pertama) return pertama.focus();

        const teksTombol = btnSimpan.textContent;
        btnSimpan.disabled = true;
        btnSimpan.textContent = 'Menyimpan...';

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                body: new FormData(form),
            });

            if (res.status === 422) {
                const { errors } = await res.json();
                Object.entries(errors).forEach(([nama, pesan]) => tampilError(nama, pesan[0]));
                return;
            }
            if (!res.ok) throw new Error('Gagal menyimpan');

            window.location.reload();
        } catch (err) {
            tampilError('metode_pembayaran', 'Terjadi kesalahan, coba lagi.');
        } finally {
            btnSimpan.disabled = false;
            btnSimpan.textContent = teksTombol;
        }
    });
});
