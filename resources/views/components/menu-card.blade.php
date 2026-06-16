@props(['item'])

<div class="card overflow-hidden">
    <div class="relative">
        <img src="{{ $item->image_url }}"
             alt="{{ $item->name }}"
             class="w-full aspect-[4/3] object-cover"
             loading="lazy"
             onerror="this.src='https://placehold.co/400x300/coffee/lavender?text={{ urlencode($item->name) }}'">
        @if (!$item->is_available)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">Sold Out</span>
            </div>
        @endif
    </div>
    <div class="p-3">
        <h3 class="font-semibold text-sm text-coffee-800 leading-tight">{{ $item->name }}</h3>
        <p class="text-coffee-600 font-bold text-sm mt-1">{{ $item->formatted_price }}</p>
        @if ($item->is_available)
            <button
                {{ $attributes->merge(['class' => 'mt-2 w-full bg-coffee-700 hover:bg-coffee-800 text-white text-xs font-semibold py-2 rounded-lg transition active:scale-95']) }}>
                + Tambah
            </button>
        @else
            <button disabled class="mt-2 w-full bg-gray-200 text-gray-400 text-xs font-semibold py-2 rounded-lg cursor-not-allowed">
                Tidak Tersedia
            </button>
        @endif
    </div>
</div>
