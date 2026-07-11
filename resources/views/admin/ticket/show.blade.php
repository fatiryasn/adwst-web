@extends('layouts.admin')

@section('title', $ticket->code)
@section('page_title', $ticket->code)

@section('content')
<div class="space-y-6" id="ticket-admin-container"
    data-payment-status="{{ $ticket->payment_status }}"
    data-ticket-status="{{ $ticket->ticket_status }}">

    <!-- breadcrumb -->
    <div class="">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ url('/admin') }}" class="hover:text-secondary transition">Dashboard</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{ url('/admin/ticket') }}" class="hover:text-secondary transition">Tiket</a>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="font-medium text-secondary">{{$ticket->code}}</span>
        </nav>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex flex-wrap gap-3">
        @if ($ticket->ticket_status !== 'checked_in')
        <button id="actionStatusBtn"
            class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-medium px-5 py-2 rounded-md transition">
            <x-heroicon-o-arrow-path class="w-5 h-5 mr-2" />
            Update Status
        </button>

        @if ($ticket->payment_status === 'paid' && $ticket->ticket_status === 'active')
        <button id="checkInBtn"
            class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-md transition">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
            Tandai sebagai checked-in
        </button>
        @endif
        @else
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
            Tiket sudah check-in. Tidak ada aksi lanjutan.
        </div>
        @endif

        {{-- Download PDF Button --}}
        <button id="download-pdf-btn"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-md transition">
            <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
            Download Ticket (PDF)
        </button>
    </div>

    <!-- TICKET INFORMATION -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta">Informasi Tiket</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Kode Tiket</dt>
                <dd class="mt-1 font-bold text-secondary text-2xl">{{ $ticket->code }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Tanggal Dibuat</dt>
                <dd class="mt-1 text-sm">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito">Terakhir Update</dt>
                <dd class="mt-1 text-sm">{{ $ticket->updated_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
        </div>
    </div>

    <!-- STATUS TIKET -->
    <div class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta">Status Tiket</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col">
                <span class="text-xs text-gray-600 uppercase tracking-wide font-nunito mb-2">Payment Status</span>
                @php
                $paymentColors = [
                'pending' => 'bg-blue-100 text-blue-800 border-blue-300',
                'paid' => 'bg-green-100 text-green-800 border-green-300',
                'failed' => 'bg-red-100 text-red-800 border-red-300',
                'refunded' => 'bg-gray-100 text-gray-800 border-gray-300',
                ];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $paymentColors[$ticket->payment_status] }} uppercase w-fit">
                    {{ ucfirst($ticket->payment_status) }}
                </span>
            </div>

            <div class="flex flex-col">
                <span class="text-xs text-gray-600 uppercase tracking-wide font-nunito mb-2">Ticket Status</span>
                @php
                $ticketColors = [
                'active' => 'bg-blue-100 text-blue-800 border-blue-300',
                'checked_in' => 'bg-green-100 text-green-800 border-green-300',
                'expired' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                ];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $ticketColors[$ticket->ticket_status] }} uppercase w-fit">
                    {{ match($ticket->ticket_status) {
                    'active'     => 'Active',
                    'checked_in' => 'Checked In',
                    'expired'    => 'Expired',
                    'cancelled'  => 'Cancelled',
                    default      => ucfirst(str_replace('_', ' ', $ticket->ticket_status))
                } }}
                </span>
            </div>

            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide font-nunito mb-1">Verifikasi Pembayaran</dt>
                <dd class="text-sm">
                    @if ($ticket->payment_verified_at)
                    {{ $ticket->payment_verified_at->translatedFormat('d M Y, H:i') }}<br>
                    <span class="text-gray-400">by {{ $ticket->verifiedBy->full_name ?? '—' }}</span>
                    @else
                    <span class="text-gray-400">—</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-xs text-gray-600 uppercase tracking-wide mb-1">Waktu Check-in</dt>
                <dd class="text-sm">
                    @if ($ticket->checked_in_at)
                    {{ $ticket->checked_in_at->translatedFormat('d M Y, H:i') }}
                    @else
                    <span class="text-gray-400">—</span>
                    @endif
                </dd>
            </div>
        </div>
    </div>

    <!-- GRID CARDS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- customer -->
        <div class="bg-surface rounded-xl shadow border p-5 border-l-4 border-blue-400">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta flex items-center gap-2">
                <x-heroicon-o-user class="w-5 h-5 text-blue-500" />
                Pelanggan
            </h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-600 font-nunito">Nama</dt>
                <dd class="font-medium text-gray-800">{{ $ticket->customer_name }}</dd>
                <dt class="text-gray-600 font-nunito">No. Telp</dt>
                <dd>{{ $ticket->customer_phone }}</dd>
                @if ($ticket->customer_email)
                <dt class="text-gray-600 font-nunito">Email</dt>
                <dd>{{ $ticket->customer_email }}</dd>
                @endif
            </dl>
        </div>

        <!-- destination -->
        <div class="bg-surface rounded-xl shadow border p-5 border-l-4 border-green-400">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta flex items-center gap-2">
                <x-heroicon-o-map-pin class="w-5 h-5 text-green-500" />
                Destinasi
            </h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-600 font-nunito">Nama</dt>
                <dd class="font-medium text-gray-800">
                    {{ $ticket->destination->name ?? '—' }}
                    @if($ticket->cottage)
                    ({{ $ticket->cottage->name }})
                    @endif
                </dd>
                <dt class="text-gray-600 font-nunito">Harga Tiket</dt>
                <dd>Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}</dd>
                @if ($ticket->destination->address)
                <dt class="text-gray-600 font-nunito">Alamat</dt>
                <dd>{{ $ticket->destination->address }}</dd>
                @endif
            </dl>
        </div>

        <!-- visit date -->
        <div class="bg-surface rounded-xl shadow border p-5 border-l-4 border-purple-400">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-purple-500" />
                Tanggal Kunjungan
            </h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-600 font-nunito">Kedatangan</dt>
                <dd>{{ $ticket->visit_date ? $ticket->visit_date->format('d M Y') : '—' }}</dd>
                <dt class="text-gray-600 font-nunito">Kepulangan</dt>
                <dd>{{ $ticket->departure_date ? $ticket->departure_date->format('d M Y') : '—' }}</dd>
                @if ($ticket->visit_date && $ticket->departure_date)
                @php
                $totalDays = $ticket->visit_date->diffInDays($ticket->departure_date) + 1;
                @endphp
                <dt class="text-gray-600 font-nunito">Total Hari</dt>
                <dd>{{ $totalDays }} hari</dd>
                @endif
            </dl>
        </div>

        <!-- referral -->
        <div class="bg-surface rounded-xl shadow border p-5 border-l-4 border-orange-400">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta flex items-center gap-2">
                <x-heroicon-o-share class="w-5 h-5 text-orange-500" />
                Referral
            </h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-600 font-nunito">Affiliate Code</dt>
                <dd>
                    @if ($ticket->affiliate)
                    <span class="font-semibold text-secondary">{{ $ticket->affiliate->code }}</span>
                    @else
                    <span class="text-gray-400">—</span>
                    @endif
                </dd>
                <dt class="text-gray-600 font-nunito">Source</dt>
                <dd>
                    @if ($ticket->referral_source)
                    {{ implode(', ', explode(',', $ticket->referral_source)) }}
                    @else
                    —
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    <!-- NOTES  -->
    <div x-data="{ editNotes: false, submittingNotes: false }" class="bg-surface rounded-xl shadow border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-jakarta">Catatan</h3>
        <form action="{{ route('admin.ticket.updateNotes', $ticket->id) }}" method="POST" x-on:submit="submittingNotes = true">
            @csrf
            <div x-show="!editNotes">
                <div class="text-sm text-gray-800 whitespace-pre-line">{{ $ticket->notes ?? 'Tidak ada catatan.' }}</div>
                <button type="button" @click="editNotes = true" class="mt-5 inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-medium px-4 py-2 rounded-md transition">
                    <x-heroicon-o-pencil-square class="w-5 h-5 mr-2" />
                    Edit Catatan
                </button>
            </div>
            <div x-show="editNotes" x-cloak class="space-y-3">
                <textarea name="notes" rows="4" class="w-full border border-gray-200 rounded-lg shadow px-5 py-2 focus:outline-none">{{ old('notes', $ticket->notes) }}</textarea>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="editNotes = false" :disabled="submittingNotes" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>
                    <button type="submit" :disabled="submittingNotes" class="bg-secondary hover:bg-secondary/80 text-white font-medium px-5 py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center">
                        <span x-show="!submittingNotes" class="inline-flex items-center">
                            <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                            Simpan Perubahan
                        </span>
                        <span x-show="submittingNotes" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- hidden forms for status updates -->
<form id="statusForm" action="{{ route('admin.ticket.updateStatus', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="action" id="statusActionInput">
</form>

<form id="checkInForm" action="{{ route('admin.ticket.checkIn', $ticket->id) }}" method="POST" class="hidden">
    @csrf
</form>

<!-- hidden pdf qr -->
<div class="hidden">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($ticket->code) }}"
        alt="QR Code" id="qrcode-img" crossorigin="anonymous">
    <div id="ticket-data-container"
        data-code="{{ $ticket->code }}"
        data-destination="{{ $ticket->destination->name }}"
        data-cottage="{{ $ticket->cottage->name ?? '' }}"
        data-price="Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}"
        data-customer="{{ $ticket->customer_name }}"
        data-phone="{{ $ticket->customer_phone }}"
        data-visit-date="{{ $ticket->visit_date ? $ticket->visit_date->format('d M Y') : '' }}"
        data-departure-date="{{ $ticket->departure_date ? $ticket->departure_date->format('d M Y') : '' }}">
    </div>
</div>

@include('partials.sweetalert')
@endsection

@push('scripts')
{{-- jsPDF CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('ticket-admin-container');
        if (!container) return;

        const currentPayment = container.dataset.paymentStatus;
        const currentTicket = container.dataset.ticketStatus;

        // ----- Update Status with Radio Buttons -----
        const actionBtn = document.getElementById('actionStatusBtn');
        if (actionBtn) {
            actionBtn.addEventListener('click', function() {
                let html = '';
                if (currentPayment === 'pending') {
                    html += `
                    <div class="flex flex-col gap-2 text-left">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="statusAction" value="mark_paid" class="form-radio text-green-500">
                            <span class="font-medium">Update menjadi PAID (sudah bayar)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="statusAction" value="mark_failed" class="form-radio text-red-500">
                            <span class="font-medium">Update menjad FAILED (gagal bayar)</span>
                        </label>
                    </div>`;
                }
                if (currentPayment === 'paid') {
                    html += `
                    <div class="flex flex-col gap-2 text-left">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="statusAction" value="mark_refunded" class="form-radio text-gray-500">
                            <span class="font-medium">Refund & Cancel Tiket</span>
                        </label>
                    </div>`;
                }
                if (currentPayment === 'failed' || currentPayment === 'refunded') {
                    html += `
                    <div class="flex flex-col gap-2 text-left">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="statusAction" value="return_to_pending" class="form-radio text-blue-500">
                            <span class="font-medium">Kembalikan ke PENDING (tiket kembali aktif)</span>
                        </label>
                    </div>`;
                }

                if (html === '') {
                    Swal.fire('No actions available.');
                    return;
                }

                Swal.fire({
                    title: 'Update Status',
                    html: html,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const selected = document.querySelector('input[name="statusAction"]:checked');
                        if (!selected) {
                            Swal.showValidationMessage('Please select an action');
                            return false;
                        }
                        return selected.value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('statusActionInput').value = result.value;
                        document.getElementById('statusForm').submit();
                    }
                });
            });
        }

        // ----- Check-in Button -----
        const checkInBtn = document.getElementById('checkInBtn');
        if (checkInBtn) {
            checkInBtn.addEventListener('click', function() {
                Swal.fire({
                    text: 'Hanya ubah menjadi checked-in jika customer sudah menggunakan tiket di lokasi. Update menjadi checked-in?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, update',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('checkInForm').submit();
                    }
                });
            });
        }

        // ----- Download PDF Button -----
        document.getElementById('download-pdf-btn').addEventListener('click', generatePDF);

        function generatePDF() {
            const dataContainer = document.getElementById('ticket-data-container');
            const img = document.getElementById('qrcode-img');
            if (!dataContainer || !img) return;

            const code = dataContainer.dataset.code;
            const destination = dataContainer.dataset.destination;
            const price = dataContainer.dataset.price;
            const customer = dataContainer.dataset.customer;
            const phone = dataContainer.dataset.phone;
            const visitDate = dataContainer.dataset.visitDate || '';
            const departureDate = dataContainer.dataset.departureDate || '';
            const cottage = dataContainer.dataset.cottage || '';

            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            const primaryColor = [249, 115, 22];
            const darkColor = [31, 41, 55];
            const mutedColor = [107, 114, 128];
            const lightBg = [249, 250, 251];

            // Top accent bar
            doc.setFillColor(...primaryColor);
            doc.rect(0, 0, 210, 6, 'F');

            // Header
            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(26);
            doc.setTextColor(...primaryColor);
            doc.text('AdminWisata', 15, 22);

            doc.setFont('Helvetica', 'normal');
            doc.setFontSize(11);
            doc.setTextColor(...mutedColor);
            doc.text('E-Ticket & Bukti Pemesanan', 15, 28);

            doc.setDrawColor(229, 231, 235);
            doc.line(15, 33, 195, 33);

            // QR Code (centered, big)
            try {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth || 180;
                canvas.height = img.naturalHeight || 180;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const qrBase64 = canvas.toDataURL('image/png');

                const qrSize = 70;
                const qrX = (210 - qrSize) / 2;
                doc.addImage(qrBase64, 'PNG', qrX, 42, qrSize, qrSize);
            } catch (e) {
                console.error('QR code failed', e);
            }

            // Ticket details card
            const cardY = 120;
            doc.setFillColor(...lightBg);
            doc.roundedRect(15, cardY, 180, 72, 4, 4, 'F');

            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(16);
            doc.setTextColor(...darkColor);
            doc.text('Detail Perjalanan', 20, cardY + 10);

            doc.setDrawColor(209, 213, 219);
            doc.line(20, cardY + 14, 190, cardY + 14);

            const labelX = 20;
            const valueX = 70;
            let y = cardY + 24;

            doc.setFontSize(11);
            const addRow = (label, value, isBold = false) => {
                doc.setFont('Helvetica', 'normal');
                doc.setTextColor(...mutedColor);
                doc.text(label, labelX, y);
                doc.setFont('Helvetica', isBold ? 'bold' : 'normal');
                doc.setTextColor(...darkColor);
                doc.text(value, valueX, y);
                y += 8;
            };

            addRow('Kode Tiket', code, true);
            addRow('Nama', customer);
            addRow('Telepon', phone);
            addRow('Destinasi', destination + (cottage ? ' (' + cottage + ')' : ''), true);
            addRow('Harga', price, true);

            if (visitDate || departureDate) {
                doc.setFont('Helvetica', 'normal');
                doc.setTextColor(...mutedColor);
                doc.text('Tgl. Kunjungan', labelX, y);
                doc.setTextColor(...darkColor);
                doc.text(visitDate || '-', valueX, y);
                doc.setTextColor(...mutedColor);
                doc.text('Tgl. Kepulangan', valueX + 45, y);
                doc.setTextColor(...darkColor);
                doc.text(departureDate || '-', valueX + 90, y);
                y += 8;
            }

            // Validity notice
            const noticeY = cardY + 80;
            doc.setFont('Helvetica', 'italic');
            doc.setFontSize(9);
            doc.setTextColor(...mutedColor);
            doc.text('• Tiket hanya valid ketika pembayaran anda dikonfirmasi oleh Admin.', 20, noticeY);
            doc.text('• Tunjukkan tiket ini ketika sudah berada di lokasi.', 20, noticeY + 5);

            // Footer
            doc.setDrawColor(229, 231, 235);
            doc.line(15, 255, 195, 255);

            doc.setFont('Helvetica', 'oblique');
            doc.setFontSize(9);
            doc.text('Terima kasih telah mempercayakan perjalanan Anda bersama AdminWisata.', 105, 262, {
                align: 'center'
            });
            doc.setFont('Helvetica', 'normal');
            doc.text(`Waktu Cetak otomatis: ${new Date().toLocaleString('id-ID')}`, 105, 267, {
                align: 'center'
            });

            doc.save(`Tiket-AdminWisata-${code}.pdf`);
        }
    });
</script>
@endpush