{{-- resources/views/penjualan/pengiriman/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Pengiriman')

@section('content')
    {{-- Header --}}
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

        <nav class="hidden text-sm text-stone-500 md:block" aria-label="Breadcrumb">
            Dashboard › Penjualan › Pengiriman › <span class="font-semibold text-stone-900">Tambah</span>
        </nav>
    </div>

    <form id="form-pengiriman" method="POST" action="{{ route('pengiriman.store') }}" novalidate>
        @csrf

        {{-- Daftar Barang --}}
        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white">
            <div class="flex items-center justify-between px-6 py-5">
                <h2 class="text-lg font-bold text-stone-900">Daftar Barang</h2>
                <p class="text-sm text-stone-500">
                    <span id="selected-count">{{ count(old('items', [])) }}</span> dipilih ·
                    {{ $items->whereNull('nomor_resi')->count() }} belum dikirim
                </p>
            </div>

            <ul class="divide-y divide-stone-200 border-t border-stone-200">
                @forelse ($items as $item)
                    @php $locked = filled($item->nomor_resi); @endphp
                    <li class="{{ $locked ? 'bg-stone-50' : '' }}">
                        <label class="flex items-center gap-4 px-6 py-3 {{ $locked ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                            <input type="checkbox" name="items[]" value="{{ $item->id }}"
                                   class="item-checkbox h-5 w-5 rounded border-stone-300 accent-[#4b5a26]"
                                   @checked(in_array($item->id, old('items', [])))
                                   @disabled($locked)>

                            <div class="min-w-0 flex-1 {{ $locked ? 'opacity-50' : '' }}">
                                <p class="font-medium text-stone-900">{{ $item->nama }}</p>
                                <p class="text-sm text-stone-500">{{ $item->kode }} · {{ $item->kategori }}</p>
                            </div>

                            @if ($locked)
                                <span class="rounded-full bg-stone-200 px-3 py-1 text-xs font-medium text-stone-600">
                                    Sudah di resi {{ $item->nomor_resi }}
                                </span>
                            @endif

                            <span class="w-28 text-right font-bold text-stone-900 {{ $locked ? 'opacity-50' : '' }}">
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </label>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-sm text-stone-500">Belum ada barang untuk dikirim.</li>
                @endforelse
            </ul>

            @error('items')
                <p class="px-6 pb-4 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        {{-- Detail Pengiriman --}}
        <section class="mt-6 rounded-3xl border border-stone-200 bg-white p-6">
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
                        @foreach ($kurirList as $kurir)
                            <option value="{{ $kurir }}" @selected(old('kurir') === $kurir)>{{ $kurir }}</option>
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
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-stone-200 px-4 py-3 transition has-[:checked]:border-[#4b5a26] has-[:checked]:bg-[#f3f5e8]">
                            <input type="radio" name="ditanggung_oleh" value="owner" class="mt-1 h-4 w-4 accent-[#4b5a26]"
                                   @checked(old('ditanggung_oleh', 'owner') === 'owner')>
                            <span>
                                <span class="block font-semibold text-stone-900">Owner</span>
                                <span class="block text-sm text-stone-500">Ongkir dibayar toko</span>
                            </span>
                        </label>
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-stone-200 px-4 py-3 transition has-[:checked]:border-[#4b5a26] has-[:checked]:bg-[#f3f5e8]">
                            <input type="radio" name="ditanggung_oleh" value="pelanggan" class="mt-1 h-4 w-4 accent-[#4b5a26]"
                                   @checked(old('ditanggung_oleh') === 'pelanggan')>
                            <span>
                                <span class="block font-semibold text-stone-900">Pelanggan</span>
                                <span class="block text-sm text-stone-500">Ongkir dibayar pelanggan</span>
                            </span>
                        </label>
                    </div>
                    @error('ditanggung_oleh') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <fieldset>
                    <legend class="mb-1.5 text-sm font-semibold text-stone-900">Jenis Pengiriman</legend>
                    <div class="grid grid-cols-2 gap-1 rounded-xl border border-stone-300 p-1">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="kirim" class="peer sr-only"
                                   @checked(old('jenis', 'kirim') === 'kirim')>
                            <span class="block rounded-lg py-3 text-center font-medium text-stone-700 transition peer-checked:bg-[#4b5a26] peer-checked:text-white">Kirim</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="retur" class="peer sr-only"
                                   @checked(old('jenis') === 'retur')>
                            <span class="block rounded-lg py-3 text-center font-medium text-stone-700 transition peer-checked:bg-[#4b5a26] peer-checked:text-white">Retur</span>
                        </label>
                    </div>
                    @error('jenis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <div>
                    <label for="kode_penjualan" class="mb-1.5 block text-sm font-semibold text-stone-900">Kode Penjualan</label>
                    <select id="kode_penjualan" name="kode_penjualan"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3.5 text-stone-900 focus:border-[#4b5a26] focus:outline-none focus:ring-2 focus:ring-[#4b5a26]/20"
                            required>
                        @foreach ($penjualanList as $p)
                            <option value="{{ $p->kode_penjualan }}" @selected(old('kode_penjualan') === $p->kode_penjualan)>
                                {{ $p->kode_penjualan }} · {{ $p->nama_pelanggan }}
                            </option>
                        @endforeach
                    </select>
                    @error('kode_penjualan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Footer --}}
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
