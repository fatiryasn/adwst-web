@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan')

@section('content')
<div class="space-y-8">

    <!-- API KEY SECTION -->
    <div x-data="{ showKey: false, submittingRotation: false }" class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 font-jakarta flex items-center gap-2">
            <x-heroicon-o-key class="w-6 h-6 text-secondary" />
            API Key Global
        </h2>
        <p class="text-sm text-gray-600 mb-4">
            Kunci Global untuk menjaga keamanan server dari aplikasi mobile (staff).
            <strong class="text-red-600">Key ini hanya dibagikan ke staff!</strong>
        </p>

        <div class="flex items-center gap-4">
            <div class="flex-1 bg-gray-50 rounded-lg border px-4 py-3 tracking-widest text-sm text-gray-800 break-all select-all">
                <span x-show="!showKey">{{ str_repeat('•', 24) }}</span>
                <span x-show="showKey" x-cloak>{{ $apiKey }}</span>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <button type="button" @click="showKey = !showKey"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-md transition"
                    :title="showKey ? 'Sembunyikan key' : 'Tampilkan key'">
                    <x-heroicon-o-eye x-show="!showKey" class="w-5 h-5" />
                    <x-heroicon-o-eye-slash x-show="showKey" class="w-5 h-5" />
                </button>
                <button id="rotateKeyBtn"
                    @click="submittingRotation = true"
                    :disabled="submittingRotation"
                    class="flex-shrink-0 bg-secondary hover:bg-secondary/80 text-white font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <x-heroicon-o-arrow-path class="w-5 h-5" />
                    <span x-show="!submittingRotation">Rotate Key</span>
                    <span x-show="submittingRotation" class="inline-flex items-center gap-1">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
        <p class="text-xs text-gray-600 mt-2">
            Klik "Rotate Key" untuk membuat kunci baru. Kunci lama akan langsung tidak berlaku.
        </p>
    </div>

    <!-- PROFILE SECTION -->
    <div x-data="{ editMode: false, submitting: false }" class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 font-jakarta flex items-center gap-2">
            <x-heroicon-o-user-circle class="w-6 h-6 text-secondary" />
            Informasi Profil
        </h2>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5"
            x-on:submit="submitting = true">
            @csrf
            @method('PUT')

            <!-- created at -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-400 mb-1 uppercase">Tanggal dibuat</label>
                <div class="w-full border border-transparent py-1 text-gray-800">
                    {{ $user->created_at->translatedFormat('d F Y, H:i') }}
                </div>
            </div>

            <!-- email -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-400 mb-1 uppercase">Email</label>
                <div class="w-full border border-transparent py-1 text-gray-800">
                    {{ $user->email }}
                </div>
            </div>

            <!-- full name -->
            <div class="mb-5">
                <label for="full_name" class="block text-sm font-medium text-gray-400 mb-1 uppercase">Nama Lengkap</label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800">
                    {{ $user->full_name ?? '—' }}
                </div>
                <div x-show="editMode" x-cloak>
                    <input type="text" name="full_name" id="full_name"
                        value="{{ old('full_name', $user->full_name) }}"
                        class="w-full border border-gray-200 rounded-lg shadow px-3 py-2 focus:outline-none"
                        required>
                    @error('full_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- phone number -->
            <div class="mb-5">
                <label for="phone_number" class="block text-sm font-medium text-gray-400 mb-1 uppercase">Nomor Telepon</label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800">
                    {{ $user->phone_number ?? '—' }}
                </div>
                <div x-show="editMode" x-cloak>
                    <input type="text" name="phone_number" id="phone_number"
                        value="{{ old('phone_number', $user->phone_number) }}"
                        class="w-full border border-gray-200 rounded-lg shadow px-3 py-2 focus:outline-none"
                        placeholder="08xxxxxxxxxx">
                    @error('phone_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-show="!editMode"
                    @click="editMode = true"
                    class="bg-secondary hover:bg-secondary/80 text-white font-medium px-7 py-2 rounded-md transition inline-flex items-center gap-2">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                    Edit Profil
                </button>

                <template x-if="editMode">
                    <div class="flex gap-3">
                        <button type="button" @click="editMode = false"
                            :disabled="submitting"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                            Batal
                        </button>
                        <button type="submit"
                            :disabled="submitting"
                            class="bg-secondary hover:bg-secondary/80 text-white font-medium px-7 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                            <span x-show="!submitting" class="inline-flex items-center gap-2">
                                <x-heroicon-o-check-circle class="w-5 h-5" />
                                Simpan Perubahan
                            </span>
                            <span x-show="submitting" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </template>
            </div>
        </form>
    </div>

    <!-- logout button -->
    <div class="flex justify-end" x-data="{ submittingLogout: false }">
        <button id="logoutButton"
            @click="submittingLogout = true"
            :disabled="submittingLogout"
            class="bg-red-700 hover:bg-red-600 text-white font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
            <span x-show="!submittingLogout">Logout</span>
            <span x-show="submittingLogout" class="inline-flex items-center gap-1">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </div>
</div>

<!-- hidden forms -->
<form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" class="hidden">
    @csrf
</form>
<form id="rotateApiKeyForm" action="{{ route('admin.settings.rotate-api-key') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="password" id="rotatePasswordInput">
</form>

@include('partials.sweetalert')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        //logout confirm
        const logoutBtn = document.getElementById('logoutButton');
        const logoutForm = document.getElementById('logoutForm');

        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                text: 'Logout dari panel admin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    Alpine.$data(logoutBtn.closest('[x-data]')).submittingLogout = true;
                    logoutForm.submit();
                } else {
                    Alpine.$data(logoutBtn.closest('[x-data]')).submittingLogout = false;
                }
            });
        });

        //rotate api key
        const rotateBtn = document.getElementById('rotateKeyBtn');
        const rotateForm = document.getElementById('rotateApiKeyForm');
        const passwordInput = document.getElementById('rotatePasswordInput');

        rotateBtn.addEventListener('click', function() {
            Swal.fire({
                html: `
                    <h3 class="text-lg font-bold">Yakin ingin Rotasi Kunci API?</h3>
                    <p class="text-gray-800 mb-3 text-sm">Aksi ini akan membuat kunci lama invalid dan tidak bisa digunakan pengguna aplikasi mobile.</p>
                    <input type="password" id="swal-password" class="swal2-input" placeholder="Masukkan password akun Anda" required>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, rotate sekarang',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const password = document.getElementById('swal-password').value;
                    if (!password) {
                        Swal.showValidationMessage('Password harus diisi');
                        return false;
                    }
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set the password value in the hidden form
                    passwordInput.value = result.value;
                    // Set submitting state (Alpine variable on the API key section)
                    Alpine.$data(rotateBtn.closest('[x-data]')).submittingRotation = true;
                    rotateForm.submit();
                } else {
                    // Reset if cancelled
                    Alpine.$data(rotateBtn.closest('[x-data]')).submittingRotation = false;
                }
            });
        });

        // If the rotation fails (e.g., wrong password), reset the submitting state when the page reloads with error
        // This is handled by the server redirect back with errors; we could also reset on page load.
        // We'll simply reset the submitting flags on page load (they start false anyway)
    });
</script>
@endpush