@extends('layouts.app')

@section('title', 'Program Afiliasi')

@section('content')

<!-- BREADCRUMB -->
<!-- <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10 pt-24 lg:pt-32 pb-4">
    <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-secondary transition">Beranda</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <span class="font-medium text-secondary">Program Afiliasi</span>
    </nav>
</div> -->

<!-- TITLE -->
<section class="pb-8 lg:pb-12 bg-white pt-24 lg:pt-36">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 font-jakarta mb-4">Program Afiliasi</h1>
        <p class="text-lg text-gray-600 max-w-3xl">
            Dapatkan poin setiap kali pelanggan yang Anda referensikan berhasil membeli tiket.
            Daftar sekarang dan mulai kumpulkan poin untuk keuntungan lebih!
        </p>
    </div>
</section>

<!-- FORM -->
<section class="py-12 bg-white">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 bg-surface rounded-2xl shadow-lg overflow-hidden border border-gray-100">

            <!-- left -->
            <div class="hidden lg:block relative bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/20"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <h3 class="text-2xl font-bold font-jakarta mb-2">Bergabung & Dapatkan Poin</h3>
                    <p class="text-sm text-gray-200">Referensikan teman, dapatkan poin setiap pembelian tiket.</p>
                </div>
            </div>

            <!-- right -->
            <div x-data="{ submitting: false }" class="p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 font-jakarta">Daftar Menjadi Afiliasi</h2>

                <form action="{{ route('affiliates.store') }}" method="POST" class="space-y-6"
                    x-on:submit="submitting = true" autocomplete="off">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name"
                            value="{{ old('full_name') }}"
                            placeholder="Nama Anda"
                            class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                            required>
                        @error('full_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                            required>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="phone_number" id="phone_number"
                            value="{{ old('phone_number') }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                            required>
                        @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Media Promosi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Media Promosi yang Digunakan
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                            $channels = ['Instagram', 'TikTok', 'Facebook', 'YouTube', 'WhatsApp', 'Website', 'Lainnya'];
                            $oldChannels = old('promotion_channels', []);
                            @endphp
                            @foreach ($channels as $channel)
                            <label class="flex items-center space-x-2 text-gray-700 cursor-pointer">
                                <input type="checkbox" name="promotion_channels[]" value="{{ $channel }}"
                                    class="rounded border-gray-300 text-secondary focus:ring-secondary"
                                    {{ in_array($channel, $oldChannels) ? 'checked' : '' }}>
                                <span class="text-sm">{{ $channel }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('promotion_channels')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan Bergabung -->
                    <div>
                        <label for="join_reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Alasan Bergabung
                        </label>
                        <textarea name="join_reason" id="join_reason" rows="3"
                            placeholder="Ceritakan mengapa Anda ingin menjadi afiliasi..."
                            class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50">{{ old('join_reason') }}</textarea>
                        @error('join_reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            :disabled="submitting"
                            class="bg-secondary hover:bg-secondary/80 text-white font-semibold px-8 py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">Daftar Sekarang</span>
                            <span x-show="submitting" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mendaftarkan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Cek Poin Afiliasi CTA -->
<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Sudah menjadi afiliasi?</h3>
        <p class="text-gray-600 mb-4">Cek jumlah poin dan performa link afiliasi Anda.</p>
        <a href="{{ route('affiliates.check') }}"
            class="inline-flex items-center bg-white border border-secondary text-secondary font-semibold px-6 py-3 rounded-full shadow hover:bg-secondary hover:text-white transition">
            Cek Poin Afiliasi
            <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
        </a>
    </div>
</section>

{{-- SweetAlert for success/error messages --}}
@include('partials.sweetalert')

@endsection