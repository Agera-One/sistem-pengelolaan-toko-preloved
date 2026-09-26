@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">Daftar Pesanan</h1>
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
                            Pembelian
                        </a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </li>
                    <li class="font-medium text-stone-900" aria-current="page">Daftar Pesanan</li>
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
                <form method="GET" action="{{ route('pembelian.pesanan.index') }}"
                      class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:max-w-sm">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <label for="q" class="sr-only">Cari pembelian</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                               placeholder="Cari kode, supplier, atau status"
                               class="w-full rounded-lg border border-stone-300 bg-white py-2 pl-9 pr-3 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    </div>

                    <x-daterange
                        name-start="tanggal_mulai"
                        name-end="tanggal_selesai"
                        :start="request('tanggal_mulai')"
                        :end="request('tanggal_selesai')"
                    />

                    <div class="flex items-center gap-2">
                        <a href="{{ route('pembelian.pesanan.index') }}"
                            class="rounded-lg border border-stone-300 bg-white px-4 py-2 font-medium text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            Reset
                        </a>
                    </div>
                </form>

                <button type="button" data-modal-open="modal-tambah"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Pesanan
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-brand/5 text-stone-600 border-b border-stone-300">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode</th>
                            <th scope="col" class="px-4 py-3 font-medium">Supplier</th>
                            <th scope="col" class="px-4 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Total</th>
                            <th scope="col" class="px-4 py-3 font-medium">Status</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @forelse ($pembelian as $p)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">
                                    {{ $pembelian->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-stone-700">
                                    {{ $p->kode }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-900">
                                    {{ $p->supplier->nama }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-stone-700">
                                    {{ $p->tanggal->translatedFormat('d F Y') }}
                                </td>

                                <td class="max-w-xs truncate px-4 py-3.5 text-stone-700" title="{{ $p->alamat }}">
                                    Rp{{ number_format($p->total, 0, ',', '.') }}
                                </td>

                                @php
                                    $statusKey = strtolower($p->status);
                                    $statusBadge = match ($statusKey) {
                                        'belum bayar' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-300',
                                        'sudah bayar' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-300',
                                        default => 'bg-stone-100 text-stone-600 ring-1 ring-inset ring-stone-300',
                                    };
                                    $statusDot = match ($statusKey) {
                                        'belum bayar' => 'bg-red-500',
                                        'sudah bayar' => 'bg-emerald-500',
                                        default => 'bg-stone-400',
                                    };
                                @endphp
                                <td class="whitespace-nowrap px-4 py-3.5" title="{{ $p->status }}">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusBadge }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ $p->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            data-menu-toggle="menu-pembelian-{{ $p->getKey() }}"
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

                                    <div id="menu-pembelian-{{ $p->getKey() }}"
                                         role="menu"
                                         hidden
                                         class="fixed z-50 w-44 rounded-lg border border-stone-300 bg-white p-1 text-left shadow-lg">
                                        <button type="button" role="menuitem"
                                                data-detail-open="modal-detail"
                                                data-kode="{{ $p->kode }}"
                                                data-tanggal="{{ $p->tanggal->translatedFormat('d F Y') }}"
                                                data-status="{{ $p->status }}"
                                                data-total="Rp{{ number_format($p->total, 0, ',', '.') }}"
                                                data-suppliernama="{{ $p->supplier->nama ?? '-' }}"
                                                data-suppliertelepon="{{ $p->supplier->nomor_telepon ?? '-' }}"
                                                data-supplierkota="{{ $p->supplier->kota ?? '-' }}"
                                                data-usernama="{{ $p->user->name ?? '-' }}"
                                                data-useremail="{{ $p->user->email ?? '-' }}"
                                                data-dibuat="{{ $p->created_at?->translatedFormat('d F Y, H:i') }}"
                                                data-diperbarui="{{ $p->updated_at?->translatedFormat('d F Y, H:i') }}"
                                                data-items="{{ $p->detailPembelian->map(fn ($d) => [
                                                    'nama' => $d->barang->nama ?? '-',
                                                    'harga_beli' => (int) $d->harga_beli,
                                                    'harga_jual' => (int) $d->harga_jual,
                                                ])->toJson() }}"
                                                class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Detail
                                        </button>

                                        <button type="button" role="menuitem"
                                                data-edit-open="modal-ubah"
                                                data-action="{{ route('pembelian.pesanan.update', $p) }}"
                                                data-kode="{{ $p->kode }}"
                                                data-nama="{{ $p->nama }}"
                                                data-telepon="{{ $p->nomor_telepon }}"
                                                data-alamat="{{ $p->alamat }}"
                                                class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <form action="{{ route('pembelian.pesanan.destroy', $p) }}" method="POST"
                                              data-confirm="Hapus pesanan pembelian {{ $p->kode }}? Data yang dihapus tidak bisa dikembalikan.">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </span>

                                        @if (request()->filled('q'))
                                            <p class="mt-4 font-medium text-stone-900">Pesanan tidak ditemukan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tidak ada hasil untuk "{{ request('q') }}". Coba kata kunci lain.
                                            </p>
                                            <a href="{{ route('pembelian.pesanan.index') }}"
                                               class="mt-4 font-medium text-link hover:text-link-hover hover:underline">
                                                Tampilkan semua pesanan
                                            </a>
                                        @else
                                            <p class="mt-4 font-medium text-stone-900">Belum ada pesanan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tambahkan pesanan pertama Anda untuk mulai mencatat pembelian.
                                            </p>
                                            <button type="button" data-modal-open="modal-tambah"
                                                    class="mt-4 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover">
                                                Tambah Pesanan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$pembelian" />
        </div>
    </div>

    @php
        $field = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
    @endphp

    <dialog id="modal-tambah"
            aria-labelledby="modal-tambah-title"
            class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-3xl overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <form method="POST" action="{{ route('pembelian.pesanan.store') }}" id="form-tambah-pesanan" class="flex max-h-[90vh] flex-col" novalidate>
            @csrf

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-tambah-title" class="text-lg font-semibold">Tambah Pesanan Pembelian</h2>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6 overflow-y-auto px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label for="kode" class="mb-1.5 block text-stone-900">Kode pembelian</label>
                        <input type="text" id="kode" value="{{ $kode }}"
                                readonly aria-describedby="hint-kode"
                                class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 placeholder:text-stone-400 focus:outline-none">
                        <p id="hint-kode" class="mt-1.5 text-xs text-stone-500">Terisi otomatis saat disimpan.</p>
                    </div>

                    <div>
                        <label for="tanggal" class="mb-1.5 block text-stone-900">
                            Tanggal <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ now()->format('Y-m-d') }}"
                                data-label="Tanggal" data-rules="required|date"
                                aria-describedby="err-tanggal"
                                class="{{ $field }}">
                        <p id="err-tanggal" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>

                    <div>
                        <label for="supplier_id" class="mb-1.5 block text-stone-900">
                            Supplier <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <select id="supplier_id" name="supplier_id"
                                data-label="Supplier" data-rules="required"
                                aria-describedby="err-supplier_id"
                                class="{{ $field }}">
                            <option value="">Pilih supplier</option>
                            @foreach ($supplier as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        <p id="err-supplier_id" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="block text-stone-900">
                            Daftar Barang <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <button type="button" id="btn-tambah-barang"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-brand px-3 py-1.5 text-sm font-medium text-brand transition hover:bg-brand/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Tambah Barang
                        </button>
                    </div>

                    <div id="item-rows" class="space-y-3">
                        <div class="item-row grid grid-cols-1 gap-3 rounded-lg border border-stone-200 p-3 sm:grid-cols-[1fr_140px_140px_auto] sm:items-start" data-item-row>
                            <div>
                                <label class="sr-only">Barang</label>
                                <select name="barang_id[]" class="item-barang {{ $field }}">
                                    <option value="">Pilih barang</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->id }}"
                                                data-harga-beli="{{ $b->harga_beli ?? '' }}"
                                                data-harga-jual="{{ $b->harga_jual ?? '' }}">
                                            {{ $b->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="sr-only">Harga beli</label>
                                <input type="number" min="0" step="1" name="harga_beli[]" placeholder="Harga beli"
                                        class="item-harga-beli {{ $field }}">
                            </div>
                            <div>
                                <label class="sr-only">Harga jual</label>
                                <input type="number" min="0" step="1" name="harga_jual[]" placeholder="Harga jual"
                                        class="item-harga-jual {{ $field }}">
                            </div>
                            <div class="flex items-start justify-end sm:justify-center">
                                <button type="button"
                                        class="btn-hapus-item rounded-md p-2 text-stone-400 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand"
                                        aria-label="Hapus barang ini">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                        <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p id="err-items" class="mt-1.5 text-xs text-red-600" hidden>Lengkapi barang, harga beli, dan harga jual pada setiap baris.</p>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-stone-200 pt-4">
                    <span class="text-stone-500">Total</span>
                    <span id="total-display" class="text-lg font-semibold text-stone-900">Rp0</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-stone-300 bg-stone-50 px-6 py-4">
                <button type="button" data-modal-close
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                    Simpan pembelian
                </button>
            </div>
        </form>
    </dialog>

    <template id="item-row-template">
        <div class="item-row grid grid-cols-1 gap-3 rounded-lg border border-stone-200 p-3 sm:grid-cols-[1fr_140px_140px_auto] sm:items-start" data-item-row>
            <div>
                <label class="sr-only">Barang</label>
                <select name="barang_id[]" class="item-barang {{ $field }}">
                    <option value="">Pilih barang</option>
                    @foreach ($barang as $b)
                        <option value="{{ $b->id }}"
                                data-harga-beli="{{ $b->harga_beli ?? '' }}"
                                data-harga-jual="{{ $b->harga_jual ?? '' }}">
                            {{ $b->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="sr-only">Harga beli</label>
                <input type="number" min="0" step="1" name="harga_beli[]" placeholder="Harga beli"
                        class="item-harga-beli {{ $field }}">
            </div>
            <div>
                <label class="sr-only">Harga jual</label>
                <input type="number" min="0" step="1" name="harga_jual[]" placeholder="Harga jual"
                        class="item-harga-jual {{ $field }}">
            </div>
            <div class="flex items-start justify-end sm:justify-center">
                <button type="button"
                        class="btn-hapus-item rounded-md p-2 text-stone-400 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand"
                        aria-label="Hapus barang ini">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 6h18" />
                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                        <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <dialog id="modal-ubah"
            aria-labelledby="modal-ubah-title"
            class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <form method="POST" class="flex max-h-[90vh] flex-col" novalidate>
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-ubah-title" class="text-lg font-semibold">Ubah pembelian</h2>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6 overflow-y-auto px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="edit-kode" class="mb-1.5 block text-stone-900">Kode pembelian</label>
                        <input type="text" id="edit-kode" data-fill="kode"
                                readonly aria-describedby="hint-edit-kode"
                                class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 focus:outline-none">
                        <p id="hint-edit-kode" class="mt-1.5 text-xs text-stone-500">Kode tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label for="edit-nomor_telepon" class="mb-1.5 block text-stone-900">
                            Nomor telepon <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="tel" inputmode="tel" id="edit-nomor_telepon" name="nomor_telepon"
                                placeholder="08xxxxxxxxxx" autocomplete="off"
                                data-fill="telepon"
                                data-label="Nomor telepon" data-rules="required|max:15"
                                aria-describedby="err-edit-nomor_telepon"
                                class="{{ $field }}">
                        <p id="err-edit-nomor_telepon" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>
                </div>

                <div>
                    <label for="edit-nama" class="mb-1.5 block text-stone-900">
                        Nama lengkap <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input type="text" id="edit-nama" name="nama"
                            autocomplete="off" autofocus
                            data-fill="nama"
                            data-label="Nama lengkap" data-rules="required|string|max:255"
                            aria-describedby="err-edit-nama"
                            class="{{ $field }}">
                    <p id="err-edit-nama" class="mt-1.5 text-xs text-red-600" hidden></p>
                </div>

                <div>
                    <label for="edit-alamat" class="mb-1.5 block text-stone-900">
                        Alamat lengkap <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <textarea id="edit-alamat" name="alamat" rows="3"
                                placeholder="Jalan, nomor, kelurahan, kota"
                                data-fill="alamat"
                                data-label="Alamat lengkap" data-rules="required"
                                aria-describedby="err-edit-alamat"
                                class="{{ $field }}"></textarea>
                    <p id="err-edit-alamat" class="mt-1.5 text-xs text-red-600" hidden></p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-stone-300 bg-stone-50 px-6 py-4">
                <button type="button" data-modal-close
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </dialog>

    <dialog id="modal-detail"
        aria-labelledby="modal-detail-title"
        class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-2xl overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <div class="flex max-h-[90vh] flex-col">

            <div class="flex items-start justify-between border-b border-stone-300 px-6 py-4">
                <div>
                    <h2 id="modal-detail-title" class="text-lg font-semibold">Detail Pesanan Pembelian</h2>
                    <p data-detail="kode" class="mt-0.5 text-stone-500"></p>
                </div>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6 overflow-y-auto px-6 py-5">
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-stone-50 px-4 py-3">
                    <span id="detail-status-badge" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium">
                        <span id="detail-status-dot" class="h-1.5 w-1.5 rounded-full"></span>
                        <span data-detail="status"></span>
                    </span>
                    <p class="flex items-center gap-1.5 text-stone-500">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        <span data-detail="tanggal" class="font-medium text-stone-900"></span>
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg border border-stone-200 p-4">
                        <p class="mb-3 text-xs font-medium uppercase tracking-wide text-stone-400">Supplier</p>
                        <div class="flex items-start gap-3">
                            <span id="detail-supplier-initial" aria-hidden="true"
                                  class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand/10 text-sm font-semibold text-brand"></span>
                            <div class="min-w-0">
                                <p data-detail="suppliernama" class="truncate font-medium text-stone-900"></p>
                                <p class="mt-1.5 flex items-center gap-1.5 text-stone-500">
                                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span data-detail="suppliertelepon"></span>
                                </p>
                                <p class="mt-0.5 flex items-center gap-1.5 text-stone-500">
                                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    <span data-detail="supplierkota"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-stone-200 p-4">
                        <p class="mb-3 text-xs font-medium uppercase tracking-wide text-stone-400">Dibuat oleh</p>
                        <div class="flex items-start gap-3">
                            <span id="detail-user-initial" aria-hidden="true"
                                  class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand/10 text-sm font-semibold text-brand"></span>
                            <div class="min-w-0">
                                <p data-detail="usernama" class="truncate font-medium text-stone-900"></p>
                                <p class="mt-1.5 flex items-center gap-1.5 text-stone-500">
                                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="2" y="4" width="20" height="16" rx="2" />
                                        <path d="m2 7 10 6 10-6" />
                                    </svg>
                                    <span data-detail="useremail" class="truncate"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="mb-2 text-xs font-medium uppercase tracking-wide text-stone-400">Daftar Barang</p>
                    <div id="detail-items-body" class="divide-y divide-stone-200 rounded-lg border border-stone-200"></div>
                </div>

                <div class="rounded-lg bg-stone-50 p-4">
                    <div class="flex items-center justify-between text-base font-semibold text-stone-900">
                        <dt>Total</dt>
                        <dd data-detail="total"></dd>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end border-t border-stone-300 bg-stone-50 px-6 py-4">
                <button type="button" data-modal-close
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    Tutup
                </button>
            </div>
        </div>
    </dialog>

    @push('scripts')
        <script src="{{ asset('js/pesanan.js') }}"></script>
    @endpush
@endsection
