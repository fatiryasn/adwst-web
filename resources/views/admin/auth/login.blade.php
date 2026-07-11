<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Login - {{config('app.name')}}</title>
    @stack('styles')
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8 font-poppins">
        <div class="max-w-md w-full space-y-8 bg-surface rounded-xl p-8 border border-gray-200 shadow">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    AdminWisata
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Masuk ke panel kontrol admin
                </p>
            </div>

            <form class="mt-12 space-y-6" action="{{ route('admin.login') }}" method="POST"
                x-data="{ submitting: false }"
                x-on:submit="submitting = true">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="text-gray-600 text-sm mb-1">Alamat Email</label>
                    <div class="flex gap-2 items-center border border-gray-200 shadow rounded-lg px-3">
                        <x-heroicon-s-envelope class="h-5 w-5 text-secondary" />
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email') }}"
                            class="w-full py-3 outline-none bg-transparent"
                            placeholder="Alamat Email">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="text-gray-600 text-sm mb-1">Password</label>
                    <div class="flex gap-2 items-center border border-gray-200 shadow rounded-lg px-3">
                        <x-heroicon-s-lock-closed class="h-5 w-5 text-secondary" />
                        <input id="password" name="password" type="password"
                            autocomplete="current-password" required
                            class="w-full py-3 outline-none bg-transparent"
                            placeholder="Kata Sandi">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-12">
                    <button type="submit"
                        :disabled="submitting"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-secondary hover:bg-secondary/80 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!submitting">Masuk</span>
                        <span x-show="submitting">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- "Kembali ke halaman utama" link -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-secondary transition">
                ← Kembali ke halaman utama
            </a>
        </div>
    </div>
</body>

@include('partials.sweetalert')