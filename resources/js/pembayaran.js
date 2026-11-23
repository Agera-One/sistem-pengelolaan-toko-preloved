const { rupiah, format: formatAngka } = window.MoneyInput;

const JENIS = {
    pembelian: {
        field: 'pembelian_id',
        relasi: 'pembelian',
        sufiks: 'beli',
        pihak: 'supplier'
    },
    penjualan: {
        field: 'penjualan_id',
        relasi: 'penjualan',
        sufiks: 'jual',
        pihak: 'pelanggan'
    },
};

const FIELD_WAJIB = (config) => [
    [config.field, config.label.wajib],
    ['tanggal', 'Tanggal bayar wajib diisi.'],
    ['metode_pembayaran', 'Pilih metode pembayaran.'],
];

const resolveConfig = (jenis) => {
    const dasar = JENIS[jenis];
    if (!dasar) return null;

    const huruf = dasar.relasi.charAt(0).toUpperCase() + dasar.relasi.slice(1);

    return {
        ...dasar,
        datasetKode: `kode${dasar.sufiks.charAt(0).toUpperCase()}${dasar.sufiks.slice(1)}`,
        idKode: `f-kode-${dasar.sufiks}`,
        idTanggal: `f-tgl-${dasar.sufiks}`,
        idDetailKode: `d-kode-${dasar.sufiks}`,
        idPihak: `f-${dasar.pihak}`,
        idDetailPihak: `d-${dasar.pihak}`,
        label: { wajib: `${huruf} wajib diisi.` },
    };
};

/* -------------------------------------------------------------------------- */
/* Helper murni (tanpa akses DOM)                                             */
/* -------------------------------------------------------------------------- */

const tanggalIndo = (iso) =>
    iso
        ? new Date(`${iso.slice(0, 10)}T00:00:00`).toLocaleDateString('id-ID', {
              day: 'numeric',
              month: 'long',
              year: 'numeric',
          })
        : '-';

const inisial = (nama) =>
    (nama || '')
        .split(' ')
        .slice(0, 2)
        .map((kata) => kata[0])
        .join('')
        .toUpperCase();

const hariIni = () => new Date().toISOString().slice(0, 10);

const byId = (id) => document.getElementById(id);

const setText = (id, teks) => {
    byId(id).textContent = teks;
};

/* -------------------------------------------------------------------------- */
/* Menu aksi (titik tiga)                                                     */
/* -------------------------------------------------------------------------- */

function createMenu() {
    const tutup = () => {
        document.querySelectorAll('[role="menu"]').forEach((menu) => (menu.hidden = true));
        document
            .querySelectorAll('[data-menu-toggle]')
            .forEach((toggle) => toggle.setAttribute('aria-expanded', 'false'));
    };

    const handleClick = (e) => {
        const toggle = e.target.closest('[data-menu-toggle]');

        if (toggle) {
            e.stopPropagation();
            const menu = byId(toggle.dataset.menuToggle);
            const sedangTerbuka = toggle.getAttribute('aria-expanded') === 'true';

            tutup();

            if (!sedangTerbuka && menu) {
                toggle.setAttribute('aria-expanded', 'true');
                menu.hidden = false;
            }
            return true;
        }

        if (!e.target.closest('[role="menu"]')) tutup();
        return false;
    };

    return { tutup, handleClick };
}

/* -------------------------------------------------------------------------- */
/* Modal                                                                      */
/* -------------------------------------------------------------------------- */

function createModals(modals, menu) {
    const buka = (modal) => {
        menu.tutup();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const tutup = (modal) => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    const tutupSemua = () => modals.forEach(tutup);
    const adalahBackdrop = (el) => modals.includes(el);

    return { buka, tutupSemua, adalahBackdrop };
}

/* -------------------------------------------------------------------------- */
/* Error & validasi form                                                      */
/* -------------------------------------------------------------------------- */

function createFormErrors(form, fields) {
    const targetInvalid = (nama) => form.querySelector(`[data-wrap-for="${nama}"]`) || form.elements[nama];
    const pesanEl = (nama) => form.querySelector(`[data-error="${nama}"]`);

    const setInvalid = (nama, invalid) => {
        const el = targetInvalid(nama);
        if (!el) return;
        if (invalid) el.setAttribute('aria-invalid', 'true');
        else el.removeAttribute('aria-invalid');
    };

    const hapus = (nama) => {
        const p = pesanEl(nama);
        if (p) {
            p.textContent = '';
            p.classList.add('hidden');
        }
        setInvalid(nama, false);
    };

    const hapusSemua = () => fields.forEach(hapus);

    const tampil = (nama, pesan) => {
        const p = pesanEl(nama);
        if (!p) return;
        p.textContent = pesan;
        p.classList.remove('hidden');
        setInvalid(nama, true);
    };

    return { hapus, hapusSemua, tampil, adalahField: (nama) => fields.includes(nama) };
}

/* -------------------------------------------------------------------------- */
/* Halaman Pembayaran                                                         */
/* -------------------------------------------------------------------------- */

function initPembayaran() {
    const modalForm = byId('modal-pembayaran');
    const modalDetail = byId('modal-detail-pembayaran');
    const form = byId('form-pembayaran');
    if (!modalForm || !modalDetail || !form) return;

    const config = resolveConfig(form.dataset.jenis);
    if (!config) {
        console.error(`[pembayaran] data-jenis tidak dikenal: "${form.dataset.jenis}"`);
        return;
    }

    const select = byId(config.field);
    const btnSimpan = byId('btn-simpan');

    const menu = createMenu();
    const modal = createModals([modalForm, modalDetail], menu);
    const errors = createFormErrors(
        form,
        FIELD_WAJIB(config).map(([nama]) => nama),
    );

    /* ---------- Select & tiket tagihan ---------- */

    const cariOpsi = (id) => select.querySelector(`option[value="${id}"]`);

    const hapusOpsiTemporary = () => select.querySelector('option[data-temp="true"]')?.remove();

    const pastikanOpsiAda = (data) => {
        const relasi = data[config.relasi];
        if (cariOpsi(data[config.field]) || !relasi) return;

        const opt = document.createElement('option');
        opt.value = relasi.id;
        opt.dataset[config.datasetKode] = relasi.kode;
        opt.dataset.tanggal = relasi.tanggal;
        opt.dataset.total = relasi.total;
        opt.dataset[config.pihak] = relasi[config.pihak];
        opt.dataset.temp = 'true';
        opt.textContent = `${relasi.kode} — ${relasi[config.pihak]}`;
        select.appendChild(opt);
    };
    const tampilkanTiket = () => {
        const opsi = select.value ? cariOpsi(select.value) : null;

        byId('f-kosong').classList.toggle('hidden', !!opsi);
        byId('f-tiket').classList.toggle('hidden', !opsi);

        if (!opsi) {
            byId('nominal').value = '';
            return;
        }

        const total = Number(opsi.dataset.total);
        const pihak = opsi.dataset[config.pihak];

        setText('f-inisial', inisial(pihak));
        setText(config.idPihak, pihak);
        setText(config.idKode, opsi.dataset[config.datasetKode]);
        setText(config.idTanggal, tanggalIndo(opsi.dataset.tanggal));
        setText('f-total', rupiah(total));
        byId('nominal').value = formatAngka(total);
    };

    /* ---------- Modal form (tambah / ubah) ---------- */

    const isiFormUbah = (data) => {
        pastikanOpsiAda(data);

        select.value = data[config.field];
        byId('kode-bayar').value = data.kode_bayar;
        byId('tanggal').value = data.tanggal;

        const radio = form.querySelector(`[name="metode_pembayaran"][value="${data.metode_pembayaran}"]`);
        if (radio) radio.checked = true;
    };

    const isiFormTambah = () => {
        select.value = '';
        byId('kode-bayar').value = form.dataset.kodeBayar;
        byId('tanggal').value = hariIni();
    };

    const bukaForm = (data = null) => {
        const mode = data ? 'ubah' : 'tambah';

        errors.hapusSemua();
        form.reset();
        hapusOpsiTemporary();

        setText('modal-pembayaran-judul', mode === 'ubah' ? 'Ubah pembayaran' : 'Tambah Pembayaran');
        btnSimpan.textContent = 'Simpan';
        form.action = mode === 'ubah' ? data.url_update : form.dataset.urlStore;
        byId('field-method').disabled = mode === 'tambah';

        if (mode === 'ubah') isiFormUbah(data);
        else isiFormTambah();

        tampilkanTiket();
        modal.buka(modalForm);
    };

    /* ---------- Modal detail ---------- */

    const bukaDetail = (data) => {
        const relasi = data[config.relasi];

        setText('d-nominal', rupiah(data.nominal));
        setText('d-sub', `${data.kode_bayar} · ${tanggalIndo(data.tanggal)}`);
        setText('d-kode-bayar', data.kode_bayar);
        setText('d-tanggal', tanggalIndo(data.tanggal));
        setText('d-metode', data.metode_label);
        setText(config.idDetailPihak, relasi ? relasi[config.pihak] : '-');
        setText(config.idDetailKode, relasi ? relasi.kode : '-');

        modal.buka(modalDetail);
    };

    /* ---------- Submit ---------- */

    const validasi = () => {
        let pertama = null;

        FIELD_WAJIB(config).forEach(([nama, pesan]) => {
            if (form.elements[nama].value) return;
            errors.tampil(nama, pesan);
            pertama ??= form.querySelector(`[name="${nama}"]`);
        });

        return pertama;
    };

    const kirim = async () => {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
            body: new FormData(form),
        });

        if (res.status === 422) {
            const { errors: galat } = await res.json();
            Object.entries(galat).forEach(([nama, pesan]) => errors.tampil(nama, pesan[0]));
            return;
        }

        if (!res.ok) throw new Error('Gagal menyimpan');

        window.location.reload();
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        errors.hapusSemua();

        const fieldPertamaError = validasi();
        if (fieldPertamaError) return fieldPertamaError.focus();

        const teksTombol = btnSimpan.textContent;
        btnSimpan.disabled = true;
        btnSimpan.textContent = 'Menyimpan...';

        try {
            await kirim();
        } catch {
            errors.tampil('metode_pembayaran', 'Terjadi kesalahan, coba lagi.');
        } finally {
            btnSimpan.disabled = false;
            btnSimpan.textContent = teksTombol;
        }
    };

    /* ---------- Event listener ---------- */

    const handleClick = (e) => {
        if (menu.handleClick(e)) return;

        const tombol = (atribut) => e.target.closest(`[${atribut}]`);

        const ubah = tombol('data-pembayaran-ubah');
        const detail = tombol('data-pembayaran-detail');

        if (tombol('data-pembayaran-tambah')) return bukaForm();
        if (ubah) return bukaForm(JSON.parse(ubah.dataset.pembayaran));
        if (detail) return bukaDetail(JSON.parse(detail.dataset.pembayaran));

        if (tombol('data-modal-tutup') || modal.adalahBackdrop(e.target)) modal.tutupSemua();
    };

    document.addEventListener('click', handleClick);
    document.addEventListener('keydown', (e) => e.key === 'Escape' && modal.tutupSemua());

    select.addEventListener('change', tampilkanTiket);

    form.addEventListener('input', (e) => {
        if (errors.adalahField(e.target.name)) errors.hapus(e.target.name);
    });

    form.addEventListener('submit', handleSubmit);
}

document.addEventListener('DOMContentLoaded', initPembayaran);
