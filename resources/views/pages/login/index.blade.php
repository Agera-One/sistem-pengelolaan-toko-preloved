<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Sistem Pengelolaan Toko Preloved</title>

    @vite(['resources/css/app.css', 'resources/js/login.js'])
</head>

<body class="min-h-screen bg-stone-50 text-stone-800 antialiased">

<main class="grid min-h-screen grid-cols-1 transition-opacity duration-300 ease-in-out starting:opacity-0 motion-reduce:transition-none md:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] lg:grid-cols-2">

    {{-- ============================================================
         KOLOM KIRI: Identitas toko (disembunyikan di mobile)
         ============================================================ --}}
    <aside class="relative hidden flex-col justify-between gap-12 overflow-hidden bg-linear-to-b from-stone-800 to-stone-900 p-8 md:flex lg:p-12 xl:p-16">

        {{-- Cahaya olive lembut di pojok kiri atas (dekoratif) --}}
        <div aria-hidden="true"
             class="pointer-events-none absolute inset-0 bg-radial-[circle_at_top_left] from-brand/45 to-transparent to-55%"></div>

        {{-- Belah ketupat di pojok kanan bawah (dekoratif, hanya di layar lebar) --}}
        <svg viewBox="0 0 320 320" fill="none" stroke="currentColor" aria-hidden="true"
             class="pointer-events-none absolute -bottom-24 -right-24 hidden h-80 w-80 text-brand-light opacity-20 lg:block">
            <rect x="60" y="60" width="200" height="200" stroke-width="10" transform="rotate(45 160 160)"/>
            <rect x="100" y="100" width="120" height="120" stroke-width="8" transform="rotate(45 160 160)"/>
            <rect x="130" y="130" width="60" height="60" fill="currentColor" stroke="none" transform="rotate(45 160 160)"/>
        </svg>

        <div class="relative">
            {{-- Ganti blok ini dengan logo asli, contoh:
                 <img src="{{ asset('images/logo.png') }}" alt="Nama Toko Anda" class="h-12 w-auto"> --}}
            <p class="flex items-center gap-3 font-serif text-2xl font-bold text-stone-50">
                <svg class="h-6 w-6 shrink-0 text-brand-light" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2 22 12 12 22 2 12Z"/>
                    <path d="M12 8 16 12 12 16 8 12Z" fill="currentColor"/>
                </svg>
                TRISTANTI STORE
            </p>

            <p class="mt-10 font-serif text-3xl font-bold leading-tight text-stone-50 lg:text-4xl xl:text-5xl">
                Sistem Pengelolaan Toko Preloved
            </p>

            <p class="mt-5 max-w-sm text-lg leading-relaxed text-stone-200 xl:text-xl">
                Kelola barang, penjualan, pembelian, dan stok dengan lebih mudah.
            </p>
        </div>

        {{-- Ilustrasi sederhana: rak pakaian. Dekoratif, jadi disembunyikan dari screen reader. --}}
        <svg viewBox="0 0 400 225" class="relative h-auto w-full max-w-md text-stone-400" fill="none" stroke="currentColor"
             stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            {{-- Rak --}}
            <path d="M30 40H370" stroke-width="6"/>
            <path d="M50 40V215M350 40V215M30 215H70M330 215H370" stroke-width="6"/>

            {{-- Kaos --}}
            <g transform="translate(105 40)">
                <path d="M0 0V10"/>
                <path d="M0 10L-28 24H28Z"/>
                <path class="fill-brand stroke-stone-300" stroke-width="2"
                      d="M-28 24L-50 38L-40 54L-28 48V112H28V48L40 54L50 38L28 24Q0 38 -28 24Z"/>
            </g>

            {{-- Dress --}}
            <g transform="translate(200 40)">
                <path d="M0 0V10"/>
                <path d="M0 10L-24 24H24Z"/>
                <path class="fill-amber-800 stroke-stone-300" stroke-width="2"
                      d="M-22 24L-28 62L-40 140H40L28 62L22 24Q0 36 -22 24Z"/>
            </g>

            {{-- Celana --}}
            <g transform="translate(295 40)">
                <path d="M0 0V10"/>
                <path d="M0 10L-26 24H26Z"/>
                <path class="fill-stone-200 stroke-stone-400" stroke-width="2"
                      d="M-24 24H24L30 128H8L0 66L-8 128H-30Z"/>
            </g>
        </svg>
    </aside>

    {{-- ============================================================
         KOLOM KANAN: Form login
         ============================================================ --}}
    <section class="flex items-center justify-center px-5 py-10 sm:px-6 md:px-8 lg:px-16">
        <div class="w-full max-w-md">

            {{-- Identitas ringkas untuk mobile (kolom kiri disembunyikan) --}}
            <div class="mb-8 md:hidden">
                <p class="font-serif text-xl font-bold text-link">TRISTANTI STORE</p>
                <p class="mt-1 text-base text-stone-700">Sistem Pengelolaan Toko Preloved</p>
            </div>

            <h1 class="font-serif text-4xl font-bold text-stone-900">Selamat Datang</h1>
            <p class="mt-1 text-lg text-stone-700">Kelola toko preloved Anda dengan lebih mudah.</p>

            {{-- Pesan status (contoh: setelah password berhasil diatur ulang) --}}
            @if (session('status'))
                <div class="mt-6 flex items-start gap-3 rounded-lg border-2 border-brand bg-white p-4 text-lg text-stone-900"
                     role="status">
                    <svg class="mt-0.5 h-6 w-6 shrink-0 text-brand" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login.attempt') }}" class="mt-8 space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-2 block text-lg font-semibold text-stone-900">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email Anda"
                        autocomplete="username"
                        inputmode="email"
                        autocapitalize="none"
                        spellcheck="false"
                        required
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                        @class([
                            'block h-14 w-full rounded-lg border-2 bg-white px-4 text-lg text-stone-900 placeholder:text-stone-500 transition duration-200 ease-in-out focus:outline-none focus:ring-4',
                            'border-stone-500 focus:border-brand focus:ring-brand/25' => ! $errors->has('email'),
                            'border-red-700 focus:border-red-700 focus:ring-red-700/25' => $errors->has('email'),
                        ])
                    >
                    @error('email')
                        <p id="email-error" class="mt-2 flex items-start gap-2 rounded-lg bg-red-50 px-3 py-2 text-base font-medium text-red-800">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            <span><span class="font-bold">Perhatian:</span> {{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Password (nilai lama sengaja tidak dipertahankan) --}}
                <div>
                    <label for="password" class="mb-2 block text-lg font-semibold text-stone-900">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                            required
                            @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                            @class([
                                'block h-14 w-full rounded-lg border-2 bg-white pl-4 pr-14 text-lg text-stone-900 placeholder:text-stone-500 transition duration-200 ease-in-out focus:outline-none focus:ring-4',
                                'border-stone-500 focus:border-brand focus:ring-brand/25' => ! $errors->has('password'),
                                'border-red-700 focus:border-red-700 focus:ring-red-700/25' => $errors->has('password'),
                            ])
                        >

                        <button
                            type="button"
                            id="toggle-password"
                            aria-controls="password"
                            aria-pressed="false"
                            aria-label="Tampilkan password"
                            title="Tampilkan password"
                            class="absolute inset-y-0.5 right-0.5 flex w-12 items-center justify-center rounded-md text-stone-700 transition duration-200 ease-in-out hover:bg-stone-100 hover:text-stone-950 focus:outline-none focus-visible:ring-4 focus-visible:ring-inset focus-visible:ring-brand/40"
                        >
                            {{-- Ikon mata (password tersembunyi) --}}
                            <svg data-icon="show" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            {{-- Ikon mata dicoret (password terlihat) --}}
                            <svg data-icon="hide" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p id="password-error" class="mt-2 flex items-start gap-2 rounded-lg bg-red-50 px-3 py-2 text-base font-medium text-red-800">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            <span><span class="font-bold">Perhatian:</span> {{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Ingat saya + Lupa password --}}
                <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                    <label for="remember" class="inline-flex min-h-12 cursor-pointer items-center gap-3 text-lg text-stone-900">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            @checked(old('remember'))
                            class="h-7 w-7 shrink-0 cursor-pointer rounded border-2 border-stone-500 accent-brand focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-2 focus-visible:outline-brand"
                        >
                        <span>Ingat saya</span>
                    </label>

                    {{-- Hanya tampil bila route password.request tersedia (mis. Laravel Breeze/Fortify) --}}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="inline-flex min-h-12 items-center rounded text-lg font-medium text-link underline underline-offset-4 transition duration-200 ease-in-out hover:text-link-hover focus:outline-none focus-visible:ring-4 focus-visible:ring-brand/40">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Tombol masuk --}}
                <button
                    type="submit"
                    id="login-submit"
                    class="flex h-14 w-full items-center justify-center gap-3 rounded-lg bg-brand px-6 text-lg font-semibold text-white transition duration-200 ease-in-out hover:bg-brand-hover focus:outline-none focus-visible:ring-4 focus-visible:ring-brand/40 focus-visible:ring-offset-2 disabled:cursor-wait disabled:bg-brand-hover"
                >
                    <svg id="login-spinner" class="hidden h-5 w-5 animate-spin motion-reduce:animate-none"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"></path>
                    </svg>
                    <span id="login-submit-label">Masuk</span>
                </button>
            </form>
        </div>
    </section>
</main>

</body>
</html>
