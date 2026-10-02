@extends('layouts.app')

@section('title', 'Detail Pembelian ' . $pembelian->kode)

@section('content')
    @php
        $statusStyle = fn (?string $status) => match (strtolower(trim((string) $status))) {
            'belum bayar' => [
                'badge' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-300',
                'dot'   => 'bg-red-500',
            ],
            'sudah bayar', 'tersedia' => [
                'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-300',
                'dot'   => 'bg-emerald-500',
            ],
            'terjual' => [
                'badge' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-300',
                'dot'   => 'bg-blue-500',
            ]
        };

        $statusPembelian = $statusStyle($pembelian->status);

        $inisial = fn (?string $nama) => \Illuminate\Support\Str::of($nama ?? '?')
            ->trim()->explode(' ')->filter()->take(2)
            ->map(fn ($kata) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($kata, 0, 1)))
            ->implode('');

        $rupiah = fn ($angka) => 'Rp' . number_format($angka, 0, ',', '.');

        $supplier = $pembelian->supplier;
        $user = $pembelian->user;
    @endphp

    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('pembelian.index') }}"
               class="inline-flex items-center gap-1.5 text-stone-500 transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Kembali ke daftar pembelian
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
                        <a href="{{ route('pembelian.index') }}"
                           class="rounded transition hover:text-link focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">Daftar Pembelian</a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                    </li>
                    <li class="font-medium text-stone-900" aria-current="page">Detail Pembelian</li>
                </ol>
            </nav>
        </div>

        @if (session('error'))
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mt-6">
            <div class="mt-1 flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold text-stone-900">{{ $pembelian->kode }}</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusPembelian['badge'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $statusPembelian['dot'] }}"></span>
                    {{ $pembelian->status }}
                </span>
            </div>
            <div class="mt-2 flex flex-wrap items-center gap-x-5 gap-y-1 text-stone-500">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                    {{ $pembelian->tanggal->translatedFormat('d F Y') }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="3" width="16" height="18" rx="2" /><path d="M9 7h1M14 7h1M9 11h1M14 11h1M10 21v-4h4v4" />
                    </svg>
                    {{ $supplier->nama }}
                </span>
            </div>
        </div>

        {{-- Kartu Supplier & Pembuat --}}
        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <section class="rounded-xl border border-stone-300 bg-white p-4" aria-labelledby="judul-supplier">
                <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                    <h2 id="judul-supplier" class="text-lg font-semibold text-stone-900">Supplier</h2>
                    <span class="inline-flex rounded-md bg-stone-100 px-2 py-1 text-xs font-medium text-stone-700 ring-1 ring-inset ring-stone-300">Pengirim</span>
                </div>

                <div class="mt-4 flex items-center gap-4">
                    <span aria-hidden="true" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-brand text-lg font-semibold text-white">
                        {{ $inisial($supplier->nama) }}
                    </span>
                    <div class="min-w-0">
                        <p class="break-words text-lg font-semibold text-stone-900">{{ $supplier->nama }}</p>
                        <p class="text-sm text-stone-700">{{ $supplier->kode }}</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-2.5">
                        <p class="text-xs text-stone-500">Nomor Telepon / WA</p>
                        <p class="mt-1 flex items-center gap-2 font-medium text-stone-900">
                            <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            {{ $supplier->nomor_telepon ?: '-' }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-2.5">
                        <p class="text-xs text-stone-500">Lokasi Pengirim</p>
                        <p class="mt-1 flex items-center gap-2 font-medium text-stone-900">
                            <svg class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z" /><circle cx="12" cy="10" r="3" />
                            </svg>
                            {{ $supplier->kota ?: '-' }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-stone-300 bg-white p-4" aria-labelledby="judul-pembuat">
                <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                    <h2 id="judul-pembuat" class="text-lg font-semibold text-stone-900">Dibuat Oleh</h2>
                    <span class="inline-flex rounded-md bg-stone-100 px-2 py-1 text-xs font-medium text-stone-700 ring-1 ring-inset ring-stone-300">Penerima</span>
                </div>

                <div class="mt-4 flex items-center gap-4">
                    <span aria-hidden="true" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-brand text-lg font-semibold text-white">
                        {{ $inisial($user->name) }}
                    </span>
                    <div class="min-w-0">
                        <p class="break-words text-lg font-semibold text-stone-900">{{ $user->name }}</p>
                        <p class="break-all text-sm text-stone-700">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-2.5">
                        <p class="text-xs text-stone-500">Kontak Personil</p>
                        <p class="mt-1 flex items-center gap-2 font-medium text-stone-900">
                            <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            {{ $user->nomor_telepon ?? '-' }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-2.5">
                        <p class="text-xs text-stone-500">Alamat Penerima</p>
                        <p class="mt-1 flex items-center gap-2 font-medium text-stone-900">
                            <svg class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z" /><circle cx="12" cy="10" r="3" />
                            </svg>
                            {{ $user->alamat ?? '-' }}
                        </p>
                    </div>
                </div>
            </section>
        </div>

        {{-- Daftar Barang --}}
        <section class="mt-6 overflow-hidden rounded-xl border border-stone-300 bg-white" aria-labelledby="judul-barang">
            <div class="flex flex-col gap-3 border-b border-stone-300 p-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 id="judul-barang" class="text-lg font-semibold text-stone-900">Daftar Barang</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-stone-300 bg-brand/5 text-stone-600">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode Barang</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nama Barang</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kategori</th>
                            <th scope="col" class="px-4 py-3 font-medium">Lingkar</th>
                            <th scope="col" class="px-4 py-3 font-medium">Panjang</th>
                            <th scope="col" class="px-4 py-3 font-medium">Harga Beli</th>
                            <th scope="col" class="px-4 py-3 font-medium">Harga Jual</th>
                            <th scope="col" class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @foreach ($pembelian->barang as $barang)
                            @php $statusBarang = $statusStyle($barang->status); @endphp

                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">{{ $loop->iteration }}</td>

                                <td class="px-4 py-3.5">
                                    <p class="font-medium text-stone-900">{{ $barang->kode }}</p>
                                </td>

                                <td class="px-4 py-3.5">
                                    {{ $barang->nama }}
                                </td>

                                <td class="px-4 py-3.5">
                                    {{ $barang->kategori }}
                                </td>

                                <td class="px-4 py-3.5">
                                    {{ $barang->lingkar . ' cm' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    {{ $barang->panjang . ' cm' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    {{ $rupiah($barang->harga_beli) }}
                                </td>

                                <td class="px-4 py-3.5">
                                    {{ $rupiah($barang->harga_jual) }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusBarang['badge'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusBarang['dot'] }}"></span>
                                        {{ $barang->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-end gap-6 border-t border-stone-300 bg-stone-50 px-4 py-4">
                <span>Total Harga Beli</span>
                <span class="text-xl font-semibold">{{ $rupiah($pembelian->total) }}</span>
            </div>
        </section>
    </div>
@endsection
