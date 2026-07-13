<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-background font-poppins antialiased">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- MOBILE SIDEBAR -->
        <div x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
            @click="sidebarOpen = false">
        </div>

        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-72 transform bg-surface border-r border-gray-200 shadow transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex h-full flex-col">
                <!-- header -->
                <div class="flex h-20 items-center justify-between lg:justify-center px-4 border-b border-gray-200">
                    <div class="flex-shrink-0">
                        <a href="{{ url('/admin') }}" class="text-xl md:text-2xl font-bold text-secondary">
                            Admin<span class="font-nunito">Wisata</span>
                        </a>
                    </div> <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <!-- navigation links -->
                <nav class="flex-1 px-2 py-6 space-y-3 overflow-y-auto">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Navigasi</p>

                    <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                        <x-heroicon-o-squares-2x2 class="w-5 h-5 mr-3" />
                        Dashboard
                    </x-admin.nav-link>

                    <x-admin.nav-link href="{{ route('admin.destination.index') }}" :active="request()->routeIs('admin.destination.*')">
                        <x-heroicon-o-map-pin class="w-5 h-5 mr-3" />
                        Destinasi Wisata
                    </x-admin.nav-link>

                    <x-admin.nav-link href="{{ route('admin.ticket.index') }}" :active="request()->routeIs('admin.ticket.*')">
                        <x-heroicon-o-ticket class="w-5 h-5 mr-3" />
                        Tiket
                    </x-admin.nav-link>

                    <x-admin.nav-link href="{{ route('admin.affiliate.index') }}" :active="request()->routeIs('admin.affiliate.*')">
                        <x-heroicon-o-user-group class="w-5 h-5 mr-3" />
                        Afiliasi
                    </x-admin.nav-link>

                    <div class="pt-4">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Aksi Cepat</p>

                        <a href="{{ route('admin.destination.new') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium text-secondary hover:bg-secondary/10 rounded-r-lg transition-colors">
                            <x-heroicon-o-plus class="w-5 h-5 mr-3" />
                            Tambah Destinasi
                        </a>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- TOP NAVBAR -->
            <header class="h-20 bg-surface shadow border-b border-gray-200 flex items-center justify-between px-4 lg:px-6 z-10">
                <!-- mobile hamburger -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <x-heroicon-o-bars-3 class="w-6 h-6" />
                </button>

                <!-- page title -->
                <h1 class="text-lg font-semibold text-gray-800 hidden sm:block">
                    @yield('page_title', 'Dashboard')
                </h1>

                <!-- user link to setting -->
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none cursor-pointer hover:bg-secondary/10 rounded-md p-2 transition">
                    <img class="w-8 h-8 rounded"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? 'Admin') }}"
                        alt="">
                    <span class="ml-2 hidden sm:inline">{{ Auth::user()->full_name ?? 'Admin' }}</span>
                </a>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto px-7 py-12">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>