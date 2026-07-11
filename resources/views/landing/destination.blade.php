@extends('layouts.app')

@section('title', 'Destinasi Wisata')

@section('content')

<!-- breadcrumb -->
<div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10 pt-24 lg:pt-32 pb-4">
    <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-secondary transition">Beranda</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <span class="font-medium text-secondary">Destinasi Wisata</span>
    </nav>
</div>

<!-- title page -->
<section class="pb-8 lg:pb-12">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 font-jakarta mb-4">Destinasi Wisata</h1>
        <p class="text-lg text-gray-600 max-w-3xl">Jelajahi berbagai destinasi wisata menakjubkan. Temukan tempat impian Anda dan pesan tiket dengan mudah.</p>
    </div>
</section>

<!-- toolbar -->
<section class="py-5 sticky top-16 lg:top-20 z-10 bg-white/95 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <form action="{{ route('destinations.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <div class="relative flex-1 w-full">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama destinasi..."
                        class="bg-surface w-full py-4 pl-4 pr-16 border border-gray-200 rounded-full shadow focus:outline-none">
                    <button type="submit" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-secondary">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5 text-secondary" />
                    </button>
                </div>
            </div>
            <div class="w-full sm:w-48">
                <select name="sort" onchange="this.form.submit()" class="bg-surface w-full py-4 pl-4 pr-10 border border-gray-200 rounded-full shadow-sm focus:outline-none cursor-pointer">
                    <option value="newest" {{ ($sort ?? 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="az" {{ ($sort ?? '') == 'az' ? 'selected' : '' }}>A-Z (Nama)</option>
                    <option value="za" {{ ($sort ?? '') == 'za' ? 'selected' : '' }}>Z-A (Nama)</option>
                </select>
            </div>
        </form>
    </div>
</section>

<!-- grid -->
<section class="py-12">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <div id="destinations-grid"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
            data-next-page="{{ $destinations->currentPage() + 1 }}"
            data-has-more="{{ $destinations->hasMorePages() ? 'true' : 'false' }}">

            @forelse ($destinations as $dest)
            @include('partials.destination-card', ['dest' => $dest])
            @empty
            <div class="col-span-full text-center text-gray-500 py-16">
                <x-heroicon-o-magnifying-glass class="w-12 h-12 mx-auto text-gray-300 mb-4" />
                <p class="text-lg">Tidak ada destinasi ditemukan.</p>
            </div>
            @endforelse
        </div>

        @if ($destinations->hasMorePages())
        <div id="load-more-sentinel" class="h-10"></div>
        @endif

        <div id="loading-indicator" class="hidden justify-center py-8">
            <svg class="animate-spin h-8 w-8 text-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</section>

<!-- infinite scroll script  -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('destinations-grid');
        const sentinel = document.getElementById('load-more-sentinel');
        const loadingIndicator = document.getElementById('loading-indicator');

        if (!grid || !sentinel) return;

        let nextPage = parseInt(grid.dataset.nextPage);
        let hasMore = grid.dataset.hasMore === 'true';
        let loading = false;

        const observer = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting && hasMore && !loading) {
                loadMore();
            }
        }, {
            threshold: 0.1
        });

        observer.observe(sentinel);

        function loadMore() {
            loading = true;
            loadingIndicator.classList.remove('hidden');
            loadingIndicator.classList.add('flex');

            const url = new URL(window.location.href);
            url.searchParams.set('page', nextPage);
            url.searchParams.set('ajax', '1');

            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        grid.insertAdjacentHTML('beforeend', data.html);
                    }

                    hasMore = data.hasMore;
                    nextPage = data.nextPage;

                    if (!hasMore) {
                        sentinel.remove();
                    }
                })
                .catch(error => {
                    console.error('Load more error:', error);
                })
                .finally(() => {
                    loading = false;
                    loadingIndicator.classList.add('hidden');
                    loadingIndicator.classList.remove('flex');
                });
        }
    });
</script>

@endsection