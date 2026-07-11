@extends('layouts.admin')

@section('title', $affiliate->code)
@section('page_title', $affiliate->code)

@section('content')
<div class="space-y-6">

    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{ url('/admin/affiliate') }}" class="hover:text-secondary transition">Afiliasi</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">{{$affiliate->code}}</span>
        </nav>
    </div>

    <!-- AFFILIATE INFORMATION -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta">Informasi Afiliasi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Kode</dt>
                <dd class="mt-1 font-bold text-secondary text-2xl font-jakarta">{{ $affiliate->code }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Total Poin</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $affiliate->total_points }} poin
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Tanggal Dibuat</dt>
                <dd class="mt-1 text-sm">{{ $affiliate->created_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Terakhir Update</dt>
                <dd class="mt-1 text-sm">{{ $affiliate->updated_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
        </div>
    </div>

    <!-- DETAIL CARDS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- personal info -->
        <div class="bg-surface rounded-xl shadow border p-5 border-l-4 border-blue-400">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta flex items-center gap-2">
                <x-heroicon-o-user class="w-5 h-5 text-blue-500" />
                Data Pribadi
            </h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-600 font-nunito">Nama</dt>
                <dd class="font-medium text-gray-800">{{ $affiliate->full_name }}</dd>
                <dt class="text-gray-600 font-nunito">Email</dt>
                <dd>{{ $affiliate->email }}</dd>
                <dt class="text-gray-600 font-nunito">Telepon</dt>
                <dd>{{ $affiliate->phone_number }}</dd>
            </dl>
        </div>

        <!-- channels & reason-->
        <div class="bg-surface rounded-xl shadow border p-5 border-l-4 border-green-400">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta flex items-center gap-2">
                <x-heroicon-o-megaphone class="w-5 h-5 text-green-500" />
                Promosi & Alasan
            </h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-600 font-nunito">Channel</dt>
                <dd>{{ implode(', ', explode(',', $affiliate->promotion_channels)) ?? '—' }}</dd>
                <dt class="text-gray-600 font-nunito">Alasan Bergabung</dt>
                <dd>{{ $affiliate->join_reason ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <!-- POINTS TABLE -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta">Riwayat Poin</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($affiliate->points as $point)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-secondary">
                            {{ $point->ticket->code ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-800">
                            +{{ $point->points }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $point->description ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $point->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada poin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection