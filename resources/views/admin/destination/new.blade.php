@extends('layouts.admin')

@section('title', 'Tambah Destinasi')
@section('page_title', 'Tambah Destinasi')

@section('content')

<div class="space-y-6">
    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{ url('/admin/destination') }}" class="hover:text-secondary transition">Destinasi Wisata</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">Tambah</span>
        </nav>
    </div>

    <div x-data="{
        submitting: false,
        thumbnailPreview: null,
        fileInput: null,
        cottages: [],
        init() {
            this.fileInput = this.$refs.fileInput;
        },
        triggerFileInput() {
            this.fileInput.click();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            this.thumbnailPreview = file ? URL.createObjectURL(file) : null;
        },
        clearThumbnail() {
            this.thumbnailPreview = null;
            this.fileInput.value = '';
        },
        addCottage() {
            this.cottages.push({ name: '', description: '', price: '' });
        },
        removeCottage(index) {
            this.cottages.splice(index, 1);
        }
    }" class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 font-jakarta">Tambah Destinasi</h2>

        <form action="{{ route('admin.destination.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-8"
            x-ref="form"
            x-on:submit="submitting = true"
            autocomplete="off">
            @csrf

            <!-- name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Destinasi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                    value="{{ old('name') }}"
                    placeholder="cth: Pantai Kuta"
                    autocomplete="off"
                    class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none"
                    required>
                <p class="mt-1 text-[10px] text-gray-500">Maksimal 200 karakter.</p>
            </div>

            <!-- description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                    placeholder="Deskripsi lengkap tentang destinasi..."
                    autocomplete="off"
                    class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">{{ old('description') }}</textarea>
                <p class="mt-1 text-[10px] text-gray-500">Opsional. Deskripsi detail destinasi.</p>
            </div>

            <!-- address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" id="address" rows="2"
                    placeholder="Alamat lengkap destinasi..."
                    autocomplete="off"
                    class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">{{ old('address') }}</textarea>
                <p class="mt-1 text-[10px] text-gray-500">Opsional. Alamat fisik atau lokasi umum.</p>
            </div>

            <!-- lat & long -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="number" name="latitude" id="latitude"
                        value="{{ old('latitude') }}"
                        placeholder="cth: -6.200000"
                        autocomplete="off"
                        step="any"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">
                    <p class="mt-1 text-[10px] text-gray-500">Koordinat lintang (desimal).</p>
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="number" name="longitude" id="longitude"
                        value="{{ old('longitude') }}"
                        placeholder="cth: 106.800000"
                        autocomplete="off"
                        step="any"
                        class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">
                    <p class="mt-1 text-[10px] text-gray-500">Koordinat bujur (desimal).</p>
                </div>
            </div>

            <!-- status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                    class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                </select>
                <p class="mt-1 text-[10px] text-gray-500">Status menentukan apakah destinasi ditampilkan di halaman publik.</p>
            </div>

            <!-- thumbnail -->
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>

                <div class="flex gap-4">
                    <!-- Image preview -->
                    <div>
                        <template x-if="thumbnailPreview">
                            <div class="relative inline-block">
                                <img :src="thumbnailPreview" alt="Thumbnail Preview" class="h-32 w-48 object-cover rounded border">
                                <button type="button" @click="clearThumbnail()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600 transition"
                                    title="Hapus gambar">
                                    <x-heroicon-o-x-mark class="w-4 h-4" />
                                </button>
                            </div>
                        </template>
                        <div x-show="!thumbnailPreview" class="h-32 w-48 flex items-center justify-center border border-dashed border-gray-300 rounded text-gray-400 text-sm">
                            Tidak ada gambar
                        </div>
                    </div>

                    <!-- File input button -->
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

                <p class="text-[10px] text-gray-500">Format: JPEG, PNG, WebP. Maks. 2 MB.</p>
                @error('thumbnail')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- COTTAGES REPEATER -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 font-jakarta">Pondok</h3>
                <p class="text-xs text-gray-500 mb-4">Tambahkan pondok yang tersedia di destinasi ini beserta harganya. Kosongkan jika tidak ada.</p>

                <div class="space-y-4">
                    <template x-for="(cottage, index) in cottages" :key="index">
                        <div class="bg-gray-50 rounded-xl p-4 relative border border-gray-200">
                            <button type="button" @click="removeCottage(index)"
                                class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition"
                                :disabled="submitting"
                                title="Hapus cottage">
                                <x-heroicon-o-x-circle class="w-5 h-5" />
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Nama <span class="text-red-500">*</span></label>
                                    <input type="text" :name="`cottages[${index}][name]`" x-model="cottage.name"
                                        placeholder="cth: Deluxe Suite"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none bg-surface shadow"
                                        required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-gray-600 mb-1">Deskripsi</label>
                                    <input type="text" :name="`cottages[${index}][description]`" x-model="cottage.description"
                                        placeholder="Deskripsi singkat"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none bg-surface shadow">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" :name="`cottages[${index}][price]`" x-model="cottage.price"
                                        placeholder="cth: 250000"
                                        min="0" step="0.01"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none bg-surface shadow"
                                        required>
                                    <p class="text-[10px] text-gray-500 mt-1">Isi 0 jika harga fleksibel.</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addCottage"
                        :disabled="submitting"
                        class="inline-flex items-center text-secondary hover:text-secondary/80 font-medium text-sm transition">
                        <x-heroicon-o-plus-circle class="w-5 h-5 mr-1" />
                        Tambah Pondok
                    </button>
                </div>
                @error('cottages.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="button"
                    @click="
                        $refs.form.reset();
                        document.getElementById('status').value = 'active';
                        clearThumbnail();
                        cottages = [];
                        addCottage(); // keep one empty cottage
                    "
                    :disabled="submitting"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Clear
                </button>

                <button type="submit"
                    :disabled="submitting"
                    class="bg-secondary hover:bg-secondary/80 text-white font-medium px-7 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Tambah Destinasi</span>
                    <span x-show="submitting">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert partial --}}
@include('partials.sweetalert')
@endsection