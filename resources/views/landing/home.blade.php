@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<!-- HERO SECTION -->
<main class="relative min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
    style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');">
    <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-transparent"></div>

    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 leading-tight font-jakarta">
            Jelajahi Keindahan <span class="text-secondary">Wisata Lokal</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-100 mb-8">
            Temukan destinasi wisata menakjubkan, pesan tiket dengan mudah, dan nikmati liburan tak terlupakan.
        </p>

        <!-- search -->
        <form action="{{ route('destinations.index') }}" method="GET" class="flex justify-center">
            <div class="relative w-full max-w-xl">
                <input type="text" name="search" placeholder="Cari destinasi wisata..."
                    class="w-full py-4 pl-8 pr-20 rounded-full border-4 border-secondary bg-white backdrop-blur-sm text-gray-800 shadow-lg focus:outline-none focus:ring-4 focus:ring-secondary/50 text-lg"
                    value="{{ request('search') }}">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-secondary hover:bg-secondary/80 text-white p-3 rounded-full transition">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                </button>
            </div>
        </form>
    </div>
</main>

<!-- DESTINASI POPULER SECTION -->
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="mb-12 flex justify-between items-end">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 font-jakarta">Temukan Wisata Lokal</h2>
                <p class="mt-3 text-gray-600 text-lg">Beberapa tempat wisata terbaik yang bisa kamu kunjungi</p>
            </div>
            <div>
                <a href="{{ route('destinations.index') }}" class="inline-flex items-center text-secondary font-medium hover:underline">
                    Lihat Semua Destinasi <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($destinations as $dest)
            <a href="{{ route('destinations.show', $dest->slug) }}"
                class="group relative h-[35rem] rounded-xl overflow-hidden shadow-md hover:shadow-xl transition">
                @if ($dest->thumbnail)
                <img src="{{ asset('storage/' . $dest->thumbnail) }}"
                    alt="{{ $dest->name }}"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400">
                    <x-heroicon-o-photo class="w-10 h-10" />
                </div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                <div class="absolute bottom-0 left-0 p-4 text-white">
                    <h3 class="font-semibold text-lg leading-tight font-jakarta">{{ $dest->name }}</h3>
                    <p class="text-sm text-gray-200 mt-1 line-clamp-2">{{ Str::limit($dest->description, 60) }}</p>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center text-gray-500">Belum ada destinasi tersedia.</div>
            @endforelse
        </div>


    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- left -->
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-jakarta">
                    Tiket Online
                </h2>
                <p class="text-gray-600 text-lg mb-8">
                    Hanya 4 langkah mudah untuk memesan tiket wisata impian Anda. Tanpa antri, tanpa ribet.
                </p>
                <a href="{{ route('destinations.index') }}"
                    class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-8 py-3 rounded-full shadow transition">
                    Pilih Destinasi Anda
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>

            <!-- right -->
            <div class="relative">
                <div class="absolute left-4 top-4 -translate-x-1/2 h-[calc(100%-2rem)] w-0.5 bg-gray-300 z-0"></div>

                <div class="space-y-20">
                    <div class="relative flex items-start gap-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-sm font-bold shadow z-10">
                            1
                        </div>
                        <div class="pt-0.5">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 font-jakarta">Pilih Destinasi</h3>
                            <p class="text-gray-600 text-lg mt-2">Telusuri katalog destinasi dan pilih yang paling Anda inginkan.</p>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-sm font-bold shadow z-10">
                            2
                        </div>
                        <div class="pt-0.5">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 font-jakarta">Isi Data Diri</h3>
                            <p class="text-gray-600 text-lg mt-2">Lengkapi formulir dengan data yang dibutuhkan.</p>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-sm font-bold shadow z-10">
                            3
                        </div>
                        <div class="pt-0.5">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 font-jakarta">Bayar via WhatsApp</h3>
                            <p class="text-gray-600 text-lg mt-2">Konfirmasi pesanan dan lakukan pembayaran langsung lewat chat.</p>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-sm font-bold shadow z-10">
                            4
                        </div>
                        <div class="pt-0.5">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 font-jakarta">Terima Tiket Anda</h3>
                            <p class="text-gray-600 text-lg mt-2">Tiket anda valid dan dapat digunakan!</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- WHY CHOOSE US SECTION -->
<section class="py-16 lg:py-24 bg-white relative overflow-hidden">
    <!-- subtle background pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/50 to-blue-50/50 -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 font-jakarta">Kenapa <span class="font-bold text-secondary font-poppins">
                    Admin<span class="font-nunito">Wisata</span>
                </span></h2>
            <p class="mt-3 text-gray-600 text-lg">Kami memberikan pengalaman wisata terbaik dengan berbagai keunggulan</p>
        </div>

        <!-- cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group relative bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl p-8 transition-all duration-300">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-secondary text-white flex items-center justify-center shadow-lg transition-transform">
                    <x-heroicon-o-shield-check class="w-8 h-8" />
                </div>
                <div class="mt-8 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Terpercaya</h3>
                    <p class="text-gray-600 leading-relaxed">Ratusan wisatawan telah menggunakan layanan kami dengan aman dan nyaman. Keamanan data dan transaksi Anda adalah prioritas utama.</p>
                </div>
            </div>

            <div class="group relative bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-xl p-8 transition-all duration-300">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-secondary text-white flex items-center justify-center shadow-lg transition-transform">
                    <x-heroicon-o-banknotes class="w-8 h-8" />
                </div>
                <div class="mt-8 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Terjangkau</h3>
                    <p class="text-gray-600 leading-relaxed">Dapatkan tiket masuk ke destinasi favorit dengan harga bersahabat. Berbagai pilihan paket wisata yang sesuai dengan budget Anda.</p>
                </div>
            </div>

            <div class="group relative bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-xl p-8 transition-all duration-300">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-secondary text-white flex items-center justify-center shadow-lg transition-transform">
                    <x-heroicon-o-user-group class="w-8 h-8" />
                </div>
                <div class="mt-8 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Dukungan 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">Tim kami siap membantu kapan pun Anda membutuhkan informasi atau bantuan. Kepuasan Anda adalah motivasi kami.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AFFILIATE PREVIEW SECTION -->
<section class="py-16 lg:py-24">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-cover bg-center bg-no-repeat rounded-3xl overflow-hidden shadow-xl"
            style="background-image: url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');">
            {{-- Dark overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-black/70"></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10 px-8 py-16 lg:px-16 lg:py-24">
                <div class="text-white max-w-xl lg:text-left text-center">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-jakarta leading-tight mb-4">
                        Program <span class="text-secondary">Afiliasi</span>
                    </h2>
                    <p class="text-lg text-white/80 mb-0 max-w-md mx-auto lg:mx-0">
                        Dapatkan komisi menarik dengan menjadi bagian dari program afiliasi kami.
                        Ajak teman dan keluarga untuk berwisata dan dapatkan keuntungan.
                    </p>
                    <div class="mt-6 w-20 h-1 bg-secondary rounded-full mx-auto lg:mx-0"></div>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('affiliates.index') }}"
                        class="group inline-flex items-center bg-white text-secondary font-semibold px-10 py-4 rounded-full shadow-2xl hover:bg-gray-100 transition-all transform hover:scale-105 text-lg">
                        Pelajari Selengkapnya
                        <x-heroicon-o-arrow-right class="w-5 h-5 ml-3 transition-transform group-hover:translate-x-1" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection