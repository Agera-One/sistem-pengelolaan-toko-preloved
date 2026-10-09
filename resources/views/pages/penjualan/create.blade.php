@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@php
    $input = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
    $readonly = 'w-full cursor-default rounded-lg border border-stone-200 bg-stone-100 px-3 py-2.5 text-stone-500 focus:outline-none';
    $chevron = '<svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>';
@endphp

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('penjualan.index') }}"
               class="inline-flex items-center gap-1.5 text-stone-500 transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6" /></svg>
                Kembali ke daftar penjualan
            </a>

            <nav aria-label="Breadcrumb" class="order-first sm:order-last">
                <ol class="flex items-center gap-1.5 text-stone-500">
                    <li><a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="rounded transition hover:text-link">Dashboard</a></li>
                    <li aria-hidden="true">{!! $chevron !!}</li>
                    <li>Penjualan</li>
                    <li aria-hidden="true">{!! $chevron !!}</li>
                    <li><a href="{{ route('penjualan.index') }}" class="rounded transition hover:text-link">Daftar Penjualan</a></li>
                    <li aria-hidden="true">{!! $chevron !!}</li>
                    <li class="font-medium text-stone-900" aria-current="page">Tambah Penjualan</li>
                </ol>
            </nav>
        </div>

        <h1 class="mt-5 text-2xl font-semibold text-stone-900">Tambah Penjualan</h1>

        @if ($errors->any())
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800" role="alert">
                <p class="font-medium">Penjualan belum bisa disimpan:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="form-penjualan" method="POST" action="{{ route('penjualan.store') }}" class="mt-6 space-y-6" novalidate>
            @csrf

            <section class="rounded-xl border border-stone-300 bg-white">
                <div class="flex items-center gap-3 border-b border-stone-300 px-4 py-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs font-medium text-white">1</span>
                    <h2 class="font-semibold text-stone-900">Informasi Penjualan</h2>
                </div>

                <div class="grid gap-5 p-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="kode" class="mb-2 block text-stone-700">Kode Penjualan</label>
                        <input type="text" id="kode" value="{{ $kode }}" readonly tabindex="-1" class="{{ $readonly }}">
                        <p class="mt-1.5 text-xs text-stone-500">Dibuat otomatis oleh sistem.</p>
                    </div>

                    <div>
                        <label for="tanggal" class="mb-2 block text-stone-700">Tanggal Penjualan <span class="text-red-600">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" required
                               class="{{ $input }} @error('tanggal') border-red-400 @enderror">
                        <p class="mt-1.5 hidden text-xs text-red-600" data-error-for="tanggal"></p>
                    </div>

                    <div>
                        <label for="pelanggan_id" class="mb-2 block text-stone-700">Nama Pelanggan <span class="text-red-600">*</span></label>
                        <select id="pelanggan_id" name="pelanggan_id" required
                                class="{{ $input }} @error('pelanggan_id') border-red-400 @enderror">
                            <option value="">Pilih pelanggan</option>
                            @foreach ($pelanggan as $p)
                                <option value="{{ $p->getKey() }}" @selected(old('pelanggan_id') == $p->getKey())>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 hidden text-xs text-red-600" data-error-for="pelanggan_id"></p>
                    </div>

                    <div>
                        <label for="ongkir" class="mb-2 block text-stone-700">Biaya Ongkir <span class="text-red-600">*</span></label>
                        <div data-wrap-for="ongkir"
                             class="flex items-center gap-2 rounded-lg border border-stone-300 bg-white px-3 focus-within:border-brand focus-within:ring-1 focus-within:ring-brand aria-invalid:border-red-400 aria-invalid:focus-within:border-red-500 aria-invalid:focus-within:ring-red-500">
                            <span class="text-stone-500">Rp</span>
                            <input type="text" inputmode="numeric" id="ongkir" name="ongkir" data-money placeholder="0"
                                   value="{{ old('ongkir') }}" required
                                   class="min-w-0 flex-1 border-0 bg-transparent py-2.5 text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-0">
                        </div>
                        <p class="mt-1.5 hidden text-xs text-red-600" data-error-for="ongkir"></p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-stone-300 bg-white">
                <div class="flex items-center justify-between gap-3 border-b border-stone-300 px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs font-medium text-white">2</span>
                        <h2 class="font-semibold text-stone-900">Daftar Barang</h2>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-[900px] w-full text-left">
                        <thead class="border-b border-stone-300 bg-brand/5 text-stone-600">
                            <tr>
                                <th scope="col" class="w-[36%] px-4 py-3 font-medium">Barang</th>
                                <th scope="col" class="px-2 py-3 font-medium">Kategori</th>
                                <th scope="col" class="px-2 py-3 font-medium">Lingkar (cm)</th>
                                <th scope="col" class="px-2 py-3 font-medium">Panjang (cm)</th>
                                <th scope="col" class="px-2 py-3 font-medium">Harga Jual</th>
                                <th scope="col" class="px-4 py-3 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="item-rows" class="divide-y divide-stone-300"></tbody>
                    </table>
                </div>

                <p id="item-error" class="hidden border-t border-stone-300 px-4 py-2.5 text-sm text-red-600" role="alert"></p>

                <div class="flex flex-col gap-5 rounded-b-xl border-t border-stone-300 bg-stone-50/60 p-4 sm:flex-row sm:items-end sm:justify-between">
                    <button type="button" id="btn-add-row"
                            class="inline-flex w-fit items-center gap-2 rounded-lg border border-dashed border-stone-400 bg-white px-3.5 py-2 text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                        Tambah Baris
                    </button>

                    <div class="flex flex-col gap-4 sm:items-end">
                        <dl class="grid grid-cols-[auto_auto] gap-x-10 gap-y-1.5 text-sm text-stone-600">
                            <dt>Subtotal</dt><dd id="sum-subtotal" class="text-right">Rp0</dd>
                            <dt>Ongkir</dt><dd id="sum-ongkir" class="text-right">Rp0</dd>
                            <dt class="font-semibold text-stone-900">Total</dt>
                            <dd id="sum-total" class="text-right text-lg font-bold text-stone-900">Rp0</dd>
                        </dl>

                        <div class="flex gap-2.5">
                            <a href="{{ route('penjualan.index') }}"
                               class="rounded-lg border border-stone-300 bg-white px-5 py-2 text-center font-medium text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                Batal
                            </a>
                            <button type="submit" id="btn-simpan"
                                    class="rounded-lg bg-brand px-5 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:opacity-60">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <template id="row-template">
        <tr data-row>
            <td class="px-4 py-2.5">
                <select name="items[__INDEX__][barang_id]" data-field="barang" aria-label="Barang" class="{{ $input }}">
                    <option value="">Pilih barang</option>
                </select>
            </td>
            <td class="px-2 py-2.5"><input type="text" data-field="kategori" aria-label="Kategori" readonly tabindex="-1" class="{{ $readonly }}"></td>
            <td class="px-2 py-2.5"><input type="text" data-field="lingkar" aria-label="Lingkar" readonly tabindex="-1" class="{{ $readonly }}"></td>
            <td class="px-2 py-2.5"><input type="text" data-field="panjang" aria-label="Panjang" readonly tabindex="-1" class="{{ $readonly }}"></td>
            <td class="px-2 py-2.5">
                <div class="flex items-center gap-2 rounded-lg border border-stone-300 bg-white px-3 focus-within:border-brand focus-within:ring-1 focus-within:ring-brand aria-invalid:border-red-400 aria-invalid:focus-within:border-red-500 aria-invalid:focus-within:ring-red-500">
                    <span class="text-stone-500">Rp</span>
                    <input placeholder="0" type="text" inputmode="numeric" name="items[__INDEX__][harga_jual]" data-field="harga" data-money aria-label="Harga jual"
                           class="min-w-0 flex-1 border-0 bg-transparent py-2.5 text-stone-900 focus:outline-none focus:ring-0">
                </div>
            </td>
            <td class="px-4 py-2.5 text-right">
                <button type="button" data-remove aria-label="Hapus baris"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-stone-500 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 6h18" /><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" /><path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" /><path d="M10 11v6M14 11v6" />
                    </svg>
                </button>
            </td>
        </tr>
    </template>

    <script type="application/json" id="barang-data">{!! json_encode($barang, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script type="application/json" id="old-items">{!! json_encode(array_values((array) old('items', [])), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endsection
