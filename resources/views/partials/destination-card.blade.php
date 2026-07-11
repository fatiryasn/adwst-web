<a href="{{ route('destinations.show', $dest->slug) }}"
    class="group flex flex-col bg-surface rounded-xl shadow-md hover:shadow-xl transition overflow-hidden">
    <!-- image -->
    <div class="h-56 sm:h-64 overflow-hidden">
        @if ($dest->thumbnail)
        <img src="{{ asset('storage/' . $dest->thumbnail) }}"
            alt="{{ $dest->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
            <x-heroicon-o-photo class="w-10 h-10" />
        </div>
        @endif
    </div>

    <!-- content -->
    <div class="p-5 flex flex-col flex-1">
        <h3 class="font-semibold text-lg text-gray-900 group-hover:text-secondary transition line-clamp-1">{{ $dest->name }}</h3>
        <p class="text-sm text-gray-500 mt-1 line-clamp-2 flex-1">{{ Str::limit($dest->description, 80) }}</p>
        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-o-ticket class="w-5 h-5 text-secondary" />
                <span class="text-secondary font-bold text-lg">{{ 'Rp ' . number_format($dest->ticket_price, 0, ',', '.') }}</span>
            </div>
            <span class="text-xs text-gray-400 flex items-center gap-1">
                <x-heroicon-o-arrow-right class="w-3.5 h-3.5" />
            </span>
        </div>
    </div>
</a>