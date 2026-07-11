@extends('layouts.admin')

@section('title', 'Destinasi Wisata')
@section('page_title', 'Destinasi Wisata')

@section('content')
<div class="space-y-6">

    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">Destinasi Wisata</span>
        </nav>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- total -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-gray-200 p-1 text-center">
                    <x-heroicon-o-map-pin class="w-4 h-4" />
                </div>
                <p class="text-sm text-gray-500 uppercase font-medium">Total Destinasi</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $total }}</p>
        </div>
        <!-- active -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-green-200 p-1 text-center">
                    <x-heroicon-o-map-pin class="w-4 h-4 text-green-700" />
                </div>
                <p class="text-sm text-green-600 uppercase font-medium">Aktif</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $active }}</p>
        </div>
        <!-- inactive -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-red-200 p-1 text-center">
                    <x-heroicon-o-map-pin class="w-4 h-4 text-red-700" />
                </div>
                <p class="text-sm text-red-600 uppercase font-medium">Inaktif</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $inactive }}</p>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-10">
        <form method="GET" action="{{ route('admin.destination.index') }}" class="flex flex-wrap gap-3 items-end">
            <!-- search -->
            <div class="flex items-center gap-3border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                <x-heroicon-s-magnifying-glass class="w-5 h-5 mr-2" />
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Nama destinasi..."
                    x-data="{}"
                    x-init="() => {}"
                    @input.debounce.500ms="$el.form.submit()"
                    class="outline-none">
            </div>

            <!-- sort -->
            <div>
                <select name="sort" onchange="this.form.submit()"
                    class="border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                    >
                    <option value="newest" {{ $sort == 'newest'  ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ $sort == 'oldest'  ? 'selected' : '' }}>Terlama</option>
                    <option value="az" {{ $sort == 'az'      ? 'selected' : '' }}>A-Z (Nama)</option>
                    <option value="za" {{ $sort == 'za'      ? 'selected' : '' }}>Z-A (Nama)</option>
                </select>
            </div>

            <!-- per page -->
            <div>
                <select name="per_page" onchange="this.form.submit()"
                    class="border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                    >
                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="80" {{ $perPage == 80 ? 'selected' : '' }}>80</option>
                </select>
            </div>

            <input type="hidden" name="page" value="{{ request('page', 1) }}">
        </form>

        <!-- tambah  -->
        <a href="{{ route('admin.destination.new') }}"
            class="inline-flex items-center px-5 py-2 bg-secondary hover:bg-secondary/80 text-white shadow font-medium rounded-md transition">
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Tambah
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-secondary/10">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Thumbnail</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Harga Tiket</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($destinations as $dest)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        data-url="{{ route('admin.destination.show', $dest->id) }}">
                        <!-- name -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="font-medium text-gray-900">{{ $dest->name }}</div>
                            <div class="text-xs text-gray-500">{{ $dest->slug }}</div>
                        </td>
                        <!-- thumbnail -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            @if ($dest->thumbnail)
                            <img src="{{ asset('storage/' . $dest->thumbnail) }}" alt="{{ $dest->name }}" class="h-16 w-24 object-cover rounded"> @else
                            <div class="h-16 w-24 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">No img</div>
                            @endif
                        </td>
                        <!-- ticket price -->
                        <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">
                            {{ 'Rp ' . number_format($dest->ticket_price, 0, ',', '.') }}
                        </td>
                        <!-- status -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            @if ($dest->status == 'active')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inaktif</span>
                            @endif
                        </td>
                        <!-- created at -->
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $dest->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            Tidak ada data destinasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($destinations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $destinations->links() }}
        </div>
        @endif
    </div>


</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('tr[data-url]').forEach(row => {
        row.addEventListener('click', function() {
            window.location = this.dataset.url;
        });
    });
</script>
@endpush