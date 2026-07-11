@extends('layouts.app')

@section('title', $destination->name)

@section('content')

<div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10 pt-24 lg:pt-32 pb-4">
    <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-secondary transition">Beranda</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <a href="{{ route('destinations.index') }}" class="hover:text-secondary transition">Destinasi Wisata</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <span class="font-medium text-secondary">{{ $destination->name }}</span>
    </nav>
</div>

<section class="py-8 lg:py-12">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="rounded-2xl overflow-hidden shadow-lg">
                @if ($destination->thumbnail)
                <img src="{{ asset('storage/' . $destination->thumbnail) }}"
                    alt="{{ $destination->name }}"
                    class="w-full h-80 sm:h-96 lg:h-full object-cover">
                @else
                <div class="w-full h-80 sm:h-96 lg:h-full bg-gray-200 flex items-center justify-center text-gray-400">
                    <x-heroicon-o-photo class="w-16 h-16" />
                </div>
                @endif
            </div>

            <div class="flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 font-jakarta mb-4">
                    {{ $destination->name }}
                </h1>
                @if ($destination->address)
                <div class="flex items-start gap-2 text-gray-600 mb-6">
                    <x-heroicon-o-map-pin class="w-5 h-5 mt-0.5 flex-shrink-0 text-secondary" />
                    <span>{{ $destination->address }}</span>
                </div>
                @endif
                <div class="mt-2">
                    <a href="{{ route('destinations.tickets.create', $destination->slug) }}"
                        class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-8 py-3 rounded-full shadow-lg transition text-lg">
                        Beli Tiket
                        <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($destination->description)
<section class="py-12">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 font-jakarta mb-4">Deskripsi</h2>
        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($destination->description)) !!}
        </div>
    </div>
</section>
@endif

@if ($destination->latitude && $destination->longitude)
<section class="py-12">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 font-jakarta mb-4">Lokasi</h2>

        <div id="map" class="h-[35rem] rounded-xl shadow-md z-0"
            data-lat="{{ $destination->latitude }}"
            data-lng="{{ $destination->longitude }}"
            data-name="{{ $destination->name }}"
            data-address="{{ $destination->address ?? '' }}">
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapElement = document.getElementById('map');
        if (!mapElement) return;

        // Ambil data koordinat dari elemen HTML
        const lat = parseFloat(mapElement.dataset.lat);
        const lng = parseFloat(mapElement.dataset.lng);
        const name = mapElement.dataset.name;
        const address = mapElement.dataset.address;

        // Inisialisasi Peta Leaflet
        const map = L.map('map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan Marker
        L.marker([lat, lng]).addTo(map)
            .bindPopup(`<strong>${name}</strong><br>${address}`)
            .openPopup();

        // Fix Leaflet glitch saat dirender di dalam container dinamis
        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    });
</script>
@endif

@endsection