@extends('layouts.app')

@section('title', 'Beli Tiket ' . $destination->name)

@section('content')

<section class="pb-8 lg:pb-12 pt-24 lg:pt-36">
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 font-jakarta mb-4">Beli Tiket</h1>
    </div>
</section>

<!-- MAIN SPLIT SECTION -->
<section>
    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- LEFT -->
            <div>
                <div class="bg-surface rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="h-64 sm:h-80 overflow-hidden">
                        @if ($destination->thumbnail)
                        <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                            <x-heroicon-o-photo class="w-12 h-12" />
                        </div>
                        @endif
                    </div>
                    <div class="p-5 sm:p-6">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-jakarta mb-2">{{ $destination->name }}</h1>
                        @if ($destination->address)
                        <div class="flex items-start gap-2 text-gray-600 mb-3">
                            <x-heroicon-o-map-pin class="w-5 h-5 mt-0.5 flex-shrink-0 text-secondary" />
                            <span class="text-sm">{{ $destination->address }}</span>
                        </div>
                        @endif
                        <p class="text-sm text-gray-500 mt-4">Harga pondok akan ditampilkan setelah memilih.</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Multi‑step Form -->
            <div x-data="ticketWizard({{ $destination->cottages->toJson() }}, {{ isset($affiliate) ? json_encode(['code' => $affiliate->code]) : 'null' }}) ">
                <form action="{{ route('destinations.tickets.store', $destination->slug) }}" method="POST"
                    class="bg-surface rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8"
                    @submit.prevent="handleSubmit">
                    @csrf
                    <input type="hidden" name="cottage_id" x-model="selectedCottageId">

                    <!-- indicator -->
                    <div class="flex items-center justify-center gap-2 mb-8">
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="flex items-center gap-1">
                                <span :class="getStepClass(index)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition"
                                    x-text="index + 1"></span>
                                <span class="text-xs text-gray-600 hidden sm:block" x-text="step.label"></span>
                                <template x-if="index < steps.length - 1">
                                    <div class="h-0.5 w-8 bg-gray-200"></div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- step 1 personal -->
                    <div x-show="currentStep === 0" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 font-jakarta">Data Diri</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" x-model="form.customer_name" required class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50">
                                @error('customer_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                                <input type="number" name="customer_phone" x-model="form.customer_phone" required class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50">
                                @error('customer_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Referensi</label>
                                @if (isset($affiliate) && $affiliate)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 flex items-start gap-3">
                                    <x-heroicon-o-link class="w-5 h-5 text-green-600 mt-0.5" />
                                    <div class="text-sm text-green-800">
                                        <p class="font-semibold">Anda menggunakan link referensi!</p>
                                        <p class="mt-0.5">(Kode: <span class="font-mono font-bold text-secondary">{{ $affiliate->code }}</span>)</p>
                                    </div>
                                </div>
                                @endif
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach (['Sosial Media', 'Teman', 'Website', 'Lainnya'] as $source)
                                    <label class="flex items-center space-x-2 text-gray-700 cursor-pointer">
                                        <input type="checkbox" name="referral_sources[]" value="{{ $source }}" class="rounded border-gray-300 text-secondary focus:ring-secondary"
                                            {{ in_array($source, old('referral_sources', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">{{ $source }}</span>
                                    </label>
                                    @endforeach
                                    @if (isset($affiliate) && $affiliate)
                                    <label class="flex items-center space-x-2 text-gray-700">
                                        <input type="checkbox" name="referral_sources[]" value="Afiliasi" checked disabled class="rounded border-gray-300 text-secondary focus:ring-secondary disabled:opacity-50">
                                        <span class="text-sm">Afiliasi</span>
                                    </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- step 2 dates -->
                    <div x-show="currentStep === 1" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 font-jakarta">Tanggal</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                                <input type="date" name="visit_date" x-model="form.visit_date" :min="today()" required class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50">
                                <p class="text-xs text-gray-600 mt-1">Minimal hari ini.</p>
                                @error('visit_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kepulangan <span class="text-red-500">*</span></label>
                                <input type="date" name="departure_date" x-model="form.departure_date" :min="form.visit_date || today()" required class="w-full border border-gray-200 rounded-lg shadow px-5 py-3 focus:outline-none focus:ring-2 focus:ring-secondary/50">
                                <p class="text-xs text-gray-600 mt-1">Harus sama atau setelah tanggal kedatangan.</p>
                                @error('departure_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- step 3 cottage -->
                    <div x-show="currentStep === 2" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 font-jakarta">Pilih Pondok</h3>
                        <p class="text-sm text-gray-600 mb-4" x-text="dateRangeText"></p>
                        <div class="space-y-3">
                            <template x-for="cottage in cottages" :key="cottage.id">
                                <div @click="selectCottage(cottage)"
                                    :class="getCottageCardClass(cottage)"
                                    class="border rounded-xl p-4 cursor-pointer transition">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-gray-800" x-text="cottage.name"></h4>
                                            <p class="text-sm text-gray-600" x-text="cottage.description ?? '—'"></p>
                                            <p class="text-secondary font-bold mt-1" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(cottage.price)"></p>
                                        </div>
                                        <div class="text-sm font-semibold">
                                            <span x-show="cottage.available" class="text-green-600">Tersedia</span>
                                            <span x-show="!cottage.available" class="text-red-600">Tidak Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @error('cottage_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- step 4 confirmation -->
                    <div x-show="currentStep === 3" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 font-jakarta">Konfirmasi Pemesanan</h3>

                        <div class="bg-gray-50 rounded-xl p-5 space-y-6">
                            <!-- customer -->
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <x-heroicon-o-user class="w-5 h-5 text-blue-600" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Data Pemesan</p>
                                    <p class="font-semibold text-gray-900 text-lg font-jakarta" x-text="form.customer_name"></p>
                                    <p class="text-sm text-gray-600" x-text="form.customer_phone"></p>
                                    <p class="text-sm text-gray-600" x-show="form.customer_email" x-text="form.customer_email"></p>
                                </div>
                            </div>

                            <!-- tanggal -->
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <x-heroicon-o-calendar-days class="w-5 h-5 text-purple-600" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Tanggal</p>
                                    <p class="font-semibold text-gray-900 text-lg font-jakarta">
                                        <span x-text="formatDate(form.visit_date)"></span> → <span x-text="formatDate(form.departure_date)"></span>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span x-text="totalDays"></span> hari
                                    </p>
                                </div>
                            </div>

                            <!-- pondok -->
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <x-heroicon-o-home-modern class="w-5 h-5 text-green-600" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Pondok</p>
                                    <p class="font-semibold text-gray-900 text-lg font-jakarta" x-text="selectedCottageName"></p>
                                    <p class="text-secondary font-bold text-lg" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedCottagePrice)"></p>
                                </div>
                            </div>
                        </div>

                        @if (isset($affiliate) && $affiliate)
                        <input type="hidden" name="affiliate_code" value="{{ $affiliate->code }}">
                        @endif
                    </div>

                    <!-- nav buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" @click="prevStep" :disabled="currentStep === 0"
                            class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-lg transition disabled:opacity-50">
                            <x-heroicon-o-chevron-left class="w-5 h-5 mr-1" />
                            Kembali
                        </button>
                        <button type="button" @click="nextStep" x-show="currentStep < 3"
                            class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-medium px-5 py-2 rounded-lg transition">
                            Lanjut
                            <x-heroicon-o-chevron-right class="w-5 h-5 ml-1" />
                        </button>
                        <button type="submit" x-show="currentStep === 3" :disabled="submitting"
                            class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-8 py-3 rounded-lg transition disabled:opacity-50">
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
</section>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ticketWizard', (cottagesData, affiliate) => ({
            currentStep: 0,
            steps: [{
                    label: 'Data Diri'
                },
                {
                    label: 'Tanggal'
                },
                {
                    label: 'Pondok'
                },
                {
                    label: 'Konfirmasi'
                }
            ],
            form: {
                customer_name: '',
                customer_phone: '',
                customer_email: '',
                visit_date: '',
                departure_date: ''
            },
            selectedCottageId: null,
            submitting: false,
            cottages: [],
            init() {
                // Transform cottage data: attach the existing tickets array as booked periods
                this.cottages = cottagesData.map(c => ({
                    ...c,
                    bookedPeriods: c.tickets ? c.tickets.map(t => ({
                        visit: t.visit_date,
                        depart: t.departure_date
                    })) : []
                }));

                // Perbaikan pengisian nilai lama (old values) dari Laravel Blade
                this.form.customer_name = '{{ old("customer_name") ?? "" }}';
                this.form.customer_phone = '{{ old("customer_phone") ?? "" }}';
                this.form.customer_email = '{{ old("customer_email") ?? "" }}';
                this.form.visit_date = '{{ old("visit_date") ?? "" }}';
                this.form.departure_date = '{{ old("departure_date") ?? "" }}';

                const oldCottage = '{{ old("cottage_id") ?? "" }}';
                if (oldCottage) this.selectedCottageId = oldCottage;

                // Watch for date changes to update availability
                this.$watch('form.visit_date', () => this.updateAvailability());
                this.$watch('form.departure_date', () => this.updateAvailability());
                this.updateAvailability();
            },
            getStepClass(index) {
                if (index === this.currentStep) return 'border-secondary text-secondary bg-secondary/10';
                if (index < this.currentStep) return 'border-green-500 text-green-500 bg-green-50';
                return 'border-gray-300 text-gray-400';
            },
            nextStep() {
                if (this.currentStep < 3 && this.validateStep(this.currentStep)) {
                    this.currentStep++;
                }
            },
            prevStep() {
                if (this.currentStep > 0) this.currentStep--;
            },
            validateStep(step) {
                if (step === 0) {
                    if (!this.form.customer_name || !this.form.customer_phone) {
                        Swal.fire({
                            text: 'Mohon isi nama dan nomor telepon.',
                            icon: 'warning',
                            confirmButtonColor: '#f97316',
                        });
                        return false;
                    }
                    return true;
                }
                if (step === 1) {
                    if (!this.form.visit_date || !this.form.departure_date) {
                        Swal.fire({
                            text: 'Pilih tanggal kedatangan dan kepulangan.',
                            icon: 'warning',
                            confirmButtonColor: '#f97316',
                        });
                        return false;
                    }
                    if (new Date(this.form.departure_date) < new Date(this.form.visit_date)) {
                        Swal.fire({
                            text: 'Tanggal kepulangan harus setelah atau sama dengan tanggal kedatangan.',
                            icon: 'warning',
                            confirmButtonColor: '#f97316',
                        });
                        return false;
                    }
                    if (new Date(this.form.visit_date) < new Date(this.today())) {
                        Swal.fire({
                            text: 'Tanggal kedatangan tidak boleh kurang dari hari ini.',
                            icon: 'warning',
                            confirmButtonColor: '#f97316',
                        });
                        return false;
                    }
                    return true;
                }
                if (step === 2) {
                    if (!this.selectedCottageId) {
                        Swal.fire({
                            text: 'Pilih satu cottage.',
                            icon: 'warning',
                            confirmButtonColor: '#f97316',
                        });
                        return false;
                    }
                    const selected = this.cottages.find(c => c.id == this.selectedCottageId);
                    if (!selected || !selected.available) {
                        Swal.fire({
                            text: 'Cottage tidak tersedia.',
                            icon: 'warning',
                            confirmButtonColor: '#f97316',
                        });
                        return false;
                    }
                    return true;
                }
                return true;
            },
            today() {
                return new Date().toISOString().split('T')[0];
            },
            updateAvailability() {
                if (!this.form.visit_date || !this.form.departure_date) return;
                const visit = new Date(this.form.visit_date);
                const depart = new Date(this.form.departure_date);
                if (depart < visit) return;

                this.cottages.forEach(c => {
                    // Check if any booked period overlaps
                    const overlap = c.bookedPeriods.some(p => {
                        const pVisit = new Date(p.visit);
                        const pDepart = new Date(p.depart);
                        return (visit <= pDepart && depart >= pVisit);
                    });
                    c.available = !overlap;
                });

                // If selected cottage becomes unavailable, clear it
                if (this.selectedCottageId) {
                    const sel = this.cottages.find(c => c.id == this.selectedCottageId);
                    if (sel && !sel.available) this.selectedCottageId = null;
                }
            },
            selectCottage(cottage) {
                if (cottage.available) {
                    this.selectedCottageId = cottage.id;
                }
            },
            getCottageCardClass(cottage) {
                if (!cottage.available) return 'bg-red-50 border-red-300 cursor-not-allowed';
                if (this.selectedCottageId == cottage.id) return 'bg-blue-50 border-blue-300';
                return 'bg-white border-gray-200 hover:border-secondary';
            },
            get selectedCottageName() {
                const c = this.cottages.find(c => c.id == this.selectedCottageId);
                return c ? c.name : '—';
            },
            get selectedCottagePrice() {
                const c = this.cottages.find(c => c.id == this.selectedCottageId);
                return c ? c.price : 0;
            },
            handleSubmit() {
                if (!this.validateStep(2)) return;

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
                        this.submitting = true;
                        this.$el.submit();
                    }
                });
            },
            get totalDays() {
                if (!this.form.visit_date || !this.form.departure_date) return 0;
                const visit = new Date(this.form.visit_date);
                const depart = new Date(this.form.departure_date);
                const diff = depart - visit;
                const nights = Math.ceil(diff / (1000 * 60 * 60 * 24));
                return nights + 1; // inclusive of both days
            },
            get dateRangeText() {
                if (!this.form.visit_date || !this.form.departure_date) {
                    return 'Pondok yang tersedia';
                }
                const options = {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                };
                const visit = new Date(this.form.visit_date);
                const depart = new Date(this.form.departure_date);
                const visitStr = visit.toLocaleDateString('id-ID', options);
                const departStr = depart.toLocaleDateString('id-ID', options);
                if (visitStr === departStr) {
                    return `Pondok yang tersedia di tanggal ${visitStr}`;
                } else {
                    return `Pondok yang tersedia di tanggal ${visitStr} sampai ${departStr}`;
                }
            },
            formatDate(dateStr) {
                if (!dateStr) return '—';
                const options = {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                };
                return new Date(dateStr + 'T00:00:00').toLocaleDateString('id-ID', options);
            },
        }));
    });
</script>
@endpush

@include('partials.sweetalert')
@endsection