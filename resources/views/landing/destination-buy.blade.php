@extends('layouts.app')

@section('title', 'Beli Tiket ' . $destination->name)

@section('content')

<!-- BREADCRUMB -->
<div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10 pt-16 lg:pt-32 pb-4">
    <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-secondary transition">Beranda</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <a href="{{ route('destinations.index') }}" class="hover:text-secondary transition">Destinasi Wisata</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <a href="{{ route('destinations.show', $destination->slug) }}" class="hover:text-secondary transition">{{ $destination->name }}</a>
        <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
        <span class="font-medium text-secondary">Beli Tiket</span>
    </nav>
</div>

<!-- title page -->
<section class="pb-8 lg:pb-12">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 font-jakarta mb-4">Beli Tiket</h1>
    </div>
</section>

<!-- MAIN SPLIT SECTION -->
<section class="">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- LEFT: Destination Detail -->
            <div>
                <div class="bg-surface rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- image -->
                    <div class="h-64 sm:h-80 overflow-hidden">
                        @if ($destination->thumbnail)
                        <img src="{{ asset('storage/' . $destination->thumbnail) }}"
                            alt="{{ $destination->name }}"
                            class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                            <x-heroicon-o-photo class="w-12 h-12" />
                        </div>
                        @endif
                    </div>

                    <!-- info -->
                    <div class="p-5 sm:p-6">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-jakarta mb-2">
                            {{ $destination->name }}
                        </h1>
                        @if ($destination->address)
                        <div class="flex items-start gap-2 text-gray-600 mb-3">
                            <x-heroicon-o-map-pin class="w-5 h-5 mt-0.5 flex-shrink-0 text-secondary" />
                            <span class="text-sm">{{ $destination->address }}</span>
                        </div>
                        @endif
                        <div class="flex items-baseline gap-2 mt-4">
                            <span class="text-xl font-bold text-secondary font-jakarta">
                                Rp {{ number_format($destination->ticket_price, 0, ',', '.') }}
                            </span>
                            <span class="text-sm text-gray-500">/ orang</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Booking Form -->
            <div>
                <div class="bg-surface rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 font-jakarta">Isi data berikut</h2>

                    <form action="{{ route('destinations.tickets.store', $destination->slug) }}" method="POST" class="space-y-5"
                        x-data="{ submitting: false }"
                        x-on:submit.prevent="confirmSubmit($el)">
                        @csrf

                        <!-- name -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" id="customer_name"
                                value="{{ old('customer_name') }}"
                                placeholder="Nama Anda"
                                :disabled="submitting"
                                class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50 disabled:opacity-50 disabled:cursor-not-allowed"
                                required>
                            @error('customer_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- email -->
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="customer_email" id="customer_email"
                                value="{{ old('customer_email') }}"
                                placeholder="contoh@email.com"
                                :disabled="submitting"
                                class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50 disabled:opacity-50 disabled:cursor-not-allowed">
                            @error('customer_email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- phone -->
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone') }}"
                                placeholder="08xxxxxxxxxx"
                                :disabled="submitting"
                                class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50 disabled:opacity-50 disabled:cursor-not-allowed"
                                required>
                            @error('customer_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- visit date -->
                        <div>
                            <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan</label>
                            <input type="date" name="visit_date" id="visit_date"
                                value="{{ old('visit_date') }}"
                                :disabled="submitting"
                                class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50 disabled:opacity-50 disabled:cursor-not-allowed">
                            @error('visit_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- departure date -->
                        <div>
                            <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kepulangan</label>
                            <input type="date" name="departure_date" id="departure_date"
                                value="{{ old('departure_date') }}"
                                :disabled="submitting"
                                class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50 disabled:opacity-50 disabled:cursor-not-allowed">
                            @error('departure_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- referral source -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sumber Referensi
                            </label>

                            @if (isset($affiliate) && $affiliate)
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <x-heroicon-o-link class="w-5 h-5 text-green-600" />
                                </div>
                                <div class="text-sm text-green-800">
                                    <p class="font-semibold">Anda menggunakan link referensi!</p>
                                    <p class="mt-0.5">
                                        (Kode: <span class="font-mono font-bold text-secondary">{{ $affiliate->code }}</span>)
                                    </p>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @php
                                $sources = ['Sosial Media', 'Teman', 'Website', 'Lainnya'];
                                $oldSources = old('referral_sources', []);
                                @endphp
                                @foreach ($sources as $source)
                                <label class="flex items-center space-x-2 text-gray-700 cursor-pointer">
                                    <input type="checkbox" name="referral_sources[]" value="{{ $source }}"
                                        :disabled="submitting"
                                        class="rounded border-gray-300 text-secondary focus:ring-secondary disabled:opacity-50"
                                        {{ in_array($source, $oldSources) ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $source }}</span>
                                </label>
                                @endforeach

                                @if (isset($affiliate) && $affiliate)
                                <label class="flex items-center space-x-2 text-gray-700">
                                    <input type="checkbox" name="referral_sources[]" value="Afiliasi"
                                        :disabled="submitting"
                                        class="rounded border-gray-300 text-secondary focus:ring-secondary disabled:opacity-50"
                                        checked disabled>
                                    <span class="text-sm">Afiliasi</span>
                                </label>
                                @endif
                            </div>
                            @error('referral_sources') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- hidden affiliate code -->
                        @if (isset($affiliate) && $affiliate)
                        <input type="hidden" name="affiliate_code" value="{{ $affiliate->code }}">
                        @endif

                        <!-- submit button -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                :disabled="submitting"
                                class="bg-secondary hover:bg-secondary/80 text-white font-semibold px-8 py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center">
                                <span x-show="!submitting">Pesan Tiket</span>
                                <span x-show="submitting" class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script>
    window.confirmSubmit = function(formElement) {
        Swal.fire({
            title: 'Konfirmasi Pemesanan',
            html: 'Yakin ingin meneruskan?<br>Pastikan data Anda valid.<br>Setelah ini Anda akan menerima tiket dan melakukan pembayaran via WhatsApp.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Pesan Tiket',
            cancelButtonText: 'Periksa Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                const alpineData = Alpine.$data(formElement);
                if (alpineData) {
                    alpineData.submitting = true;
                }
                formElement.submit();
            }
        });
    }
</script>
@endpush

{{-- SweetAlert (any flash) --}}
@include('partials.sweetalert')

@endsection