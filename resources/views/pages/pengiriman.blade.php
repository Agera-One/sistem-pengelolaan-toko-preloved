@extends('layouts.app')

@section('title', 'Daftar Pengiriman')

@section('content')
    <div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">Daftar Pengiriman</h1>
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
                    <li class="font-medium text-stone-900" aria-current="page">Daftar Pengiriman</li>
                </ol>
            </nav>
        </div>

        @if (session('error'))
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 rounded-xl border border-stone-300 bg-white">
            <div class="flex flex-col gap-3 rounded-t-xl border-b border-stone-300 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('pengiriman.index') }}" class="relative w-full sm:max-w-xs">
                        <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                        <input type="hidden" name="ditanggung" value="{{ request('ditanggung') }}">
                        @foreach ($kurirDipilih as $kv)
                            <input type="hidden" name="kurir[]" value="{{ $kv }}">
                        @endforeach
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                            placeholder="Cari nomor resi"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 pl-9 pr-3 text-stone-900 placeholder:text-stone-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    </form>

                    <form method="GET" action="{{ route('pengiriman.index') }}" class="w-full sm:w-40">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="ditanggung" value="{{ request('ditanggung') }}">
                        @foreach ($kurirDipilih as $kv)
                            <input type="hidden" name="kurir[]" value="{{ $kv }}">
                        @endforeach
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <select name="jenis" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 px-3 text-stone-900 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                            <option value="">Semua Jenis</option>
                            @foreach ($jenis as $j)
                                <option value="{{ $j->value }}" {{ request('jenis') === $j->value ? 'selected' : '' }}>
                                    {{ $j->label() }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <form method="GET" action="{{ route('pengiriman.index') }}" class="w-full sm:w-48">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                        @foreach ($kurirDipilih as $kv)
                            <input type="hidden" name="kurir[]" value="{{ $kv }}">
                        @endforeach
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <select name="ditanggung" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-stone-300 bg-white py-2 px-3 text-stone-900 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                            <option value="">Semua Penanggung</option>
                            @foreach ($ditanggung as $d)
                                <option value="{{ $d->value }}" {{ request('ditanggung') === $d->value ? 'selected' : '' }}>
                                    {{ $d->label() }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <form method="GET" action="{{ route('pengiriman.index') }}" class="w-full sm:w-44"> 
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                        <input type="hidden" name="ditanggung" value="{{ request('ditanggung') }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                        <details data-multiselect class="group relative">
                            <summary
                                class="flex w-full cursor-pointer list-none items-center justify-between gap-2 rounded-lg border border-stone-300 bg-white px-3 py-2 text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand group-open:border-brand [&::-webkit-details-marker]:hidden">
                                <span class="truncate">
                                    @if (count($kurirDipilih) === 0)
                                        Semua Kurir
                                    @elseif (count($kurirDipilih) === 1)
                                        {{ collect($kurir)->first(fn ($k) => $k->value === $kurirDipilih[0])?->label() ?? '1 kurir' }}
                                    @else
                                        {{ count($kurirDipilih) }} kurir dipilih
                                    @endif
                                </span>
                                <svg class="h-4 w-4 shrink-0 text-stone-500 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </summary>

                            <div class="absolute left-0 z-40 mt-1 w-60 rounded-xl border border-stone-300 bg-white p-2 shadow-lg">
                                <p class="px-3 pb-1 pt-2 text-sm text-stone-500">Filter kurir</p>

                                <div class="max-h-64 overflow-y-auto">
                                    @foreach ($kurir as $k)
                                        <label class="flex cursor-pointer items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10">
                                            <input type="checkbox" name="kurir[]" value="{{ $k->value }}"
                                                {{ in_array($k->value, $kurirDipilih, true) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-stone-300 text-brand accent-brand focus:ring-brand">
                                            <span>{{ $k->label() }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mt-2 grid grid-cols-2 gap-2 px-1 pb-1 pt-1">
                                    <button type="button" data-multiselect-clear
                                            class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-center font-medium text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                        Reset
                                    </button>
                                    <button type="submit"
                                            class="rounded-lg bg-brand px-4 py-2 text-center font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </details>
                    </form>

                    <form method="GET" action="{{ route('pengiriman.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                        <input type="hidden" name="ditanggung" value="{{ request('ditanggung') }}">
                        @foreach ($kurirDipilih as $kv)
                            <input type="hidden" name="kurir[]" value="{{ $kv }}">
                        @endforeach

                        <x-daterange
                            name-start="tanggal_mulai"
                            name-end="tanggal_selesai"
                            :start="request('tanggal_mulai')"
                            :end="request('tanggal_selesai')"
                        />
                    </form>

                    <a href="{{ route('pengiriman.index') }}"
                    class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-center font-medium text-stone-700 transition hover:bg-stone-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                        Reset
                    </a>
                </div>

                <button type="button" data-pengiriman-tambah
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Pengiriman
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-brand/5 text-stone-600 border-b border-stone-300">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3 font-medium">No</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nomor Resi</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kode Penjualan</th>
                            <th scope="col" class="px-4 py-3 font-medium">Jenis</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kurir</th>
                            <th scope="col" class="px-4 py-3 font-medium">Ditanggung Oleh</th>
                            <th scope="col" class="px-4 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Ongkir</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-300">
                        @forelse ($pengiriman as $p)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-4 py-3.5 text-stone-500">
                                    {{ $pengiriman->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-700">
                                    {{ $p->nomor_resi }}
                                </td>

                                <td class="px-4 py-3.5 font-medium text-stone-900">
                                    {{ $p->penjualan->kode }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700">
                                    {{ $p->jenis->label() }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700">
                                    {{ $p->kurir->label() }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700">
                                    {{ $p->ditanggung_oleh->label() }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700">
                                    {{ $p->tanggal->translatedFormat('d F Y') }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-700">
                                    {{ format_rupiah($p->ongkir) }}
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            data-menu-toggle="menu-pengiriman-{{ $p->getKey() }}"
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

                                    <div id="menu-pengiriman-{{ $p->getKey() }}" role="menu" hidden class="fixed z-50 w-44 rounded-lg border border-stone-300 bg-white p-1 text-left shadow-lg">
                                        <button type="button" role="menuitem" data-pengiriman-detail data-pengiriman="{{ json_encode($p->payload) }}" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Detail
                                        </button>

                                        <button type="button" role="menuitem" data-pengiriman-ubah data-pengiriman="{{ json_encode($p->payload) }}" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-stone-700 transition hover:bg-brand/10 hover:text-brand focus:bg-brand/10 focus:outline-none">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <form action="{{ route('pengiriman.destroy', $p) }}" method="POST"
                                            data-confirm="Hapus pengiriman {{ $p->kode }}? Data yang dihapus tidak bisa dikembalikan.">
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
                                <td colspan="9" class="px-4 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand/10 text-brand">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-van preview-icon">
                                                <path d="M13 6v5a1 1 0 0 0 1 1h6.102a1 1 0 0 1 .712.298l.898.91a1 1 0 0 1 .288.702V17a1 1 0 0 1-1 1h-3"/>
                                                <path d="M5 18H3a1 1 0 0 1-1-1V8a2 2 0 0 1 2-2h12c1.1 0 2.1.8 2.4 1.8l1.176 4.2"/>
                                                <path d="M9 18h5"/>
                                                <circle cx="16" cy="18" r="2"/>
                                                <circle cx="7" cy="18" r="2"/>
                                            </svg>
                                        </span>

                                        @if (request()->filled('q'))
                                            <p class="mt-4 font-medium text-stone-900">Pengiriman tidak ditemukan</p>
                                            <p class="mt-1 text-stone-500">
                                                Tidak ada hasil untuk "{{ request('q') }}". Coba kata kunci lain.
                                            </p>
                                            <a href="{{ route('pengiriman.index') }}"
                                               class="mt-4 font-medium text-link hover:text-link-hover hover:underline">
                                                Tampilkan semua pengiriman
                                            </a>
                                        @else
                                            <p class="mt-4 font-medium text-stone-900">Belum ada pengiriman</p>
                                            <p class="mt-1 text-stone-500">
                                                Tambahkan pengiriman pertama Anda untuk mulai mencatat pengiriman.
                                            </p>
                                            <button type="button" data-pengiriman-tambah
                                                    class="mt-4 rounded-lg bg-brand px-4 py-2 font-medium text-white transition hover:bg-brand-hover">
                                                Tambah Pengiriman
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$pengiriman" />
        </div>
    </div>
@endsection
