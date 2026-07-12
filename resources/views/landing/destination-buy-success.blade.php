<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Pemesanan Tiket Berhasil - {{ config('app.name') }}</title>

    @stack('styles')
</head>

<body class="antialiased bg-secondary">
    <div class="py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- card -->
        <div class="bg-surface rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-10 text-center">

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-600 mb-6">
                <x-heroicon-o-check-circle class="w-10 h-10" />
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-jakarta mb-2">Pemesanan Berhasil!</h1>
            <p class="text-gray-600 mb-8">Lakukan pembayaran via whatsapp. Silakan simpan tiket ini segera.</p>

            <!-- QR code -->
            <div class="mb-8">
                <p class="text-sm text-gray-500 mb-3">QR Code Tiket (hanya valid setelah pembayaran berhasil)</p>
                <div id="qrcode-container" class="inline-block bg-white p-2 rounded-xl shadow-sm border">
                    <canvas id="qrcode-canvas" width="180" height="180"></canvas>
                </div>
            </div>

            <!-- ticket details -->
            <div id="ticket-data-container" class="mb-6"
                data-code="{{ $ticket->code }}"
                data-destination="{{ $ticket->destination->name }}"
                data-price="Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}"
                data-price-raw="{{ $ticket->ticket_price }}"
                data-customer="{{ $ticket->customer_name }}"
                data-phone="{{ $ticket->customer_phone }}"
                data-visit-date="{{ $ticket->visit_date ? $ticket->visit_date->format('d M Y') : '' }}"
                data-departure-date="{{ $ticket->departure_date ? $ticket->departure_date->format('d M Y') : '' }}"
                data-cottage="{{ $ticket->cottage->name ?? '' }}">

                <div class="bg-gray-50 rounded-xl px-4 py-3 mb-3 {{ $ticket->ticket_price > 0 ? 'flex items-center justify-between' : 'text-center' }}">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Kode Tiket</p>
                        <p class="font-jakarta font-bold text-secondary text-lg">{{ $ticket->code }}</p>
                    </div>
                    @if($ticket->ticket_price > 0)
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                        <p class="font-jakarta font-bold text-gray-900 text-lg">Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Data Pemesan</p>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-800">{{ $ticket->customer_name }}</p>
                            <p class="text-sm text-gray-600">{{ $ticket->customer_phone }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Detail Perjalanan</p>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-800">
                                {{ $ticket->destination->name }}
                                @if($ticket->cottage)
                                ({{ $ticket->cottage->name }})
                                @endif
                            </p> @if ($ticket->visit_date || $ticket->departure_date)
                            <p class="text-sm text-gray-600">
                                {{ $ticket->visit_date?->format('d M Y') ?? '' }}
                                {{ $ticket->departure_date ? ' → ' . $ticket->departure_date->format('d M Y') : '' }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- whatsapp payment cta -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-left mb-6">
                <h4 class="font-jakarta font-semibold text-amber-800 flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                    Pembayaran via WhatsApp
                </h4>
                <p class="mt-2 text-sm text-amber-700">
                    Agar tiket valid digunakan, lanjutkan pembayaran dengan menghubungi kami di WhatsApp. Gunakan kode tiket di atas sebagai referensi.
                </p>
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20AdminWisata%2C%20saya%20ingin%20melakukan%20pembayaran%20untuk%20tiket%20{{ urlencode($ticket->code)}}%20-%20{{urlencode($ticket->destination->name)}}"
                    target="_blank"
                    class="inline-flex items-center mt-4 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-lg transition font-poppins">
                    Bayar via WhatsApp
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>

            <!-- download warning -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 text-left mb-6">
                <h4 class="font-jakarta font-semibold text-red-800 flex items-center gap-2 mb-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                    Penting! Halaman ini hanya tampil satu kali.
                </h4>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1 font-nunito">
                    <li>Segera <strong>simpan PDF tiket</strong> atau screenshot halaman ini.</li>
                    <li>Jika Anda menutup halaman ini, Anda <strong>tidak dapat mengaksesnya kembali</strong>.</li>
                    <li>PDF tiket akan otomatis terunduh. Jika tidak, klik tombol di bawah.</li>
                </ul>
            </div>

            <!-- action buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                <button id="download-pdf-btn"
                    class="inline-flex items-center justify-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-6 py-3 rounded-lg transition font-poppins">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                    Unduh Tiket (PDF)
                </button>
                <button id="back-home-btn"
                    onclick="confirmBackHome()"
                    class="inline-flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-lg transition font-poppins">
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                    Kembali
                </button>
            </div>
        </div>
    </div>

    @include('partials.sweetalert')

    <script>
        //generate qr code
        document.addEventListener('DOMContentLoaded', async function() {
            const container = document.getElementById('ticket-data-container');
            const canvas = document.getElementById('qrcode-canvas');
            if (container && canvas) {
                const code = container.dataset.code;
                try {
                    const qrDataUrl = await window.generateQRDataURL(code);
                    const ctx = canvas.getContext('2d');
                    const img = new Image();
                    img.onload = () => {
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    };
                    img.src = qrDataUrl;
                } catch (e) {
                    console.error('QR generation failed:', e);
                }
            }
        });

        //pdf ticket download
        document.getElementById('download-pdf-btn').addEventListener('click', function() {
            const container = document.getElementById('ticket-data-container');
            if (!container) return;

            const ticketData = {
                code: container.dataset.code,
                destination: container.dataset.destination,
                price: container.dataset.price,
                priceRaw: parseFloat(container.dataset.priceRaw) || 0,
                customer: container.dataset.customer,
                phone: container.dataset.phone,
                visitDate: container.dataset.visitDate || '',
                departureDate: container.dataset.departureDate || '',
                cottage: container.dataset.cottage || ''
            };

            window.downloadTicketPDF(ticketData).catch(err => {
                console.error('PDF generation failed:', err);
                Swal.fire({
                    icon: 'error',
                    text: 'Tidak dapat membuat PDF. Silakan coba lagi.'
                });
            });
        });

        //confirm back home
        function confirmBackHome() {
            Swal.fire({
                text: 'Pastikan anda sudah menyimpan informasi tiket anda sebelum meninggalkan halaman ini!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sudah, kembali',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/';
                }
            });
        }
    </script>

</body>

</html>