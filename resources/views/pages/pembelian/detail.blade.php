@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
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

        <div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-semibold text-stone-900">{{ $pembelian->kode }}</h1>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $pembelian->status->badgeColor() }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $pembelian->status->dotColor() }}"></span>
                        {{ $pembelian->status->label() }}
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
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck preview-icon">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                            <path d="M15 18H9"/>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                            <circle cx="17" cy="18" r="2"/>
                            <circle cx="7" cy="18" r="2"/>
                        </svg>
                        {{ $pembelian->supplier->nama }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2.5 print:hidden">
                <button type="button" onclick="window.print()"
                        class="inline-flex h-11 items-center gap-2 rounded-lg border border-stone-300 bg-white px-4 font-medium text-stone-900 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 9V3h12v6" /><rect x="3" y="9" width="18" height="9" rx="2" /><path d="M7 14h10v7H7z" />
                    </svg>
                    Cetak Nota
                </button>

                <a href="" class="inline-flex h-11 items-center gap-2 rounded-lg border border-stone-300 bg-white px-4 font-medium text-stone-900 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3v12" /><path d="m7 10 5 5 5-5" /><path d="M4 20h16" />
                    </svg>
                    Download
                </a>

                @if ($pembelian->status->value === 'belum bayar')
                    <a href="{{ route('pembelian.edit', $pembelian) }}"
                       class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand px-4 font-medium text-white transition hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9" /><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                        </svg>
                        Edit
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6 grid items-start gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                    <section class="rounded-xl border border-stone-300 bg-white p-4" aria-labelledby="judul-pakaian">
                        <div class="flex items-center justify-between">
                            <h2 id="judul-pakaian" class="text-lg font-semibold text-stone-900">Daftar Pakaian</h2>
                            <span class="rounded-full bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-700 ring-1 ring-inset ring-stone-300">
                                {{ $pembelian->pakaian->count() }} barang
                            </span>
                        </div>

                        <ul class="mt-3 divide-y divide-dashed divide-stone-300">
                            @foreach ($pembelian->pakaian as $pakaian)
                                <li class="flex flex-wrap items-center gap-4 py-3.5">
                                    <span aria-hidden="true"
                                          class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z" />
                                        </svg>
                                    </span>

                                    <div class="min-w-0 flex-1 basis-56">
                                        <p class="text-xs font-medium tracking-wide text-stone-500">{{ $pakaian->kode }}</p>
                                        <p class="break-words font-semibold text-stone-900">{{ $pakaian->nama }}</p>
                                        <div class="mt-1 flex flex-wrap gap-1.5">
                                            <span class="rounded-md bg-stone-100 px-2 py-0.5 text-xs text-stone-700">Lingkar {{ $pakaian->lingkar }} cm</span>
                                            <span class="rounded-md bg-stone-100 px-2 py-0.5 text-xs text-stone-700">Panjang {{ $pakaian->panjang }} cm</span>
                                        </div>
                                    </div>

                                    <p class="ml-auto font-bold text-stone-900">{{ format_rupiah($pakaian->harga_beli) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="rounded-xl border border-stone-300 bg-white p-4" aria-labelledby="judul-ringkasan">
                        <div class="flex items-center justify-between">
                            <h2 id="judul-ringkasan" class="text-lg font-semibold text-stone-900">Ringkasan Pembelian</h2>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $pembelian->status->badgeColor() }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $pembelian->status->dotColor() }}"></span>
                                {{ $pembelian->status->label() }}
                            </span>
                        </div>

                        <div class="mt-3 flex items-center justify-between border-t border-stone-200 pt-3">
                            <span class="font-semibold text-stone-900">Total</span>
                            @if ($pembelian->status->value === 'sudah bayar')
                                <span class="text-xl font-bold text-emerald-700">{{ format_rupiah($pembelian->total) }}</span>
                            @else
                                <span class="text-xl font-bold text-red-700">{{ format_rupiah($pembelian->total) }}</span>
                            @endif
                        </div>
                    </section>
            </div>

            <div class="space-y-5">
                <section class="rounded-md border border-stone-300 bg-white p-4" aria-labelledby="judul-supplier">
                    <h2 id="judul-supplier" class="text-base font-bold text-stone-900">Informasi Supplier</h2>

                    <div class="mt-4 flex items-center gap-3.5">
                        <span aria-hidden="true"
                              class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand font-bold text-white">
                            {{ $pembelian->supplier->inisial }}
                        </span>
                        <div class="min-w-0 space-y-1.5">
                            <p class="break-words text-sm font-semibold text-stone-900">{{ $pembelian->supplier->nama }}</p>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-700 ring-1 ring-inset ring-stone-300">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                                {{ $pembelian->supplier->nomor_telepon }}
                            </span>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-stone-300 bg-white p-4" aria-labelledby="judul-alamat">
                    <h2 id="judul-alamat" class="text-base font-bold text-stone-900">Alamat Pengiriman</h2>

                    <dl class="mt-4 grid grid-cols-5 gap-x-4 gap-y-3.5 text-sm">
                        <dt class="col-span-2 font-semibold text-stone-900">Alamat</dt>
                        <dd class="col-span-3 text-left font-semibold text-stone-900">Jl. Graha Angkasa RRI No.A15</dd>
                    </dl>
                    <dl class="mt-4 grid grid-cols-5 gap-x-4 gap-y-3.5 text-sm">
                        <dt class="col-span-2 font-semibold text-stone-900">Kelurahan</dt>
                        <dd class="col-span-3 text-left font-semibold text-stone-900">Kedungrejo</dd>
                    </dl>
                    <dl class="mt-4 grid grid-cols-5 gap-x-4 gap-y-3.5 text-sm">
                        <dt class="col-span-2 font-semibold text-stone-900">kecamatan</dt>
                        <dd class="col-span-3 text-left font-semibold text-stone-900">Waru</dd>
                    </dl>
                    <dl class="mt-4 grid grid-cols-5 gap-x-4 gap-y-3.5 text-sm">
                        <dt class="col-span-2 font-semibold text-stone-900">Kota/Kabupaten</dt>
                        <dd class="col-span-3 text-left font-semibold text-stone-900">Kabupaten Sidorajo</dd>
                    </dl>
                    <dl class="mt-4 grid grid-cols-5 gap-x-4 gap-y-3.5 text-sm">
                        <dt class="col-span-2 font-semibold text-stone-900">Provinsi</dt>
                        <dd class="col-span-3 text-left font-semibold text-stone-900">Jawa Timur</dd>
                    </dl>
                    <dl class="mt-4 grid grid-cols-5 gap-x-4 gap-y-3.5 text-sm">
                        <dt class="col-span-2 font-semibold text-stone-900">Kode Pos</dt>
                        <dd class="col-span-3 text-left font-semibold text-stone-900">61256</dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>
@endsection
