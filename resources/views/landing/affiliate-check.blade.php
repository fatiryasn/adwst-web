@extends('layouts.app')

@section('title', 'Cek Poin Afiliasi')

@section('content')

<!-- BREADCRUMB -->
<!-- <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10 pt-24 lg:pt-32 pb-4">
    <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-secondary transition">Beranda</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <a href="{{ route('affiliates.index') }}" class="hover:text-secondary transition">Program Afiliasi</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <span class="font-medium text-secondary">Cek Poin</span>
    </nav>
</div> -->

<!-- TITLE -->
<section class="pb-8 lg:pb-12 bg-white pt-24 lg:pt-36">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 font-jakarta mb-4">Cek Poin Afiliasi</h1>
        <p class="text-lg text-gray-600 max-w-3xl">
            Masukkan kode afiliasi (atau link yang Anda bagikan) dan email yang terdaftar untuk melihat total poin Anda.
        </p>
    </div>
</section>

<!-- FORM & RESULT -->
<section class="py-12 bg-white">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-8">

        @if (!isset($verified) || !$verified)
        {{-- Check Form --}}
        <div class="bg-surface rounded-xl shadow border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 font-jakarta">Isi data berikut</h2>
            <form action="{{ route('affiliates.check.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="code_or_url" class="block text-sm font-medium text-gray-700 mb-1">
                        Kode Afiliasi atau Link <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code_or_url" id="code_or_url"
                        value="{{ old('code_or_url', $inputCode ?? '') }}"
                        placeholder="Masukkan kode atau tempel link afiliasi"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                        required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email Terdaftar <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $inputEmail ?? '') }}"
                        placeholder="email@contoh.com"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                        required>
                </div>

                {{-- Error messages placed above the button --}}
                @if ($errors->any())
                <div class="rounded-md bg-red-50 border border-red-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-exclamation-circle class="h-5 w-5 text-red-400" />
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="bg-secondary hover:bg-secondary/80 text-white font-semibold px-6 py-3 rounded-lg transition">
                        Cek Poin
                    </button>
                </div>
            </form>
        </div>
        @else
        {{-- Result Card --}}
        <div class="bg-surface rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-10 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-600 mb-6">
                <x-heroicon-o-check-circle class="w-10 h-10" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900 font-jakarta mb-2">Data Ditemukan</h2>
            <p class="text-gray-600 mb-8">Berikut informasi akun afiliasi Anda.</p>

            <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left max-w-md mx-auto">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kode Afiliasi</span>
                        <span class="font-mono font-bold text-secondary">{{ $affiliate->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama</span>
                        <span class="font-medium text-gray-800">{{ $censoredName }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon</span>
                        <span class="font-medium text-gray-800">{{ $censoredPhone }}</span>
                    </div>
                    <div class="border-t pt-3 mt-3 flex justify-between items-center">
                        <span class="text-gray-500 font-semibold">Total Poin</span>
                        <span class="text-2xl font-bold text-secondary">{{ number_format($affiliate->total_points, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('affiliates.check') }}" class="inline-flex items-center text-secondary font-medium hover:underline">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
                Cek lagi
            </a>
        </div>
        @endif

    </div>
</section>

{{-- SweetAlert (if any flash) --}}
@include('partials.sweetalert')

@endsection