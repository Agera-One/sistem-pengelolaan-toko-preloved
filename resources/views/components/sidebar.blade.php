@props(['storeName' => 'TRISTANTI STORE'])

@php
    $menus = [
        [
            'label'  => 'Dashboard',
            'route'  => 'dashboard',
            'active' => 'dashboard',
            'icon'   => 'm2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
        ],
        [
            'label'  => 'Barang',
            'route'  => 'barang.index',
            'active' => 'barang.*',
            'icon'   => 'm20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
        ],
        [
            'label'  => 'Pelanggan',
            'route'  => 'pelanggan.index',
            'active' => 'pelanggan.*',
            'icon'   => 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z',
        ],
        [
            'label'  => 'Supplier',
            'route'  => 'supplier.index',
            'active' => 'supplier.*',
            'icon'   => 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
        ],
        [
            'label'  => 'Penjualan',
            'route'  => 'penjualan.index',
            'active' => 'penjualan.*',
            'icon'   => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z',
        ],
        [
            'label'  => 'Pembelian',
            'route'  => 'pembelian.index',
            'active' => 'pembelian.*',
            'icon'   => 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
        ],
        [
            'label'  => 'Laporan',
            'route'  => 'laporan.index',
            'active' => 'laporan.*',
            'icon'   => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z',
        ],
        [
            'label'  => 'Pengaturan',
            'route'  => 'pengaturan.index',
            'active' => 'pengaturan.*',
            'icon'   => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
        ],
    ];
@endphp

<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-stone-900/50 lg:hidden" aria-hidden="true"></div>

<aside id="sidebar"
       aria-label="Menu samping"
       class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] -translate-x-full flex-col border-r border-stone-200 bg-stone-50 transition-transform duration-200 ease-in-out motion-reduce:transition-none lg:translate-x-0">

    <div class="flex items-start justify-between gap-2 border-b border-stone-200 px-5 py-5">
        <div class="min-w-0">
            {{-- <img src="{{ asset('images/logo.png') }}" alt="{{ $storeName }}" class="h-10 w-auto"> --}}
            <p class="flex items-center gap-2 font-serif text-xl font-bold text-stone-900">
                <svg class="h-6 w-6 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2 22 12 12 22 2 12Z"/>
                    <path d="M12 8 16 12 12 16 8 12Z" fill="currentColor"/>
                </svg>
                <span class="truncate">{{ $storeName }}</span>
            </p>
        </div>

        <button type="button" id="sidebar-close" aria-label="Tutup menu"
                class="-mr-2 -mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-lg text-stone-700 transition duration-200 ease-in-out hover:bg-stone-200/60 focus:outline-none focus-visible:ring-4 focus-visible:ring-brand/40 lg:hidden">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav aria-label="Menu utama" class="flex-1 overflow-y-auto px-4 py-5">
        <ul class="space-y-1">
            @foreach ($menus as $menu)
                @php
                    $isActive = request()->routeIs($menu['active']);
                    $href = Route::has($menu['route']) ? route($menu['route']) : '#';
                @endphp
                <li>
                    <a href="{{ $href }}"
                       @if ($isActive) aria-current="page" @endif
                       @class([
                           'flex min-h-12 items-center gap-3 rounded-lg px-3 py-2 text-lg transition duration-200 ease-in-out focus:outline-none focus-visible:ring-4 focus-visible:ring-brand/40',
                           'bg-brand/10 font-semibold text-brand-hover' => $isActive,
                           'font-medium text-stone-700 hover:bg-stone-200/60 hover:text-stone-900' => ! $isActive,
                       ])>
                        <svg @class(['h-6 w-6 shrink-0', 'text-brand' => $isActive, 'text-stone-600' => ! $isActive])
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}"/>
                        </svg>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="border-t border-stone-200 p-4">
        <div class="flex items-center gap-3 px-2 pb-4">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand text-lg font-semibold text-white"
                  aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
            <div class="min-w-0">
                <p class="truncate text-lg font-semibold text-stone-900">{{ $user->name }}</p>
                @if ($user?->email)
                    <p class="truncate text-base text-stone-600">{{ $user->email }}</p>
                @endif
            </div>
        </div>

        @if (Route::has('logout'))
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex min-h-12 w-full items-center gap-3 rounded-lg border-2 border-stone-300 bg-white px-3 text-lg font-semibold text-stone-800 transition duration-200 ease-in-out hover:bg-stone-100 focus:outline-none focus-visible:ring-4 focus-visible:ring-brand/40">
                    <svg class="h-6 w-6 shrink-0 text-stone-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        @endif
    </div>
</aside>
