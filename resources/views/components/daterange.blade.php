@props([
    'nameStart' => 'tanggal_mulai',
    'nameEnd' => 'tanggal_selesai',
    'start' => null,
    'end' => null,
    'placeholder' => 'Pilih tanggal',
])

<div {{ $attributes->merge(['class' => 'relative w-full sm:w-64 js-daterange']) }}>
    <button type="button" data-role="btn"
            class="flex w-full items-center gap-2 rounded-lg border border-stone-300 bg-white px-3 py-2 text-left text-stone-700 transition hover:bg-stone-50 focus:outline-none focus:ring-1 focus:ring-brand">
        <svg class="h-4 w-4 shrink-0 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
        </svg>
        <span data-role="label" class="truncate text-stone-500">{{ $placeholder }}</span>
    </button>

    <input type="hidden" name="{{ $nameStart }}" data-role="input-start" value="{{ $start }}">
    <input type="hidden" name="{{ $nameEnd }}" data-role="input-end" value="{{ $end }}">

    <div data-role="panel" hidden
         class="fixed z-40 mt-2 w-[300px] rounded-xl border border-stone-300 bg-white p-4 text-stone-900 shadow-lg">
        <div class="flex items-center gap-2 text-sm">
            <input type="text" data-role="display-start" readonly placeholder="Mulai"
                   class="w-1/2 rounded-md border border-stone-300 bg-stone-50 px-2 py-1.5 text-center text-stone-700 focus:outline-none">
            <span class="text-stone-400">–</span>
            <input type="text" data-role="display-end" readonly placeholder="Selesai"
                   class="w-1/2 rounded-md border border-stone-300 bg-stone-50 px-2 py-1.5 text-center text-stone-700 focus:outline-none">
        </div>

        <div class="mt-4 flex items-center justify-between">
            <button type="button" data-role="prev" aria-label="Bulan sebelumnya"
                    class="rounded-md p-1 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6" /></svg>
            </button>
            <span data-role="month-label" class="text-sm font-medium text-stone-900"></span>
            <button type="button" data-role="next" aria-label="Bulan berikutnya"
                    class="rounded-md p-1 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
            </button>
        </div>

        <div class="mt-2 grid grid-cols-7 gap-y-1 text-center text-[11px] font-medium text-stone-400">
            <span>M</span><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span>
        </div>
        <div data-role="days" class="grid grid-cols-7 gap-y-1 text-center text-sm"></div>

        <div class="mt-4 flex items-center justify-end gap-2 border-t border-stone-200 pt-3">
            <button type="button" data-role="cancel"
                    class="rounded-md border border-stone-300 px-3 py-1.5 text-sm text-stone-700 transition hover:bg-stone-50">
                Batal
            </button>
            <button type="button" data-role="apply"
                    class="rounded-md bg-brand px-3 py-1.5 text-sm font-medium text-white transition hover:bg-brand-hover">
                Terapkan
            </button>
        </div>
    </div>
</div>

@once
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
@endonce
