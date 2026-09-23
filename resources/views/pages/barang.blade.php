@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">Daftar Barang</h1>
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
                    <li class="font-medium text-stone-900" aria-current="page">Daftar Barang</li>
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
                <form method="GET" action="{{ route('barang.index') }}"
                      class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:max-w-sm">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <label for="q" class="sr-only">Cari barang</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                               placeholder="Cari kode, nama, kategori, atau status"
                               class="w-full rounded-lg border border-stone-300 bg-white py-2 pl-9 pr-3 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('barang.index') }}"
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
                    Tambah barang
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-brand/5 text-stone-600 border-b border-stone-300">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nama</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kategori</th>
                            <th scope="col" class="px-4 py-3 font-medium">Status</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @forelse ($barang as $b)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">
                                    {{ $barang->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-stone-700">
                                    {{ $b->kode }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-900">
                                    {{ Str::title($b->nama) }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-stone-700">
                                    {{ Str::title($b->kategori) }}
                                </td>

                                @php
                                    $statusKey = strtolower($b->status);
                                    $statusBadge = match ($statusKey) {
                                        'draft' => 'bg-stone-100 text-stone-600 ring-1 ring-inset ring-stone-300',
                                        'tersedia' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                                        'terjual' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
                                        default => 'bg-stone-100 text-stone-600 ring-1 ring-inset ring-stone-300',
                                    };
                                    $statusDot = match ($statusKey) {
                                        'draft' => 'bg-stone-400',
                                        'tersedia' => 'bg-emerald-500',
                                        'terjual' => 'bg-blue-500',
                                        default => 'bg-stone-400',
                                    };
                                @endphp
                                <td class="whitespace-nowrap px-4 py-3.5" title="{{ Str::title($b->status) }}">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusBadge }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ Str::title($b->status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            data-menu-toggle="menu-barang-{{ $b->getKey() }}"
                                            aria-haspopup="menu"
                                            aria-expanded="false"
                                            aria-label="Aksi untuk {{ Str::title($b->nama) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <circle cx="12" cy="5" r="1.75" />
                                            <circle cx="12" cy="12" r="1.75" />
                                            <circle cx="12" cy="19" r="1.75" />
                                        </svg>
                                    </button>

                                    <div id="menu-barang-{{ $b->getKey() }}"
                                         role="menu"
                                         hidden
                                         class="fixed z-50 w-44 rounded-lg border border-stone-300 bg-white p-1 text-left shadow-lg">
                                        <button type="button" role="menuitem"
                                                data-detail-open="modal-detail"
                                                data-kode="{{ $b->kode }}"
                                                data-nama="{{ $b->nama }}"
                                                data-lingkar="{{ $b->lingkar }}"
                                                data-panjang="{{ $b->panjang }}"
                                                data-kategori="{{ $b->kategori }}"
                                                data-status="{{ $b->status }}"
                                                data-harga-beli="{{ $b->harga_beli ?? '' }}"
                                                data-harga-jual="{{ $b->harga_jual ?? '' }}"
                                                data-dibuat="{{ $b->created_at?->translatedFormat('d F Y') }}"
                                                data-diperbarui="{{ $b->updated_at?->translatedFormat('d F Y') }}"
                                                class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Detail
                                        </button>

                                        <button type="button" role="menuitem"
                                                data-edit-open="modal-ubah"
                                                data-action="{{ route('barang.update', $b) }}"
                                                data-kode="{{ $b->kode }}"
                                                data-nama="{{ $b->nama }}"
                                                data-lingkar="{{ $b->lingkar }}"
                                                data-panjang="{{ $b->panjang }}"
                                                data-kategori="{{ $b->kategori }}"
                                                class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <form action="{{ route('barang.destroy', $b) }}" method="POST"
                                              data-confirm="Hapus barang {{ $b->nama }}? Data yang dihapus tidak bisa dikembalikan.">
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
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                                <path d="m3.3 7 8.7 5 8.7-5" />
                                                <path d="M12 22V12" />
                                            </svg>
                                        </span>

                                        @if (request()->filled('q'))
                                            <p class="mt-4 font-medium text-stone-900">Barang tidak ditemukan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tidak ada hasil untuk "{{ request('q') }}". Coba kata kunci lain.
                                            </p>
                                            <a href="{{ route('barang.index') }}"
                                            class="mt-4 font-medium text-link hover:text-link-hover hover:underline">
                                                Tampilkan semua barang
                                            </a>
                                    @else
                                            <p class="mt-4 font-medium text-stone-900">Belum ada barang</p>
                                            <p class="mt-1 text-stone-500">
                                                Tambahkan barang pertama Anda untuk mulai mencatat penjualan.
                                            </p>
                                            <button type="button" data-modal-open="modal-tambah"
                                                    class="mt-4 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover">
                                                Tambah barang
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$barang" />
        </div>
    </div>

    @php
        $field = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
    @endphp

    <dialog id="modal-tambah"
            aria-labelledby="modal-tambah-title"
            class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <form method="POST" action="{{ route('barang.store') }}" class="flex max-h-[90vh] flex-col" novalidate>
            @csrf

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-tambah-title" class="text-lg font-semibold">Tambah Barang</h2>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6 overflow-y-auto px-6 py-5">
                <div>
                    <label for="kode" class="mb-1.5 block text-stone-900">Kode barang</label>
                    <input name="kode" type="text" id="kode" value="{{ $kode }}"
                            readonly aria-describedby="hint-kode"
                            class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 placeholder:text-stone-400 focus:outline-none">
                    <p id="hint-kode" class="mt-1.5 text-xs text-stone-500">Terisi saat data disimpan.</p>
                </div>

                <div>
                    <label for="nama" class="mb-1.5 block text-stone-900">
                        Nama <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input type="text" id="nama" name="nama"
                            autocomplete="off" autofocus
                            data-label="Nama" data-rules="required|string|max:255"
                            aria-describedby="err-nama"
                            class="{{ $field }}">
                    <p id="err-nama" class="mt-1.5 text-xs text-red-600" hidden></p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="lingkar" class="mb-1.5 block text-stone-900">
                            <span data-ukuran-label="lingkar">Lingkar Dada/Pinggang</span>
                            <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="number" inputmode="numeric" min="0" id="lingkar" name="lingkar"
                                placeholder="Contoh: 90" autocomplete="off"
                                data-label="Lingkar" data-rules="required|numeric"
                                aria-describedby="err-lingkar"
                                class="{{ $field }}">
                        <p id="err-lingkar" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>

                    <div>
                        <label for="panjang" class="mb-1.5 block text-stone-900">
                            <span data-ukuran-label="panjang">Panjang Baju/Celana</span>
                            <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="number" inputmode="numeric" min="0" id="panjang" name="panjang"
                                placeholder="Contoh: 60" autocomplete="off"
                                data-label="Panjang" data-rules="required|numeric"
                                aria-describedby="err-panjang"
                                class="{{ $field }}">
                        <p id="err-panjang" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>
                </div>

                <div>
                    <label for="kategori" class="mb-1.5 block text-stone-900">
                        Kategori <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="kategori" name="kategori"
                            data-label="Kategori" data-rules="required|string|max:255"
                            aria-describedby="err-kategori"
                            class="{{ $field }}">
                        <option value="" disabled selected>Pilih kategori</option>
                        <option value="Blouse">Blouse</option>
                        <option value="Kemeja">Kemeja</option>
                        <option value="Rok">Rok</option>
                        <option value="Celana">Celana</option>
                        <option value="Overall">Overall</option>
                        <option value="Outher">Outher</option>
                        <option value="Jaket">Jaket</option>
                        <option value="Vest">Vest</option>
                        <option value="Gamis">Gamis</option>
                        <option value="Dress">Dress</option>
                    </select>
                    <p id="err-kategori" class="mt-1.5 text-xs text-red-600" hidden></p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-stone-300 bg-stone-50 px-6 py-4">
                <button type="button" data-modal-close
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                    Simpan barang
                </button>
            </div>
        </form>
    </dialog>

    <dialog id="modal-ubah"
        aria-labelledby="modal-ubah-title"
        class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <form method="POST" class="flex max-h-[90vh] flex-col" novalidate>
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-ubah-title" class="text-lg font-semibold">Ubah Barang</h2>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6 overflow-y-auto px-6 py-5">
                <div>
                    <label for="edit-kode" class="mb-1.5 block text-stone-900">Kode barang</label>
                    <input type="text" id="edit-kode" data-fill="kode"
                            readonly aria-describedby="hint-edit-kode"
                            class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 focus:outline-none">
                    <p id="hint-edit-kode" class="mt-1.5 text-xs text-stone-500">Kode tidak dapat diubah.</p>
                </div>

                <div>
                    <label for="edit-nama" class="mb-1.5 block text-stone-900">
                        Nama <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input type="text" id="edit-nama" name="nama"
                            autocomplete="off" autofocus
                            data-fill="nama"
                            data-label="Nama" data-rules="required|string|max:255"
                            aria-describedby="err-edit-nama"
                            class="{{ $field }}">
                    <p id="err-edit-nama" class="mt-1.5 text-xs text-red-600" hidden></p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="edit-lingkar" class="mb-1.5 block text-stone-900">
                            <span data-ukuran-label="edit-lingkar">Lingkar Dada/Pinggang</span>
                            <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="number" inputmode="numeric" min="0" id="edit-lingkar" name="lingkar"
                                placeholder="Contoh: 90" autocomplete="off"
                                data-fill="lingkar"
                                data-label="Lingkar" data-rules="required|numeric"
                                aria-describedby="err-edit-lingkar"
                                class="{{ $field }}">
                        <p id="err-edit-lingkar" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>

                    <div>
                        <label for="edit-panjang" class="mb-1.5 block text-stone-900">
                            <span data-ukuran-label="edit-panjang">Panjang Baju/Celana</span>
                            <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="number" inputmode="numeric" min="0" id="edit-panjang" name="panjang"
                                placeholder="Contoh: 60" autocomplete="off"
                                data-fill="panjang"
                                data-label="Panjang" data-rules="required|numeric"
                                aria-describedby="err-edit-panjang"
                                class="{{ $field }}">
                        <p id="err-edit-panjang" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>
                </div>


                <div>
                    <label for="edit-kategori" class="mb-1.5 block text-stone-900">
                        Kategori <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="edit-kategori" name="kategori"
                            data-fill="kategori"
                            data-label="Kategori" data-rules="required|string|max:255"
                            aria-describedby="err-edit-kategori"
                            class="{{ $field }}">
                        <option value="" disabled selected>Pilih kategori</option>
                        <option value="Blouse">Blouse</option>
                        <option value="Kemeja">Kemeja</option>
                        <option value="Rok">Rok</option>
                        <option value="Celana">Celana</option>
                        <option value="Overall">Overall</option>
                        <option value="Outher">Outher</option>
                        <option value="Jaket">Jaket</option>
                        <option value="Vest">Vest</option>
                        <option value="Gamis">Gamis</option>
                        <option value="Dress">Dress</option>
                    </select>
                    <p id="err-edit-kategori" class="mt-1.5 text-xs text-red-600" hidden></p>
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
        class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <div class="flex max-h-[90vh] flex-col">

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-detail-title" class="text-lg font-semibold">Detail Barang</h2>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 py-5">
                <div class="flex items-start gap-4">
                    <span aria-hidden="true"
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2 8.5 5H6a2 2 0 0 0-2 2v1.2a1 1 0 0 0 .68.95L7 10v10a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V10l2.32-.85A1 1 0 0 0 20 8.2V7a2 2 0 0 0-2-2h-2.5L12 2Z" />
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p data-detail="nama" class="break-words text-lg font-semibold text-stone-900"></p>
                        <p data-detail="kode" class="mt-0.5 font-mono text-stone-500"></p>
                        <span data-detail-status-badge class="mt-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 font-medium">
                            <span data-detail-status-dot class="h-1.5 w-1.5 rounded-full"></span>
                            <span data-detail="status"></span>
                        </span>
                    </div>
                </div>

                <div class="overflow-y-auto py-6 space-y-5 -mt-3 bg-white rounded-t-2xl">
                    <div class="mt-5 grid grid-cols-2 divide-x divide-brand/20 overflow-hidden rounded-lg border border-brand/20 bg-brand/5">
                        <div class="px-3 py-2.5">
                            <p class= text-stone-500">Harga Beli</p>
                            <p data-detail="harga_beli" class="mt-0.5 font-medium text-stone-900"></p>
                        </div>
                        <div class="px-3 py-2.5">
                            <p class= text-stone-500">Harga Jual</p>
                            <p data-detail="harga_jual" class="mt-0.5 font-medium text-brand"></p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-stone-200 overflow-hidden divide-y divide-stone-100 shadow-sm">
                        <div class="flex justify-between items-center px-4 py-3 bg-white">
                            <span class="text-stone-500">Kategori</span>
                            <span data-detail="kategori" class="font-medium text-stone-900"></span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-white">
                            <span class="text-stone-500">Lingkar Dada/Pinggang</span>
                            <span data-detail="lingkar" class="font-medium text-stone-900"></span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-white">
                            <span class="text-stone-500">Panjang Baju/Celana</span>
                            <span data-detail="panjang" class="font-medium text-stone-900"></span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-stone-50/60">
                            <span class="text-stone-500">Terdaftar pada</span>
                            <span data-detail="dibuat" class="font-medium text-stone-900"></span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-stone-50/60">
                            <span class="text-stone-500">Terakhir diperbarui</span>
                            <span data-detail="diperbarui" class="font-medium text-stone-900"></span>
                        </div>
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
@endsection
