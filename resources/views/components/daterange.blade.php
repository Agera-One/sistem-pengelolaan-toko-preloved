@props([
    'nameStart' => 'tanggal_mulai',
    'nameEnd' => 'tanggal_selesai',
    'start' => null,
    'end' => null,
    'labelStart' => 'Dari',
    'labelEnd' => 'Sampai',
    'placeholder' => null,
    'submitOnChange' => true,
])

@php
    $uid = 'dr-' . \Illuminate\Support\Str::random(6);
    $kolom = [
        ['id' => $uid . '-start', 'name' => $nameStart, 'value' => $start, 'label' => $labelStart, 'role' => 'start', 'min' => null, 'max' => $end],
        ['id' => $uid . '-end', 'name' => $nameEnd, 'value' => $end, 'label' => $labelEnd, 'role' => 'end', 'min' => $start, 'max' => null],
    ];
@endphp

<div {{ $attributes->merge(['class' => 'grid grid-cols-2 gap-3']) }} data-daterange @if ($submitOnChange) data-daterange-auto @endif>
    @foreach ($kolom as $k)
        <div>
            <label for="{{ $k['id'] }}" class="mb-1 block text-sm text-stone-500">{{ $k['label'] }}</label>
            <div class="relative overflow-hidden rounded-lg border border-stone-300 bg-white focus-within:border-brand focus-within:ring-1 focus-within:ring-brand">
                <input type="date" id="{{ $k['id'] }}" name="{{ $k['name'] }}" value="{{ $k['value'] }}"
                       data-date-{{ $k['role'] }} data-date-picker
                       @if ($k['min']) min="{{ $k['min'] }}" @endif
                       @if ($k['max']) max="{{ $k['max'] }}" @endif
                       class="block w-[calc(100%+2.5rem)] cursor-pointer border-0 bg-transparent py-2 pl-3 pr-0 text-sm text-stone-900 focus:outline-none focus:ring-0">
                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <path d="M16 2v4M8 2v4M3 10h18" />
                </svg>
            </div>
        </div>
    @endforeach
</div>
