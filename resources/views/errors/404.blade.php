<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Halaman Tidak Ditemukan - {{ config('app.name') }}</title>
    @stack('styles')
</head>

<body class="antialiased font-poppins min-h-screen flex items-center justify-center">
    <div class="py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <!-- card -->
        <div class="p-6 sm:p-10 text-center">

            <!-- icon -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 text-red-600 mb-6">
                <x-heroicon-o-exclamation-triangle class="w-10 h-10" />
            </div>

            <h1 class="text-3xl font-bold text-gray-900 font-jakarta mb-2">404 – Halaman Tidak Ditemukan</h1>
            <p class="text-gray-600 mb-8 text-sm md:text-base">
                Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.
            </p>

            <!-- action buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                <button onclick="history.back()"
                    class="inline-flex items-center justify-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-6 py-3 rounded-lg transition font-poppins">
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                    Kembali ke Halaman Sebelumnya
                </button>
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-lg transition font-poppins">
                    <x-heroicon-o-home class="w-5 h-5 mr-2" />
                    Ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>