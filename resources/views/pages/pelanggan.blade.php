@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">Daftar Pelanggan</h1>
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
                    <li class="font-medium text-stone-900" aria-current="page">Daftar Pelanggan</li>
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
                <form method="GET" action="{{ route('pelanggan.index') }}"
                      class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:max-w-sm">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <label for="q" class="sr-only">Cari pelanggan</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                               placeholder="Cari kode, nama, atau telepon"
                               class="w-full rounded-lg border border-stone-300 bg-white py-2 pl-9 pr-3 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('pelanggan.index') }}"
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
                    Tambah pelanggan
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-brand/5 text-stone-600 border-b border-stone-300">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nama</th>
                            <th scope="col" class="px-4 py-3 font-medium">Telepon</th>
                            <th scope="col" class="px-4 py-3 font-medium">Alamat</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @forelse ($pelanggan as $p)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">
                                    {{ $pelanggan->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-stone-700">
                                    {{ $p->kode }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-900">
                                    {{ $p->nama }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-stone-700">
                                    {{ $p->nomor_telepon ?: '-' }}
                                </td>

                                <td class="max-w-xs truncate px-4 py-3.5 text-stone-700" title="{{ $p->alamat }}">
                                    {{ $p->alamat ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            data-menu-toggle="menu-pelanggan-{{ $p->getKey() }}"
                                            aria-haspopup="menu"
                                            aria-expanded="false"
                                            aria-label="Aksi untuk {{ $p->nama }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <circle cx="12" cy="5" r="1.75" />
                                            <circle cx="12" cy="12" r="1.75" />
                                            <circle cx="12" cy="19" r="1.75" />
                                        </svg>
                                    </button>

                                    <div id="menu-pelanggan-{{ $p->getKey() }}"
                                         role="menu"
                                         hidden
                                         class="fixed z-50 w-44 rounded-lg border border-stone-300 bg-white p-1 text-left shadow-lg">
                                        <button type="button" role="menuitem"
                                                data-detail-open="modal-detail"
                                                data-kode="{{ $p->kode }}"
                                                data-nama="{{ $p->nama }}"
                                                data-telepon="{{ $p->nomor_telepon }}"
                                                data-alamat="{{ $p->alamat }}"
                                                data-dibuat="{{ $p->created_at?->translatedFormat('d F Y') }}"
                                                data-diperbarui="{{ $p->updated_at?->translatedFormat('d F Y') }}"
                                                class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Detail
                                        </button>

                                        <button type="button" role="menuitem"
                                                data-edit-open="modal-ubah"
                                                data-action="{{ route('pelanggan.update', $p) }}"
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

                                        <form action="{{ route('pelanggan.destroy', $p) }}" method="POST"
                                              data-confirm="Hapus pelanggan {{ $p->nama }}? Data yang dihapus tidak bisa dikembalikan.">
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
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand/10 text-brand">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </span>

                                        @if (request()->filled('q'))
                                            <p class="mt-4 font-medium text-stone-900">Pelanggan tidak ditemukan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tidak ada hasil untuk "{{ request('q') }}". Coba kata kunci lain.
                                            </p>
                                            <a href="{{ route('pelanggan.index') }}"
                                               class="mt-4 font-medium text-link hover:text-link-hover hover:underline">
                                                Tampilkan semua pelanggan
                                            </a>
                                        @else
                                            <p class="mt-4 font-medium text-stone-900">Belum ada pelanggan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tambahkan pelanggan pertama Anda untuk mulai mencatat penjualan.
                                            </p>
                                            <button type="button" data-modal-open="modal-tambah"
                                                    class="mt-4 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover">
                                                Tambah pelanggan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$pelanggan" />
        </div>
    </div>

    @php
        $field = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
    @endphp

    <dialog id="modal-tambah"
            aria-labelledby="modal-tambah-title"
            class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <form method="POST" action="{{ route('pelanggan.store') }}" class="flex max-h-[90vh] flex-col" novalidate>
            @csrf

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-tambah-title" class="text-lg font-semibold">Tambah pelanggan</h2>
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
                        <label for="kode" class="mb-1.5 block text-stone-900">Kode pelanggan</label>
                        <input name="kode" type="text" id="kode" value="{{ $kode }}"
                                readonly aria-describedby="hint-kode"
                                class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 placeholder:text-stone-400 focus:outline-none">
                        <p id="hint-kode" class="mt-1.5 text-xs text-stone-500">Terisi saat data disimpan.</p>
                    </div>

                    <div>
                        <label for="nomor_telepon" class="mb-1.5 block text-stone-900">
                            Nomor telepon <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="tel" inputmode="tel" id="nomor_telepon" name="nomor_telepon"
                                placeholder="08xxxxxxxxxx" autocomplete="off"
                                data-label="Nomor telepon" data-rules="required|max:15"
                                aria-describedby="err-nomor_telepon"
                                class="{{ $field }}">
                        <p id="err-nomor_telepon" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>
                </div>

                <div>
                        <label for="nama" class="mb-1.5 block text-stone-900">
                            Nama lengkap <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="text" id="nama" name="nama"
                                autocomplete="off" autofocus
                                data-label="Nama lengkap" data-rules="required"
                                aria-describedby="err-nama"
                                class="{{ $field }}">
                        <p id="err-nama" class="mt-1.5 text-xs text-red-600" hidden></p>
                    </div>

                <div>
                    <label for="alamat" class="mb-1.5 block text-stone-900">
                        Alamat lengkap <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="3"
                                placeholder="Jalan, nomor, kelurahan, kota"
                                data-label="Alamat lengkap" data-rules="required"
                                aria-describedby="err-alamat"
                                class="{{ $field }}"></textarea>
                    <p id="err-alamat" class="mt-1.5 text-xs text-red-600" hidden></p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-stone-300 bg-stone-50 px-6 py-4">
                <button type="button" data-modal-close
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                    Simpan pelanggan
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
                <h2 id="modal-ubah-title" class="text-lg font-semibold">Ubah pelanggan</h2>
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
                        <label for="edit-kode" class="mb-1.5 block text-stone-900">Kode pelanggan</label>
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
                            data-label="Nama lengkap" data-rules="required"
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
        class="m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-xl border border-stone-300 bg-white p-0 text-stone-900 shadow-xl backdrop:bg-stone-900/50">
        <div class="flex max-h-[90vh] flex-col">

            <div class="flex items-center justify-between border-b border-stone-300 px-6 py-4">
                <h2 id="modal-detail-title" class="text-lg font-semibold">Detail pelanggan</h2>
                <button type="button" data-modal-close aria-label="Tutup"
                        class="rounded-md p-1.5 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 py-5">
                <div class="flex items-center gap-4">
                    <span data-detail-initial aria-hidden="true"
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-brand/10 text-xl font-semibold text-brand"></span>
                    <div class="min-w-0">
                        <p data-detail="nama" class="break-words text-lg font-semibold text-stone-900"></p>
                        <p data-detail="kode" class="mt-0.5 text-stone-500"></p>
                    </div>
                </div>

                <dl class="mt-5 divide-y divide-stone-300 border-t border-stone-100">
                    <div class="grid gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-stone-500">Nomor telepon</dt>
                        <dd data-detail="telepon" class="text-stone-900 sm:col-span-2"></dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-stone-500">Alamat</dt>
                        <dd data-detail="alamat" class="whitespace-pre-line break-words text-stone-900 sm:col-span-2"></dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-stone-500">Terdaftar pada</dt>
                        <dd data-detail="dibuat" class="text-stone-900 sm:col-span-2"></dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-stone-500">Terakhir diperbarui</dt>
                        <dd data-detail="diperbarui" class="text-stone-900 sm:col-span-2"></dd>
                    </div>
                </dl>
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
