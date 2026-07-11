@extends('layouts.admin')

@section('title', $destination->name)
@section('page_title', $destination->name)

@section('content')
<div class="space-y-6">
    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{ url('/admin/destination') }}" class="hover:text-secondary transition">Destinasi Wisata</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">{{$destination->name}}</span>
        </nav>
    </div>

    <div x-data="{ editMode: false, submitting: false, thumbnailPreview: null }" class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Detail Destinasi</h2>

        <form action="{{ route('admin.destination.update', $destination->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-8"
            x-ref="form"
            x-on:submit="submitting = true"
            autocomplete="off">
            @csrf
            @method('PUT')

            <!-- name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Destinasi <span class="text-red-500">*</span>
                </label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                    {{ $destination->name }}
                </div>
                <div x-show="editMode" x-cloak>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $destination->name) }}"
                        placeholder="Nama destinasi"
                        autocomplete="off"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none"
                        required>
                    <p class="mt-1 text-[10px] text-gray-500">Maksimal 200 karakter.</p>
                </div>
            </div>

            <!-- slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <div class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                    {{ $destination->slug }}
                </div>
            </div>

            <!-- description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                    {{ $destination->description ?? '—' }}
                </div>
                <div x-show="editMode" x-cloak>
                    <textarea name="description" id="description" rows="4"
                        placeholder="Deskripsi lengkap..."
                        autocomplete="off"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">{{ old('description', $destination->description) }}</textarea>
                    <p class="mt-1 text-[10px] text-gray-500">Opsional. Deskripsi detail destinasi.</p>
                </div>
            </div>

            <!-- ticket price -->
            <div>
                <label for="ticket_price" class="block text-sm font-medium text-gray-700 mb-1">
                    Harga Tiket (Rp) <span class="text-red-500">*</span>
                </label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                    {{ 'Rp ' . number_format($destination->ticket_price, 0, ',', '.') }}
                </div>
                <div x-show="editMode" x-cloak>
                    <input type="number" name="ticket_price" id="ticket_price"
                        value="{{ old('ticket_price', $destination->ticket_price) }}"
                        placeholder="cth: 50000"
                        autocomplete="off"
                        min="0" step="0.01"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none"

                        required>
                    <p class="mt-1 text-[10px] text-gray-500">Gunakan angka. Titik untuk desimal.</p>
                </div>
            </div>

            <!-- address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                    {{ $destination->address ?? '—' }}
                </div>
                <div x-show="editMode" x-cloak>
                    <textarea name="address" id="address" rows="2"
                        placeholder="Alamat lengkap..."
                        autocomplete="off"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">{{ old('address', $destination->address) }}</textarea>
                    <p class="mt-1 text-[10px] text-gray-500">Opsional.</p>
                </div>
            </div>

            <!-- lat & long -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                        {{ $destination->latitude ?? '—' }}
                    </div>
                    <div x-show="editMode" x-cloak>
                        <input type="number" name="latitude" id="latitude"
                            value="{{ old('latitude', $destination->latitude) }}"
                            placeholder="cth: -6.200000"
                            autocomplete="off"
                            step="any"
                            class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">
                        <p class="mt-1 text-[10px] text-gray-500">Koordinat lintang (desimal).</p>

                    </div>
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800 bg-secondary/5">
                        {{ $destination->longitude ?? '—' }}
                    </div>
                    <div x-show="editMode" x-cloak>
                        <input type="number" name="longitude" id="longitude"
                            value="{{ old('longitude', $destination->longitude) }}"
                            placeholder="cth: 106.800000"
                            autocomplete="off"
                            step="any"
                            class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">
                        <p class="mt-1 text-[10px] text-gray-500">Koordinat bujur (desimal).</p>

                    </div>
                </div>
            </div>

            <!-- status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status <span class="text-red-500">*</span>
                </label>
                <div x-show="!editMode" class="w-full border border-transparent py-1 text-gray-800">
                    @if ($destination->status == 'active')
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inaktif</span>
                    @endif
                </div>
                <div x-show="editMode" x-cloak>
                    <select name="status" id="status"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">
                        <option value="active" {{ old('status', $destination->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $destination->status) == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                    </select>
                    <p class="mt-1 text-[10px] text-gray-500">Status menentukan apakah destinasi ditampilkan di halaman publik.</p>

                </div>
            </div>

            <!-- thumbnail -->
            <div x-data="{
        existingThumbnailUrl: '{{ $destination->thumbnail ? asset('storage/' . $destination->thumbnail) : '' }}',
        thumbnailPreview: null,
        removeThumbnail: false,
        fileInput: null,
        init() {
            this.fileInput = this.$refs.fileInput;
        },
        triggerFileInput() {
            this.fileInput.click();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.thumbnailPreview = URL.createObjectURL(file);
                this.removeThumbnail = false; // selecting a new file cancels removal
            }
        },
        removeExisting() {
            this.removeThumbnail = true;
            this.thumbnailPreview = null;
            this.fileInput.value = ''; // clear file input
        }
    }" class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>

                <!-- view mode -->
                <div x-show="!editMode">
                    @if ($destination->thumbnail)
                    <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="Thumbnail" class="h-32 w-48 object-cover rounded border">
                    @else
                    <p class="text-gray-500 text-sm">Tidak ada thumbnail.</p>
                    @endif
                </div>

                <!-- edit mode -->
                <div x-show="editMode" x-cloak class="space-y-4 ">
                    <div class="flex gap-4">
                        <!-- img preview -->
                        <div>
                            <template x-if="!removeThumbnail && (thumbnailPreview || existingThumbnailUrl)">
                                <div class="relative inline-block">
                                    <img :src="thumbnailPreview || existingThumbnailUrl" alt="Preview" class="h-32 w-48 object-cover rounded border">
                                    <button type="button" x-show="!thumbnailPreview && existingThumbnailUrl"
                                        @click="removeExisting()"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600 transition"
                                        title="Hapus thumbnail">
                                        <x-heroicon-o-x-mark class="w-4 h-4" />
                                    </button>
                                </div>
                            </template>
                            <div x-show="removeThumbnail || (!thumbnailPreview && !existingThumbnailUrl)" class="h-32 w-48 flex items-center justify-center border border-dashed border-gray-300 rounded text-gray-400 text-sm">
                                Tidak ada gambar
                            </div>
                        </div>
                        <!-- file select button -->
                        <div class="flex items-start gap-4">
                            <button type="button" @click="triggerFileInput()"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                                <x-heroicon-o-photo class="w-5 h-5 mr-2 text-gray-500" />
                                Pilih Gambar
                            </button>
                            <input type="file" name="thumbnail" x-ref="fileInput"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                                @change="handleFileChange">
                        </div>
                    </div>

                    <!-- Hidden field to signal removal -->
                    <input type="hidden" name="remove_thumbnail" x-bind:value="removeThumbnail ? 1 : 0">

                    <p class="text-[10px] text-gray-500">Format: JPEG, PNG, WebP. Maks. 2 MB. Pilih gambar baru atau hapus yang sudah ada.</p>
                </div>
            </div>

            <!-- timestamps -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
                    <div class="w-full border border-transparent py-1 text-gray-800 text-sm bg-secondary/5">
                        {{ $destination->created_at->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diperbarui</label>
                    <div class="w-full border border-transparent py-1 text-gray-800 text-sm bg-secondary/5">
                        {{ $destination->updated_at->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <!-- view mode-->
                <div x-show="!editMode" class="flex gap-3">
                    <button type="button" @click="editMode = true"
                        class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-medium px-5 py-2 rounded-md transition">
                        <x-heroicon-o-pencil-square class="w-5 h-5 mr-2" />
                        Edit Destinasi
                    </button>
                    <button type="button" id="deleteButton"
                        class="inline-flex items-center bg-red-700 hover:bg-red-600 text-white font-medium px-5 py-2 rounded-md transition">
                        <x-heroicon-o-trash class="w-5 h-5 mr-2" />
                        Hapus Destinasi
                    </button>
                </div>

                <!-- edit mode -->
                <template x-if="editMode">
                    <div class="flex gap-3">
                        <button type="button" @click="editMode = false"
                            :disabled="submitting"
                            class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                            Batal
                        </button>
                        <button type="submit"
                            :disabled="submitting"
                            class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting" class="inline-flex items-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                                Simpan Perubahan
                            </span>
                            <span x-show="submitting" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

    <!-- hidden delete form -->
    <form id="deleteForm" action="{{ route('admin.destination.destroy', $destination->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

@include('partials.sweetalert')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('deleteButton');
        const deleteForm = document.getElementById('deleteForm');

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    html: 'Hapus destinasi <strong>{{ $destination->name }}</strong>?. Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteForm.submit();
                    }
                });
            });
        }
    });
</script>
@endpush