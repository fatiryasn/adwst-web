@extends('layouts.admin')

@section('title', 'Tiket')
@section('page_title', 'Tiket')

@section('content')
<div class="space-y-6">

    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">Tiket</span>
        </nav>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- total -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-gray-200 p-1 text-center">
                    <x-heroicon-o-ticket class="w-4 h-4" />
                </div>
                <p class="text-sm text-gray-500 uppercase font-medium">Total Tiket</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $total }}</p>
        </div>
        <!-- active -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-green-200 p-1 text-center">
                    <x-heroicon-o-ticket class="w-4 h-4 text-green-700" />
                </div>
                <p class="text-sm text-green-600 uppercase font-medium">Aktif</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $active }}</p>
        </div>
        <!-- inactive -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-red-200 p-1 text-center">
                    <x-heroicon-o-ticket class="w-4 h-4 text-red-700" />
                </div>
                <p class="text-sm text-red-600 uppercase font-medium">Inaktif</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $inactive }}</p>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('admin.ticket.index') }}" class="flex flex-wrap gap-3 items-end">
            <!-- search -->
            <div class="flex items-center gap-3 border border-gray-200 bg-surface rounded-md shadow px-5 py-2 focus:outline-none">
                <x-heroicon-s-magnifying-glass class="w-5 h-5 text-gray-400" />
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari kode / nama / no.telp"
                    x-data="{}"
                    @input.debounce.500ms="$el.form.submit()"
                    autocomplete="off"
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider" style="max-width: 200px;">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider" style="max-width: 200px;">Destinasi</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Tgl Kunjungan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider" style="max-width: 150px;">Referral</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-secondary uppercase tracking-wider">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                        <!-- code -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="font-bold text-secondary">{{ $ticket->code }}</span>
                        </td>
                        <!-- customer -->
                        <td class="px-6 py-4 border-r border-gray-200 max-w-[200px] truncate" title="{{ $ticket->customer_name }} - {{ $ticket->customer_phone }}">
                            <div class="font-medium text-gray-900 truncate">{{ $ticket->customer_name }}</div>
                            <div class="text-xs text-gray-600 truncate">{{ $ticket->customer_phone }}</div>
                            @if ($ticket->customer_email)
                            <div class="text-xs text-gray-600 truncate">{{ $ticket->customer_email }}</div>
                            @endif
                        </td>
                        <!-- destination -->
                        <td class="px-6 py-4 border-r border-gray-200 max-w-[200px] truncate" title="{{ $ticket->destination->name ?? '' }}">
                            <div class="font-medium text-gray-900 truncate">{{ $ticket->destination->name ?? '—' }}</div>
                            <div class="text-xs text-gray-600"> @if($ticket->ticket_price > 0)
                                Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}
                                @else
                                —
                                @endif</div>
                        </td>
                        <!-- visit and departure -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            @if ($ticket->visit_date)
                            <div class="text-sm text-gray-800">{{ $ticket->visit_date->format('d M Y') }}</div>
                            @endif
                            @if ($ticket->departure_date)
                            <div class="text-xs text-gray-600">s/d {{ $ticket->departure_date->format('d M Y') }}</div>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <!-- referral -->
                        <td class="px-6 py-4 border-r border-gray-200 max-w-[200px] truncate">
                            @if ($ticket->affiliate)
                            <span class="inline-flex items-center rounded text-sm font-medium text-secondary truncate">
                                <x-heroicon-o-share class="w-3 h-3 text-orange-500 mr-1" />
                                {{ $ticket->affiliate->code }}
                            </span>
                            @endif
                            @if ($ticket->referral_source)
                            <div class="text-xs text-gray-600 mt-1 truncate"> {{ implode(', ', explode(',', $ticket->referral_source)) }}</div>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <!-- payment & ticket status -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex flex-col gap-1 uppercase">
                                {{-- Payment Status --}}
                                @if ($ticket->payment_status == 'pending')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <x-heroicon-s-banknotes class="w-4 h-4 mr-1" />
                                    pending
                                </span>
                                @elseif ($ticket->payment_status == 'paid')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <x-heroicon-s-banknotes class="w-4 h-4 mr-1" />
                                    paid
                                </span>
                                @elseif ($ticket->payment_status == 'failed')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <x-heroicon-s-banknotes class="w-4 h-4 mr-1" />
                                    failed
                                </span>
                                @elseif ($ticket->payment_status == 'refunded')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <x-heroicon-s-banknotes class="w-4 h-4 mr-1" />
                                    refunded
                                </span>
                                @endif

                                {{-- Ticket Status --}}
                                @if ($ticket->ticket_status == 'active')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <x-heroicon-s-ticket class="w-4 h-4 mr-1" />
                                    active
                                </span>
                                @elseif ($ticket->ticket_status == 'checked_in')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <x-heroicon-s-ticket class="w-4 h-4 mr-1" />
                                    checked-in
                                </span>
                                @elseif ($ticket->ticket_status == 'expired')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <x-heroicon-s-ticket class="w-4 h-4 mr-1" />
                                    expired
                                </span>
                                @elseif ($ticket->ticket_status == 'cancelled')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <x-heroicon-s-ticket class="w-4 h-4 mr-1" />
                                    cancelled
                                </span>
                                @endif
                            </div>
                        </td>
                        <!-- created at -->
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $ticket->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            Tidak ada data tiket.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- pagination -->
        @if ($tickets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tickets->links() }}
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