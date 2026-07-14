<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'AdminWisata') - {{ config('app.name') }}</title>
    @stack('styles')
</head>

<body class="bg-white font-poppins antialiased">

    <!-- NAVBAR -->
    <!-- <header x-data="{ atTop: window.location.pathname === '/' && window.pageYOffset === 0, mobileMenuOpen: false }" @scroll.window="atTop = window.location.pathname === '/' && window.pageYOffset === 0"
        :class="atTop ? 'bg-transparent border-transparent shadow-none' : 'bg-white backdrop-blur-md shadow-sm border-b border-gray-100'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20">

                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-xl md:text-2xl font-bold text-secondary">
                        Admin<span class="font-nunito">Wisata</span>
                    </a>
                </div>

                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="{{ url('/') }}"
                        :class="atTop ? 'text-white hover:text-gray-200' : 'text-gray-700 hover:text-secondary'"
                        class="font-medium transition border-b-2 {{ request()->is('/') ? 'border-secondary' : 'border-transparent' }}">
                        Beranda
                    </a>
                    <a href="{{ route('destinations.index') }}"
                        :class="atTop ? 'text-white hover:text-gray-200' : 'text-gray-700 hover:text-secondary'"
                        class="font-medium transition border-b-2 {{ request()->routeIs('destinations.*') ? 'border-secondary' : 'border-transparent' }}">
                        Destinasi Wisata
                    </a>
                    <a href="{{ route('affiliates.index') }}"
                        :class="atTop ? 'text-white hover:text-gray-200' : 'text-gray-700 hover:text-secondary'"
                        class="font-medium transition border-b-2 {{ request()->routeIs('affiliates.*') ? 'border-secondary' : 'border-transparent' }}">
                        Program Afiliasi
                    </a>
                </nav>

                <div class="lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" :class="atTop ? 'text-white hover:text-gray-200' : 'text-gray-700 hover:text-secondary'"
                        class="text-gray-700 hover:text-secondary focus:outline-none">
                        <x-heroicon-o-bars-3 x-show="!mobileMenuOpen" class="w-6 h-6" />
                        <x-heroicon-o-x-mark x-show="mobileMenuOpen" class="w-6 h-6" />
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-white border-t border-gray-100 shadow-lg">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ url('/') }}" class="block text-gray-700 hover:text-secondary font-medium transition {{ request()->is('/') ? 'text-secondary' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('destinations.index') }}" class="block text-gray-700 hover:text-secondary font-medium transition {{ request()->routeIs('destinations.*') ? 'text-secondary' : '' }}">
                    Destinasi Wisata
                </a>
                <a href="{{ route('affiliates.index') }}" class="block text-gray-700 hover:text-secondary font-medium transition {{ request()->routeIs('affiliates.*') ? 'text-secondary' : '' }}">
                    Program Afiliasi
                </a>
            </div>
        </div>
    </header> -->

    <!-- NAVBAR -->
    <header x-data="{ mobileMenuOpen: false }"
        class="fixed top-0 left-0 right-0 z-50 bg-white backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20">

                <!-- logo -->
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-xl md:text-2xl font-bold text-secondary">
                        Admin<span class="font-nunito">Wisata</span>
                    </a>
                </div>

                <!-- desktop navs -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="{{ url('/') }}"
                        class="text-gray-700 hover:text-secondary font-medium transition border-b-2 {{ request()->is('/') ? 'border-secondary' : 'border-transparent' }}">
                        Beli Tiket
                    </a>
                    <a href="{{ route('affiliates.index') }}"
                        class="text-gray-700 hover:text-secondary font-medium transition border-b-2 {{ request()->routeIs('affiliates.*') ? 'border-secondary' : 'border-transparent' }}">
                        Program Afiliasi
                    </a>
                </nav>

                <!-- mobile hamburger -->
                <div class="lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-gray-700 hover:text-secondary focus:outline-none">
                        <x-heroicon-o-bars-3 x-show="!mobileMenuOpen" class="w-6 h-6" />
                        <x-heroicon-o-x-mark x-show="mobileMenuOpen" class="w-6 h-6" />
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-white border-t border-gray-100 shadow-lg">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ url('/') }}" class="block text-gray-700 hover:text-secondary font-medium transition {{ request()->is('/') ? 'text-secondary' : '' }}">
                    Beli Tiket
                </a>
                <a href="{{ route('affiliates.index') }}" class="block text-gray-700 hover:text-secondary font-medium transition {{ request()->routeIs('affiliates.*') ? 'text-secondary' : '' }}">
                    Program Afiliasi
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <!-- pt-16 lg:pt-20 -->
    <main class="pb-20">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark-secondary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-3 pt-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <!-- brand -->
                <div>
                    <h3 class="text-2xl font-bold text-white mb-4">Admin<span class="font-nunito">Wisata</span></h3>
                </div>

                <!-- links -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-gray-100 hover:underline transition text-sm">Beli Tiket</a></li>
                        <li><a href="{{ route('affiliates.index') }}" class="text-gray-100 hover:underline transition text-sm">Program Afiliasi</a></li>
                    </ul>
                </div>

                <!-- contact -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-100">
                        <li class="flex items-center gap-2">
                            <x-heroicon-o-envelope class="w-4 h-4 text-secondary" />
                            <span>-</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-heroicon-o-phone class="w-4 h-4 text-secondary" />
                            <span>-</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divider & Copyright -->
            <div class="mt-10 pt-6 border-t border-cyan-200/60 text-center text-sm text-gray-100">
                <p>&copy; {{ date('Y') }} AdminWisata. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>