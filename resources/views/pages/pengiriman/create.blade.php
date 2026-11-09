@extends('layouts.app')

@section('title', 'Tambah Pengiriman')

@section('content')
    <div class="mb-6 flex items-start justify-between gap-4">
        <div class="flex items-start gap-4">
            <a href="{{ route('pengiriman.index') }}"
               class="flex h-12 w-12 items-center justify-center rounded-xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-50"
               aria-label="Kembali">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-stone-900">Tambah Pengiriman</h1>
                <p class="text-stone-500">Catat resi dan pilih barang yang dikirim dalam satu paket.</p>
            </div>
        </div>

        <nav aria-label="Breadcrumb" class="order-first sm:order-last">
            <ol class="flex items-center gap-1.5 text-stone-500">
                <li><a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="rounded transition hover:text-link">Dashboard</a></li>
                <li aria-hidden="true"><svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg></li>
                <li>Penjualan</li>
                <li aria-hidden="true"><svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg></li>
                <li><a href="{{ route('pengiriman.index') }}" class="rounded transition hover:text-link">Daftar Pengiriman</a></li>
                <li aria-hidden="true"><svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg></li>
                <li class="font-medium text-stone-900" aria-current="page">Tambah Pengiriman</li>
            </ol>
        </nav>
    </div>

    <form id="form-pengiriman" method="POST" action="{{ route('pengiriman.store') }}" novalidate>
        @csrf
        <section class="rounded-3xl border border-stone-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-bold text-stone-900">Detail Pengiriman</h2>

            <div class="grid gap-x-6 gap-y-5 md:grid-cols-2">
                <div>
                    <label for="nomor_resi" class="mb-1.5 block text-sm font-semibold text-stone-900">Nomor Resi</label>
                    <input type="text" id="nomor_resi" name="nomor_resi" value="{{ old('nomor_resi') }}"
                           placeholder="Contoh: JP1234567890"
                           class="w-full rounded-xl border border-stone-300 px-4 py-3 text-stone-900 focus:border-[#4b5a26] focus:outline-none focus:ring-2 focus:ring-[#4b5a26]/20"
                           required>
                    @error('nomor_resi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kurir" class="mb-1.5 block text-sm font-semibold text-stone-900">Kurir</label>
                    <select id="kurir" name="kurir"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-stone-900 focus:border-[#4b5a26] focus:outline-none focus:ring-2 focus:ring-[#4b5a26]/20"
                            required>
                        @foreach ($kurir as $k)
                            <option value="{{ $k->value }}">
                                {{ $k->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('kurir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_kirim" class="mb-1.5 block text-sm font-semibold text-stone-900">Tanggal Kirim</label>
                    <input type="date" id="tanggal_kirim" name="tanggal_kirim"
                           value="{{ old('tanggal_kirim', now()->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-stone-300 px-4 py-3 text-stone-900 focus:border-[#4b5a26] focus:outline-none focus:ring-2 focus:ring-[#4b5a26]/20"
                           required>
                    @error('tanggal_kirim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ongkir_display" class="mb-1.5 block text-sm font-semibold text-stone-900">Ongkir</label>
                    <div class="flex overflow-hidden rounded-xl border border-stone-300 focus-within:border-[#4b5a26] focus-within:ring-2 focus-within:ring-[#4b5a26]/20">
                        <span class="flex items-center bg-stone-100 px-4 text-stone-500">Rp</span>
                        <input type="text" id="ongkir_display" inputmode="numeric" autocomplete="off"
                               value="{{ number_format((int) old('ongkir', 0), 0, ',', '.') }}"
                               class="w-full px-4 py-3 text-stone-900 focus:outline-none">
                        <input type="hidden" id="ongkir" name="ongkir" value="{{ (int) old('ongkir', 0) }}">
                    </div>
                    @error('ongkir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <fieldset class="md:col-span-2">
                    <legend class="mb-1.5 text-sm font-semibold text-stone-900">Ditanggung Oleh</legend>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($ditanggung as $d)
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-stone-200 px-4 py-3 transition has-[:checked]:border-[#4b5a26] has-[:checked]:bg-[#f3f5e8]">
                                <input type="radio" name="ditanggung_oleh" value="{{ $d->value }}" class="mt-1 h-4 w-4 accent-[#4b5a26]"
                                    @checked(old('ditanggung_oleh') === $d->value)>
                                <span>
                                    <span class="block font-semibold text-stone-900">{{ $d->label() }}</span>
                                    <span class="block text-sm text-stone-500">{{ $d->description() }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('ditanggung_oleh') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <fieldset>
                    <legend class="mb-1.5 text-sm font-semibold text-stone-900">Jenis Pengiriman</legend>
                    <div class="grid grid-cols-2 gap-1 rounded-xl border border-stone-300 p-1">
                        @foreach ($jenis as $j)
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis" value="{{ $j->value }}" class="mt-1 h-4 w-4 accent-[#4b5a26]"
                                    @checked(old('jenis') === $j->value)>
                                <span class="block rounded-lg py-3 text-center font-medium text-stone-700 transition peer-checked:bg-[#4b5a26] peer-checked:text-white">
                                    {{ $j->label() }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('jenis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <div>
                    <label for="kode_penjualan" class="mb-1.5 block text-sm font-semibold text-stone-900">Kode Penjualan</label>
                    <select id="kode_penjualan" name="penjualan_id"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3.5 text-stone-900 focus:border-[#4b5a26] focus:outline-none focus:ring-2 focus:ring-[#4b5a26]/20"
                            required>
                        <option value="">Pilih Penjualan</option>
                        @foreach ($penjualan as $p)
                            <option value="{{ $p->id }}" @selected(old('penjualan_id') == $p->id)>{{ $p->kode }}</option>
                        @endforeach
                    </select>
                    @error('penjualan_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-3xl border border-stone-200 bg-white">
            <div class="flex items-center justify-between px-6 py-5">
                <h2 class="text-lg font-bold text-stone-900">Daftar Barang</h2>
                <p class="text-sm text-stone-500">
                    <span id="selected-count">{{ count(old('items', [])) }}</span> dipilih ·
                    <span id="remaining-count">0</span> belum dikirim
                </p>
            </div>

            <ul id="item-list" class="divide-y divide-stone-200 border-t border-stone-200">
                <li id="empty-state" class="px-6 py-8 text-center text-sm text-stone-500">
                    Pilih penjualan terlebih dahulu untuk melihat daftar barang.
                </li>

                @foreach ($items as $item)
                    @php
                        $locked = filled($item->pengiriman?->nomor_resi);
                        $p = $item->pakaian;
                    @endphp
                    <li class="item-row hidden {{ $locked ? 'bg-stone-50' : '' }}"
                        data-penjualan="{{ $item->penjualan_id }}">
                        <label class="flex items-center gap-4 px-6 py-3 {{ $locked ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                            <input type="checkbox" name="items[]" value="{{ $item->id }}"
                                   class="item-checkbox h-5 w-5 rounded border-stone-300 accent-[#4b5a26]"
                                   @checked(in_array($item->id, old('items', [])))
                                   @disabled($locked)>

                            <div class="min-w-0 flex-1 {{ $locked ? 'opacity-50' : '' }}">
                                <p class="font-medium text-stone-900">{{ $p->nama }}</p>
                                <p class="text-sm text-stone-500">{{ $p->kode }} · {{ $p->kategori }}</p>
                            </div>

                            @if ($locked)
                                <span class="rounded-full bg-stone-200 px-3 py-1 text-xs font-medium text-stone-600">
                                    Sudah di resi {{ $item->pengiriman->nomor_resi }}
                                </span>
                            @endif

                            <span class="w-28 text-right font-bold text-stone-900 {{ $locked ? 'opacity-50' : '' }}">
                                Rp{{ number_format($item->harga_jual ?? $p->harga_jual, 0, ',', '.') }}
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>

            @error('items')
                <p class="px-6 pb-4 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm text-stone-500">Barang yang dipilih akan ditandai sudah masuk resi ini.</p>
            <div class="flex gap-3">
                <a href="{{ route('pengiriman.index') }}"
                   class="rounded-xl border border-stone-300 bg-white px-8 py-3 font-semibold text-stone-900 hover:bg-stone-50">Batal</a>
                <button type="submit" id="btn-simpan"
                        class="rounded-xl bg-[#4b5a26] px-6 py-3 font-semibold text-white hover:bg-[#3e4b1f] disabled:cursor-not-allowed disabled:opacity-50">
                    Simpan Pengiriman
                </button>
            </div>
        </div>
    </form>
@endsection
