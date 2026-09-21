@php
    // Satu sumber untuk nama toko. Ganti di sini, sidebar dan header mobile ikut berubah.
    $storeName = 'TRISTANTI STORE';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') - {{ $storeName }}</title>

    @vite(['resources/css/app.css', 'resources/js/sidebar.js'])
</head>

<body class="min-h-screen bg-white text-stone-800 antialiased">

    {{-- Memudahkan pengguna keyboard langsung ke isi halaman --}}
    <a href="#konten-utama"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-brand focus:px-4 focus:py-3 focus:text-lg focus:font-semibold focus:text-white">
        Lewati ke konten utama
    </a>

    <x-sidebar :store-name="$storeName" />

    {{-- Sisi kanan: bergeser selebar sidebar (w-72) di layar besar --}}
    <div id="app-content" class="lg:pl-72">

        {{-- Bilah atas khusus HP/tablet, berisi tombol pembuka menu --}}
        <header class="sticky top-0 z-30 flex items-center justify-between gap-3 border-b border-stone-200 bg-white px-5 py-3 sm:px-8 lg:hidden">
            <button type="button" id="sidebar-open" aria-controls="sidebar" aria-expanded="false"
                    class="flex min-h-12 items-center gap-2 rounded-lg border-2 border-stone-300 bg-white px-4 text-lg font-semibold text-stone-800 transition duration-200 ease-in-out hover:bg-stone-100 focus:outline-none focus-visible:ring-4 focus-visible:ring-brand/40">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <span>Menu</span>
            </button>
            <p class="truncate font-serif text-lg font-bold text-stone-900">{{ $storeName }}</p>
        </header>

        <main id="konten-utama" class="px-5 py-8 sm:px-8 lg:px-12 lg:py-10">
            @yield('content')
        </main>
    </div>

</body>
</html>
