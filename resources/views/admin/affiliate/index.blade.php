@extends('layouts.admin')

@section('title', 'Afiliasi')
@section('page_title', 'Afiliasi')

@section('content')
<div class="space-y-6">

    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">Afiliasi</span>
        </nav>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- total affiliates -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-gray-200 p-1 text-center">
                    <x-heroicon-o-user-group class="w-4 h-4" />
                </div>
                <p class="text-sm text-gray-500 uppercase font-medium">Total Afiliasi</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalAffiliates }}</p>
        </div>
        <!-- total points -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-blue-200 p-1 text-center">
                    <x-heroicon-o-star class="w-4 h-4 text-blue-700" />
                </div>
                <p class="text-sm text-blue-600 uppercase font-medium">Total Poin</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalPoints, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('admin.affiliate.index') }}" class="flex flex-wrap gap-3 items-end">
            <!-- search -->
            <div class="flex items-center gap-3 border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                <x-heroicon-s-magnifying-glass class="w-5 h-5 text-gray-400" />
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari kode / nama / email..."
                    x-data="{}"
                    @input.debounce.500ms="$el.form.submit()"
                    class="outline-none bg-transparent">
            </div>

            <!-- sort -->
            <div>
                <select name="sort" onchange="this.form.submit()"
                    class="border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                    <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>

            <!-- limit -->
            <div>
                <select name="per_page" onchange="this.form.submit()"
                    class="border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="80" {{ $perPage == 80 ? 'selected' : '' }}>80</option>
                </select>
            </div>

            <input type="hidden" name="page" value="{{ request('page', 1) }}">
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-secondary/10">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Afiliator</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Total Poin</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Channel Promosi</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($affiliates as $affiliate)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        data-url="{{ route('admin.affiliate.show', $affiliate->id) }}">
                        <!-- code -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="font-bold text-secondary">{{ $affiliate->code }}</span>
                        </td>
                        <!-- afiliator -->
                        <td class="px-6 py-4 border-r border-gray-200 max-w-[200px] truncate">
                            <div class="font-medium text-gray-900">{{ $affiliate->full_name }}</div>
                            <div class="text-xs text-gray-600">{{ $affiliate->phone_number }}</div>
                            <div class="text-xs text-gray-600">{{ $affiliate->email }}</div>
                        </td>
                        <!-- total poin -->
                        <td class="px-6 py-4 border-r border-gray-200 ">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $affiliate->total_points }} poin
                            </span>
                        </td>
                        <!-- Channel Promosi -->
                        <td class="px-6 py-4 border-r border-gray-200 max-w-[200px] truncate">
                            @if ($affiliate->promotion_channels)
                            <div class="text-sm text-gray-800">{{ $affiliate->promotion_channels }}</div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <!-- Dibuat -->
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $affiliate->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            Tidak ada data afiliasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($affiliates->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $affiliates->links() }}
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