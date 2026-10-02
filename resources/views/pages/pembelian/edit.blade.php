@extends('layouts.app')

@section('title', 'Edit Pembelian')

@section('content')
    @php
        $field = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
        $cell = 'w-full rounded-lg border border-stone-300 bg-white px-2.5 py-2 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-400 aria-invalid:focus:border-red-500 aria-invalid:focus:ring-red-500';
        $kategoriList = ['Blouse', 'Kemeja', 'Rok', 'Celana', 'Overall', 'Outher', 'Jaket', 'Vest', 'Gamis', 'Dress'];

        $lockedIds = $pembelian->barang->where('status', '!=', 'Tersedia')->pluck('id')->all();

        $rows = old('items') ?: $pembelian->barang->map(fn ($it) => [
            'id'         => $it->id,
            'nama'       => $it->nama,
            'kategori'   => $it->kategori,
            'lingkar'    => (float) $it->lingkar,
            'panjang'    => (float) $it->panjang,
            'harga_beli' => (int) $it->harga_beli,
            'harga_jual' => (int) $it->harga_jual,
        ])->all();
        $rows = $rows ?: [[]];
    @endphp

    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('pembelian.index') }}"
               class="inline-flex items-center gap-1.5 rounded text-stone-500 transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6" /></svg>
                Kembali ke daftar pembelian
            </a>

            <nav aria-label="Breadcrumb" class="order-first sm:order-last">
                <ol class="flex items-center gap-1.5 text-stone-500">
                    <li>
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}"
                           class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">Dashboard</a>
                    </li>
                    <li aria-hidden="true"><svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg></li>
                    <li><a class="rounded transition hover:text-link">Pembelian</a></li>
                    <li aria-hidden="true"><svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg></li>
                    <li>
                        <a href="{{ route('pembelian.index') }}"
                           class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">Daftar Pembelian</a>
                    </li>
                    <li aria-hidden="true"><svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg></li>
                    <li class="font-medium text-stone-900" aria-current="page">Edit Pembelian</li>
                </ol>
            </nav>
        </div>

        <h1 class="mt-4 text-2xl font-semibold text-stone-900">Edit Pembelian</h1>

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

        @if ($lockedIds)
            <p class="mt-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Pada barang yang sudah terjual. Harga Beli tidak dapat diubah dan tidak dapat dihapus.
            </p>
        @endif

        <form id="form-pembelian" method="POST" action="{{ route('pembelian.update', $pembelian) }}" novalidate class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <section class="overflow-hidden rounded-xl border border-stone-300 bg-white">
                <header class="flex items-center gap-3 border-b border-stone-300 px-4 py-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs font-semibold text-white">1</span>
                    <h2 class="font-semibold text-stone-900">Informasi Pembelian</h2>
                </header>

                <div class="grid gap-4 p-4 sm:grid-cols-3">
                    <div>
                        <label for="kode" class="mb-1.5 block text-stone-900">Kode Pembelian</label>
                        <input type="text" id="kode" value="{{ $pembelian->kode }}" readonly aria-describedby="hint-kode"
                               class="w-full cursor-not-allowed rounded-lg border border-stone-300 bg-stone-100 px-3 py-2 text-stone-500 focus:outline-none">
                        <p id="hint-kode" class="mt-1.5 text-xs text-stone-500">Kode tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label for="tanggal" class="mb-1.5 block text-stone-900">
                            Tanggal Pembelian <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal"
                               value="{{ old('tanggal', \Illuminate\Support\Carbon::parse($pembelian->tanggal)->format('Y-m-d')) }}"
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
                                <option value="{{ $s->id }}" @selected(old('supplier_id', $pembelian->supplier_id) == $s->id)>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-stone-300 bg-white">
                <header class="flex items-center justify-between border-b border-stone-300 px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs font-semibold text-white">2</span>
                        <h2 class="font-semibold text-stone-900">Daftar Barang</h2>
                    </div>

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
                                @php
                                    $locked = !empty($item['id']) && in_array($item['id'], $lockedIds);
                                    // Barang terjual: hanya Harga Beli yang dikunci
                                    $lockedCell = str_replace('bg-white', 'bg-stone-100 text-stone-500', $cell);
                                    $bad = fn ($f) => $errors->has("items.$i.$f");
                                @endphp
                                <tr data-row data-index="{{ $i }}" class="align-top">
                                    <td class="px-4 py-2">
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item['id'] ?? '' }}">
                                        <input type="text" name="items[{{ $i }}][nama]" value="{{ $item['nama'] ?? '' }}"
                                               data-cell="nama" maxlength="255" autocomplete="off" placeholder="Nama barang"
                                               @if ($bad('nama')) aria-invalid="true" @endif
                                               class="{{ $cell }}">
                                    </td>

                                    <td class="px-2 py-2">
                                        <select name="items[{{ $i }}][kategori]" data-cell="kategori"
                                                @if ($bad('kategori')) aria-invalid="true" @endif
                                                class="{{ $cell }}">
                                            <option value="" disabled @selected(empty($item['kategori']))>Pilih</option>
                                            @foreach ($kategoriList as $kat)
                                                <option value="{{ $kat }}" @selected(($item['kategori'] ?? '') === $kat)>{{ $kat }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    @foreach (['lingkar', 'panjang'] as $ukuran)
                                        <td class="px-2 py-2">
                                            <input type="number" step="any" min="0" inputmode="decimal"
                                                   name="items[{{ $i }}][{{ $ukuran }}]" value="{{ $item[$ukuran] ?? '' }}"
                                                   data-cell="{{ $ukuran }}" placeholder="0"
                                                   @if ($bad($ukuran)) aria-invalid="true" @endif
                                                   class="{{ $cell }}">
                                        </td>
                                    @endforeach

                                    @foreach (['harga_beli', 'harga_jual'] as $money)
                                        @php $moneyLocked = $locked && $money === 'harga_beli'; @endphp
                                        <td class="px-2 py-2">
                                            <div class="relative">
                                                <span class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-stone-500">Rp</span>
                                                <input type="text" inputmode="numeric" autocomplete="off" data-money
                                                       name="items[{{ $i }}][{{ $money }}]" value="{{ $item[$money] ?? '' }}"
                                                       data-cell="{{ $money }}" placeholder="0"
                                                       @if ($moneyLocked) readonly tabindex="-1" title="Harga beli barang terjual tidak dapat diubah" @endif
                                                       @if ($bad($money)) aria-invalid="true" @endif
                                                       class="{{ $moneyLocked ? $lockedCell : $cell }} pl-8">
                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="px-2 py-2 text-center">
                                        @if ($locked)
                                            <span class="inline-flex h-9 w-9 items-center justify-center text-stone-400" title="Barang sudah terjual, tidak dapat dihapus" aria-label="Barang tidak dapat dihapus">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2" /><path d="M8 11V7a4 4 0 0 1 8 0v4" /></svg>
                                            </span>
                                        @else
                                            <button type="button" data-remove aria-label="Hapus baris"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-md text-stone-500 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                            <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                        </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <template id="row-template">
                    <tr data-row data-index="__INDEX__" class="align-top">
                        <td class="px-4 py-2">
                            <input type="hidden" name="items[__INDEX__][id]" value="">
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

                        @foreach (['lingkar', 'panjang'] as $ukuran)
                            <td class="px-2 py-2">
                                <input type="number" step="any" min="0" inputmode="decimal"
                                       name="items[__INDEX__][{{ $ukuran }}]" value=""
                                       data-cell="{{ $ukuran }}" placeholder="0"
                                       class="{{ $cell }}">
                            </td>
                        @endforeach

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
                        <a href="{{ route('pembelian.index') }}"
                           class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            Batal
                        </a>
                        <button type="submit" id="btn-submit"
                                class="rounded-lg bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                            Simpan
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>
@endsection
