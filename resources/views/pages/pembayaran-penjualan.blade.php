@extends('layouts.app')

@section('title', 'Daftar Pembayaran')

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">Daftar Pembayaran</h1>
            </div>

            <nav aria-label="Breadcrumb" class="order-first sm:order-last">
                <ol class="flex items-center gap-1.5 text-stone-500">
                    <li>
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}"
                           class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            Dashboard
                        </a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </li>
                    <li>
                        <a class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            Penjualan
                        </a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </li>
                    <li class="font-medium text-stone-900" aria-current="page">Daftar Pembayaran</li>
                </ol>
            </nav>
        </div>

        @if (session('error'))
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 overflow-hidden rounded-xl border border-stone-300 bg-white">
            <div class="flex flex-col gap-3 border-b border-stone-300 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('pembayaran-penjualan.index') }}" class="relative w-full sm:max-w-xs">
                        <input type="hidden" name="metode" value="{{ request('metode') }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                            placeholder="Cari kode pembayaran / penjualan"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 pl-9 pr-3 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    </form>

                    <form method="GET" action="{{ route('pembayaran-penjualan.index') }}" class="w-full sm:w-48">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <select name="metode" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 px-3 text-stone-900 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                            <option value="">Semua Metode</option>
                            @foreach ($metode as $m)
                                <option value="{{ $m->value }}" {{ request('metode') == $m->value ? 'selected' : '' }}>
                                    {{ $m->label() }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <form method="GET" action="{{ route('pembayaran-penjualan.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="metode" value="{{ request('metode') }}">

                        <x-daterange
                            name-start="tanggal_mulai"
                            name-end="tanggal_selesai"
                            :start="request('tanggal_mulai')"
                            :end="request('tanggal_selesai')"
                        />
                    </form>

                    <a href="{{ route('pembayaran-penjualan.index') }}"
                    class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-center font-medium text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                        Reset
                    </a>
                </div>

                <button type="button" data-pembayaran-tambah
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Tambah Pembayaran
                    </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-brand/5 text-stone-600 border-b border-stone-300">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode Pembayaran</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode Penjualan</th>
                            <th scope="col" class="px-4 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nominal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Metode</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @forelse ($pembayaran as $p)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">
                                    {{ $pembayaran->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-stone-700">
                                    {{ $p->kode }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-stone-900">
                                    {{ $p->penjualan->kode }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-stone-700">
                                    {{ $p->tanggal->translatedFormat('d F Y') }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-stone-700">
                                    {{ format_rupiah($p->nominal) }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-stone-700">
                                    {{ $p->metode_pembayaran->label() }}
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            data-menu-toggle="menu-pembayaran-{{ $p->getKey() }}"
                                            aria-haspopup="menu"
                                            aria-expanded="false"
                                            aria-label="Aksi untuk {{ $p->kode }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <circle cx="12" cy="5" r="1.75" />
                                            <circle cx="12" cy="12" r="1.75" />
                                            <circle cx="12" cy="19" r="1.75" />
                                        </svg>
                                    </button>

                                    <div id="menu-pembayaran-{{ $p->getKey() }}" role="menu" hidden class="fixed z-50 w-44 rounded-lg border border-stone-300 bg-white p-1 text-left shadow-lg">
                                        <button type="button" role="menuitem" data-pembayaran-detail data-pembayaran="{{ json_encode($p->payload) }}" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Detail
                                        </button>

                                        <button type="button" role="menuitem" data-pembayaran-ubah data-pembayaran="{{ json_encode($p->payload) }}" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <form action="{{ route('pembayaran-penjualan.destroy', $p) }}" method="POST"
                                            data-confirm="Hapus pembayaran {{ $p->kode }}? Data yang dihapus tidak bisa dikembalikan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    role="menuitem"
                                                    class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-red-600 transition hover:bg-red-50 focus:bg-red-50 focus:outline-none">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                                    <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand/10 text-brand">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card preview-icon">
                                                <rect width="20" height="14" x="2" y="5" rx="2"/>
                                                <line x1="2" x2="22" y1="10" y2="10"/>
                                                <path d="M6 14h2"/>
                                            </svg>
                                        </span>

                                        @if (request()->filled('q'))
                                            <p class="mt-4 font-medium text-stone-900">Pembayaran tidak ditemukan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tidak ada hasil untuk "{{ request('q') }}". Coba kata kunci lain.
                                            </p>
                                            <a href="{{ route('pembayaran-penjualan.index') }}"
                                               class="mt-4 font-medium text-link hover:text-link-hover hover:underline">
                                                Tampilkan semua pembayaran
                                            </a>
                                        @else
                                            <p class="mt-4 font-medium text-stone-900">Belum ada pembayaran</p>
                                            <p class="mt-1 text-stone-500">
                                                Tambahkan pembayaran pertama Anda untuk mulai mencatat pembayaran.
                                            </p>
                                            <button type="button" data-pembayaran-tambah
                                                    class="mt-4 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover">
                                                Tambah Pembayaran
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$pembayaran" />
        </div>
    </div>

    <div id="modal-pembayaran" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
        role="dialog" aria-modal="true" aria-labelledby="modal-pembayaran-judul">
        <div class="flex max-h-[92vh] w-full max-w-xl flex-col rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-stone-200 px-6 py-4">
                <h2 id="modal-pembayaran-judul" class="text-lg font-semibold text-stone-900"></h2>
                <button type="button" data-modal-tutup aria-label="Tutup" class="rounded-lg p-1.5 text-stone-500 hover:bg-stone-100">
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5.3 5.3a1 1 0 0 1 1.4 0L10 8.6l3.3-3.3a1 1 0 1 1 1.4 1.4L11.4 10l3.3 3.3a1 1 0 0 1-1.4 1.4L10 11.4l-3.3 3.3a1 1 0 0 1-1.4-1.4L8.6 10 5.3 6.7a1 1 0 0 1 0-1.4Z"/>
                    </svg>
                </button>
            </div>

            <form id="form-pembayaran" data-jenis="penjualan" method="POST" action="{{ route('pembayaran-penjualan.store') }}"
                data-url-store="{{ route('pembayaran-penjualan.store') }}" data-kode-bayar="{{ $kode }}"
                novalidate class="flex min-h-0 flex-1 flex-col">
                @csrf
                <input type="hidden" name="_method" value="PUT" id="field-method" disabled>

                <div class="flex-1 space-y-7 overflow-y-auto px-6 py-5">

                    <section>
                        <label for="penjualan_id" class="mb-1.5 block font-medium text-stone-700">Kode Penjualan <span class="text-red-600">*</span></label>
                        <select id="penjualan_id" name="penjualan_id" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500/25">
                            <option value="">Pilih Penjualan</option>
                            @foreach ($penjualan as $p)
                                <option value="{{ $p->id }}"
                                    data-kode-jual="{{ $p->kode }}"
                                    data-tanggal="{{ $p->tanggal }}"
                                    data-total="{{ $p->total }}"
                                    data-pelanggan="{{ $p->pelanggan->nama }}">
                                    {{ $p->kode }} — {{ $p->pelanggan->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p data-error="penjualan_id" class="mt-1 hidden text-xs text-red-600"></p>

                        <p id="f-kosong" class="mt-3 rounded-xl border border-dashed border-stone-300 px-4 py-7 text-center text-sm text-stone-500">
                            Tagihan akan muncul di sini setelah kamu memilih penjualan.
                        </p>

                        <div id="f-tiket" class="mt-3 hidden rounded-xl border border-stone-200 bg-stone-50">
                            <div class="grid grid-cols-2 gap-x-6 gap-y-4 p-5">
                                <div class="col-span-2 flex items-center gap-3">
                                    <div id="f-inisial" class="flex size-10 items-center justify-center rounded-full bg-brand/10 text-sm font-semibold text-brand"></div>
                                    <div>
                                        <p class="text-sm text-stone-500">Pelanggan</p>
                                        <p id="f-pelanggan" class="font-medium text-stone-900"></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-stone-500">Kode Penjualan</p>
                                    <p id="f-kode-jual" class="font-medium text-stone-900"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-stone-500">Tanggal Penjualan</p>
                                    <p id="f-tgl-jual" class="font-medium text-stone-900"></p>
                                </div>
                            </div>
                            <div class="relative mx-4 border-t-2 border-dashed border-stone-300 before:absolute before:-left-[27px] before:-top-[11px] before:size-5 before:rounded-full before:bg-white after:absolute after:-right-[27px] after:-top-[11px] after:size-5 after:rounded-full after:bg-white"></div>
                            <div class="flex items-end justify-between gap-3 px-5 pb-5 pt-4">
                                <p class="text-sm text-stone-500">Total Tagihan</p>
                                <p id="f-total" class="text-xl font-semibold leading-none text-brand"></p>
                            </div>
                        </div>
                    </section>

                    <div class="space-y-6 overflow-y-auto">
                        <div>
                            <label for="nominal" class="mb-1.5 block font-medium text-stone-700">Nominal Bayar</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-stone-500">Rp</span>
                                <input id="nominal" type="text" readonly placeholder="0" class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 py-2.5 pl-8 pr-3 text-stone-500 placeholder:text-stone-400 focus:outline-none">
                            </div>
                            <p class="mt-1 text-xs text-stone-500">Sesuai total tagihan, tidak bisa diubah.</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="kode-bayar" class="mb-1.5 block font-medium text-stone-700">Kode Pembayaran</label>
                                <input id="kode-bayar" type="text" readonly aria-describedby="hint-kode-bayar" class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 placeholder:text-stone-400 focus:outline-none">
                                <p class="mt-1.5 text-xs text-stone-500">Terisi saat data disimpan.</p>
                            </div>
                            <div>
                                <label for="tanggal" class="mb-1.5 block font-medium text-stone-700">Tanggal Bayar</label>
                                <input id="tanggal" name="tanggal" type="date" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500/25">
                                <p data-error="tanggal" class="mt-1 hidden text-xs text-red-600"></p>
                            </div>
                        </div>

                        <div>
                            <span class="mb-1.5 block font-medium text-stone-700">Metode Pembayaran <span class="text-red-600">*</span></label>
                            <div data-wrap-for="metode_pembayaran" class="grid grid-cols-2 gap-2">
                                @foreach ($metode as $m)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="{{ $m->value }}" class="peer sr-only">
                                        <span class="block rounded-lg border border-stone-300 px-2 py-2.5 text-center text-sm text-stone-700 peer-checked:border-brand peer-checked:bg-brand/10 peer-checked:font-semibold peer-checked:text-brand peer-focus-visible:ring-2 peer-focus-visible:ring-brand/25">{{ $m->label() }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p data-error="metode_pembayaran" class="mt-1 hidden text-xs text-red-600"></p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-stone-200 px-6 py-4">
                    <button type="button" data-modal-tutup
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                        Batal
                    </button>
                    <button type="submit" id="btn-simpan"
                        class="rounded-lg bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-brand/25 disabled:opacity-60"></button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-detail-pembayaran" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
        role="dialog" aria-modal="true" aria-label="Bukti pembayaran">
        <div class="max-h-[92vh] w-full max-w-md overflow-y-auto">
            <div class="takik takik-bawah relative rounded-t-2xl bg-white px-6 pb-6 pt-8 text-center">
                <button type="button" data-modal-tutup aria-label="Tutup" class="absolute right-3 top-3 rounded-lg p-1.5 text-stone-400 transition hover:bg-stone-100 hover:text-stone-70 s:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5.3 5.3a1 1 0 0 1 1.4 0L10 8.6l3.3-3.3a1 1 0 1 1 1.4 1.4L11.4 10l3.3 3.3a1 1 0 0 1-1.4 1.4L10 11.4l-3.3 3.3a1 1 0 0 1-1.4-1.4L8.6 10 5.3
                        6.7a1 1 0 0 1 0-1.4Z"/>
                    </svg>
                </button>
                <p id="d-nominal" class="text-4xl font-semibold leading-tight text-stone-900"></p>
                <p id="d-sub" class="mt-2 text-sm text-stone-900"></p>
            </div>

            <div class="takik takik-atas-bawah relative bg-white px-6 py-3">
                <div class="absolute inset-x-6 top-0 border-t-2 border-dashed border-stone-300"></div>
                <dl class="text-sm">
                    <div class="flex justify-between gap-4 py-2.5"><dt class="text-stone-500">Kode Pembayaran</dt><dd id="d-kode-bayar" class="text-right font-medium text-stone-900"></dd></div>
                    <div class="flex justify-between gap-4 py-2.5"><dt class="text-stone-500">Tanggal</dt><dd id="d-tanggal" class="text-right font-medium text-stone-900"></dd></div>
                    <div class="flex justify-between gap-4 py-2.5"><dt class="text-stone-500">Metode Pembayaran</dt><dd id="d-metode" class="text-right font-medium text-stone-900"></dd></div>
                    <div class="flex justify-between gap-4 py-2.5"><dt class="text-stone-500">Nama Pelanggan</dt><dd id="d-pelanggan" class="text-right font-medium text-stone-900"></dd></div>
                    <div class="flex justify-between gap-4 py-2.5"><dt class="text-stone-500">Kode Penjualan</dt><dd id="d-kode-jual" class="text-right font-medium text-stone-900"></dd></div>
                </dl>
            </div>

            <div class="takik takik-atas relative rounded-b-2xl bg-white px-6 pb-6 pt-6 text-center">
                <div class="absolute inset-x-6 top-0 border-t-2 border-dashed border-stone-300"></div>
                <p class="text-stone-500">Simpan resi ini sebagai bukti pembayaran</p>
                <p class="mt-3 font-bold text-stone-900">TRISTANTI STORE</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pembayaran.js')
@endpush
