@props(['paginator'])

@if ($paginator->hasPages())
    <div {{ $attributes->merge(['class' => 'flex flex-col gap-3 border-t border-stone-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between']) }}>
        <p class="text-stone-500">
            Menampilkan
            <span class="font-medium text-stone-900">{{ $paginator->firstItem() }}</span>–<span class="font-medium text-stone-900">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-medium text-stone-900">{{ $paginator->total() }}</span>
            data
        </p>

        {{ $paginator->withQueryString()->links() }}
    </div>
@endif
