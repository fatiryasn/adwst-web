@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Message -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
        <h2 class="text-xl font-bold text-gray-800 font-jakarta">
            Selamat datang, {{ Auth::user()->full_name ?? 'Admin' }}!
        </h2>
        <p class="text-sm text-gray-500 mt-1">Ringkasan data platform Anda.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Destinations -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-gray-200 p-1 text-center">
                    <x-heroicon-o-map-pin class="w-4 h-4" />
                </div>
                <p class="text-sm text-gray-500 uppercase font-medium">Total Destinasi</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalDestinations }}</p>
        </div>

        <!-- Total Tickets -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-blue-200 p-1 text-center">
                    <x-heroicon-o-ticket class="w-4 h-4 text-blue-700" />
                </div>
                <p class="text-sm text-blue-600 uppercase font-medium">Total Tiket</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalTickets }}</p>
        </div>

        <!-- Total Affiliates -->
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-5">
            <div class="flex items-center gap-2">
                <div class="rounded bg-green-200 p-1 text-center">
                    <x-heroicon-o-user-group class="w-4 h-4 text-green-700" />
                </div>
                <p class="text-sm text-green-600 uppercase font-medium">Total Afiliasi</p>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAffiliates }}</p>
        </div>
    </div>

</div>
@endsection