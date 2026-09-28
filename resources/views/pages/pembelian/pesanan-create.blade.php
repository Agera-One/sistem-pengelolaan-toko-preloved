@extends('layouts.app')

@section('title', 'Tambah Pesanan')

@section('content')
    @php
        $field = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
        $cell = 'w-full rounded-lg border border-stone-300 bg-white px-2.5 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
        $rows = old('items') ?: [[]];
        $kategoriList = ['Blouse', 'Kemeja', 'Rok', 'Celana', 'Overall', 'Outher', 'Jaket', 'Vest', 'Gamis', 'Dress'];
    @endphp

    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('pembelian.pesanan.index') }}"
            class="inline-flex items-center gap-1.5 rounded text-stone-500 transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6" /></svg>
                Kembali ke daftar pesanan
            </a>

            <nav aria-label="Breadcrumb" class="order-first sm:order-last">
                <ol class="flex items-center gap-1.5 text-stone-500">
                    <li>
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}"
                           class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">Dashboard</a>
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
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                    </li>
                    <li>
                        <a href="{{ route('pembelian.pesanan.index') }}"
                           class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">Daftar Pesanan</a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                    </li>
                    <li class="font-medium text-stone-900" aria-current="page">Tambah Pesanan</li>
                </ol>
            </nav>
        </div>

        <h1 class="mt-4 text-2xl font-semibold text-stone-900">Tambah Pesanan</h1>

        @if (session('error') || $errors->any())
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800" role="alert">
                @if (session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                @if ($errors->any())
                    <p class="font-medium">Data belum lengkap:</p>
                    <ul class="mt-1 list-inside list-disc text-sm">
                        @foreach ($errors->unique() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <form id="form-pesanan" method="POST" action="{{ route('pembelian.pesanan.store') }}" novalidate class="mt-6 space-y-6">
            @csrf

            {{-- 1. Informasi Pesanan --}}
            <section class="overflow-hidden rounded-xl border border-stone-300 bg-white">
                <header class="flex items-center gap-3 border-b border-stone-300 px-4 py-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs font-semibold text-white">1</span>
                    <h2 class="font-semibold text-stone-900">Informasi Pesanan</h2>
                </header>

                <div class="grid gap-4 p-4 sm:grid-cols-3">
                    <div>
                        <label for="kode" class="mb-1.5 block text-stone-900">Kode Pesanan</label>
                        <input type="text" id="kode" value="{{ $kode }}" readonly aria-describedby="hint-kode"
                               class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 focus:outline-none">
                        <p id="hint-kode" class="mt-1.5 text-xs text-stone-500">Dibuat otomatis oleh sistem.</p>
                    </div>

                    <div>
                        <label for="tanggal" class="mb-1.5 block text-stone-900">
                            Tanggal Pesanan <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                               @error('tanggal') aria-invalid="true" @enderror
                               class="{{ $field }}">
                    </div>

                    <div>
                        <label for="supplier_id" class="mb-1.5 block text-stone-900">
                            Nama Supplier <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <select id="supplier_id" name="supplier_id"
                                @error('supplier_id') aria-invalid="true" @enderror
                                class="{{ $field }}">
                            <option value="">Pilih supplier</option>
                            @foreach ($supplier as $s)
                                <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            {{-- 2. Daftar Barang --}}
            <section class="overflow-hidden rounded-xl border border-stone-300 bg-white">
                {{-- Header Section: Judul + Total Harga sejajar di kanan --}}
                <header class="flex items-center justify-between border-b border-stone-300 px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs font-semibold text-white">2</span>
                        <h2 class="font-semibold text-stone-900">Daftar Barang</h2>
                    </div>

                    {{-- Total Harga di Header --}}
                    <div class="flex items-center gap-2 rounded-lg bg-stone-100 px-3 py-1.5 text-sm">
                        <span class="font-medium text-stone-500">Total Harga Beli:</span>
                        <span id="total-footer" class="text-base font-bold tracking-tight text-stone-900">Rp0</span>
                    </div>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1080px] text-left">
                        <thead class="border-b border-stone-300 bg-brand/5 text-stone-600">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-medium">Nama Barang</th>
                                <th scope="col" class="w-48 px-2 py-3 font-medium">Kategori</th>
                                <th scope="col" class="w-48 px-2 py-3 font-medium">Lingkar (cm)</th>
                                <th scope="col" class="w-48 px-2 py-3 font-medium">Panjang (cm)</th>
                                <th scope="col" class="w-48 px-2 py-3 font-medium">Harga Beli</th>
                                <th scope="col" class="w-48 px-2 py-3 font-medium">Harga Jual</th>
                                <th scope="col" class="w-20 px-2 py-3 text-center font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="item-rows" class="divide-y divide-stone-200">
                            @foreach ($rows as $i => $item)
                                <tr data-row data-index="{{ $i }}" class="align-top">
                                    <td class="px-4 py-2">
                                        <input type="text" name="items[{{ $i }}][nama]" value="{{ $item['nama'] ?? '' }}"
                                               data-cell="nama" maxlength="255" autocomplete="off" placeholder="Nama barang"
                                               @if ($errors->has("items.$i.nama")) aria-invalid="true" @endif
                                               class="{{ $cell }}">
                                    </td>

                                    <td class="px-2 py-2">
                                        <select name="items[{{ $i }}][kategori]" data-cell="kategori"
                                                @if ($errors->has("items.$i.kategori")) aria-invalid="true" @endif
                                                class="{{ $cell }}">
                                            <option value="" disabled @selected(!isset($item['kategori']))>Pilih</option>
                                            @foreach ($kategoriList as $kat)
                                                <option value="{{ $kat }}" @selected(($item['kategori'] ?? '') === $kat)>{{ $kat }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="px-2 py-2">
                                        <input type="number" step="any" min="0" inputmode="decimal"
                                               name="items[{{ $i }}][lingkar]" value="{{ $item['lingkar'] ?? '' }}"
                                               data-cell="lingkar" placeholder="0"
                                               @if ($errors->has("items.$i.lingkar")) aria-invalid="true" @endif
                                               class="{{ $cell }}">
                                    </td>

                                    <td class="px-2 py-2">
                                        <input type="number" step="any" min="0" inputmode="decimal"
                                               name="items[{{ $i }}][panjang]" value="{{ $item['panjang'] ?? '' }}"
                                               data-cell="panjang" placeholder="0"
                                               @if ($errors->has("items.$i.panjang")) aria-invalid="true" @endif
                                               class="{{ $cell }}">
                                    </td>

                                    @foreach (['harga_beli', 'harga_jual'] as $money)
                                        <td class="px-2 py-2">
                                            <div class="relative">
                                                <span class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-stone-500">Rp</span>
                                                <input type="text" inputmode="numeric" autocomplete="off" data-money
                                                       name="items[{{ $i }}][{{ $money }}]" value="{{ $item[$money] ?? '' }}"
                                                       data-cell="{{ $money }}" placeholder="0"
                                                       @if ($errors->has("items.$i.$money")) aria-invalid="true" @endif
                                                       class="{{ $cell }} pl-8">
                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="px-2 py-2 text-center">
                                        <button type="button" data-remove aria-label="Hapus baris"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-md text-stone-500 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 6h18" />
                                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                                <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Template JavaScript untuk Penambahan Baris --}}
                <template id="row-template">
                    <tr data-row data-index="__INDEX__" class="align-top">
                        <td class="px-4 py-2">
                            <input type="text" name="items[__INDEX__][nama]" value=""
                                   data-cell="nama" maxlength="255" autocomplete="off" placeholder="Nama barang"
                                   class="{{ $cell }}">
                        </td>

                        <td class="px-2 py-2">
                            <select name="items[__INDEX__][kategori]" data-cell="kategori" class="{{ $cell }}">
                                <option value="" disabled selected>Pilih</option>
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td class="px-2 py-2">
                            <input type="number" step="any" min="0" inputmode="decimal"
                                   name="items[__INDEX__][lingkar]" value=""
                                   data-cell="lingkar" placeholder="0"
                                   class="{{ $cell }}">
                        </td>

                        <td class="px-2 py-2">
                            <input type="number" step="any" min="0" inputmode="decimal"
                                   name="items[__INDEX__][panjang]" value=""
                                   data-cell="panjang" placeholder="0"
                                   class="{{ $cell }}">
                        </td>

                        @foreach (['harga_beli', 'harga_jual'] as $money)
                            <td class="px-2 py-2">
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-stone-500">Rp</span>
                                    <input type="text" inputmode="numeric" autocomplete="off" data-money
                                           name="items[__INDEX__][{{ $money }}]" value=""
                                           data-cell="{{ $money }}" placeholder="0"
                                           class="{{ $cell }} pl-8">
                                </div>
                            </td>
                        @endforeach

                        <td class="px-2 py-2 text-center">
                            <button type="button" data-remove aria-label="Hapus baris"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-md text-stone-500 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                    <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />
                                    <path d="M10 11v6M14 11v6" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </template>

                {{-- Action Bar Bawah --}}
                <div class="flex items-center justify-between border-t border-stone-300 bg-stone-50 px-4 py-3.5">
                    <div>
                        <button type="button" id="btn-add-row"
                                class="inline-flex items-center gap-2 rounded-lg border border-dashed border-stone-400 bg-white px-3 py-1.5 text-sm font-medium text-stone-700 transition hover:border-brand hover:bg-brand/5 hover:text-brand focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                            Tambah Baris
                        </button>
                        <p id="items-error" class="mt-1 text-xs text-red-600" hidden></p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('pembelian.pesanan.index') }}"
                           class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            Batal
                        </a>
                        <button type="submit" id="btn-submit"
                                class="rounded-lg bg-brand px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                            Simpan Pesanan
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>
@endsection
