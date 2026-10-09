@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">Daftar Penjualan</h1>
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
                    <li class="font-medium text-stone-900" aria-current="page">Daftar Penjualan</li>
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
                    <form method="GET" action="{{ route('penjualan.index') }}" class="relative w-full sm:max-w-sm">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <label for="q" class="sr-only">Cari penjualan</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                            placeholder="Cari kode, pelanggan, atau status"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 pl-9 pr-3 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    </form>

                    <form method="GET" action="{{ route('penjualan.index') }}" class="w-full sm:w-48">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <select name="status" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 px-3 text-stone-900 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                            <option value="">Semua Status</option>
                            <option value="Sudah Bayar" {{ request('status') === 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="Belum Bayar" {{ request('status') === 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </form>

                    <form method="GET" action="{{ route('penjualan.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">

                        <x-daterange
                            name-start="tanggal_mulai"
                            name-end="tanggal_selesai"
                            :start="request('tanggal_mulai')"
                            :end="request('tanggal_selesai')"
                        />
                    </form>

                    <a href="{{ route('penjualan.index') }}"
                    class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-center font-medium text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                        Reset
                    </a>
                </div>

                <a href="{{ route('penjualan.create') }}">
                    <button type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Tambah Penjualan
                    </button>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-brand/5 text-stone-600 border-b border-stone-300">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode</th>
                            <th scope="col" class="px-4 py-3 font-medium">Pelanggan</th>
                            <th scope="col" class="px-4 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Subtotal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Ongkir</th>
                            <th scope="col" class="px-4 py-3 font-medium">Total</th>
                            <th scope="col" class="px-4 py-3 font-medium">Status</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @forelse ($penjualan as $p)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">
                                    {{ $penjualan->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-700">
                                    {{ $p->kode }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-900">
                                    {{ $p->pelanggan->nama }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700">
                                    {{ $p->tanggal->translatedFormat('d F Y') }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700"">
                                    Rp{{ number_format($p->subtotal, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700"">
                                    Rp{{ number_format($p->ongkir, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700"">
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
                                <td class="px-4 py-3.5"">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusBadge }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ $p->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            data-menu-toggle="menu-penjualan-{{ $p->getKey() }}"
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

                                    <div id="menu-penjualan-{{ $p->getKey() }}" role="menu" hidden class="fixed z-50 w-44 rounded-lg border border-stone-300 bg-white p-1 text-left shadow-lg">
                                        <a href="{{ route('penjualan.show', $p) }}" role="menuitem" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Detail
                                        </a>

                                        @if ($p->status === 'Belum Bayar')
                                            <a href="{{ route('penjualan.edit', $p) }}" role="menuitem" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                                </svg>
                                                Ubah
                                            </a>

                                            <form action="{{ route('penjualan.destroy', $p) }}" method="POST"
                                                data-confirm="Hapus penjualan {{ $p->kode }}? Data yang dihapus tidak bisa dikembalikan.">
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
                                        @endif
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

                                        @if (request()->filled('q') || request()->filled('tanggal_mulai'))
                                            <p class="mt-4 font-medium text-stone-900">Penjualan tidak ditemukan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tidak ada hasil untuk filter yang dipilih. Coba ubah kata kunci atau rentang tanggal.
                                            </p>
                                        @else
                                            <p class="mt-4 font-medium text-stone-900">Belum ada penjualan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tambahkan penjualan pertama Anda untuk mulai mencatat penjualan.
                                            </p>
                                            <button type="button" data-modal-open="modal-tambah"
                                                    class="mt-4 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover">
                                                Tambah Penjualan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$penjualan" />
        </div>
    </div>
@endsection
