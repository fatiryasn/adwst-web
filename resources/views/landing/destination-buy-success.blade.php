<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Pemesanan Tiket Berhasil - {{ config('app.name') }}</title>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($ticket->code) }}"
                        alt="QR Code Tiket"
                        class="w-44 h-44"
                        id="qrcode-img"
                        crossorigin="anonymous">
                </div>
            </div>

            <!-- ticket details -->
            <div id="ticket-data-container" class="mb-6"
                data-code="{{ $ticket->code }}"
                data-destination="{{ $ticket->destination->name }}"
                data-price="Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}"
                data-customer="{{ $ticket->customer_name }}"
                data-phone="{{ $ticket->customer_phone }}"
                data-visit-date="{{ $ticket->visit_date ? $ticket->visit_date->format('d M Y') : '' }}"
                data-departure-date="{{ $ticket->departure_date ? $ticket->departure_date->format('d M Y') : '' }}"
                data-cottage="{{ $ticket->cottage->name ?? '' }}">

                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3 mb-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Kode Tiket</p>
                        <p class="font-jakarta font-bold text-secondary text-lg">{{ $ticket->code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                        <p class="font-jakarta font-bold text-gray-900 text-lg">Rp {{ number_format($ticket->ticket_price, 0, ',', '.') }}</p>
                    </div>
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

            <!-- download button -->
            <button id="download-pdf-btn"
                class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-6 py-3 rounded-lg transition font-poppins">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                Unduh Tiket (PDF)
            </button>
        </div>
    </div>

    @include('partials.sweetalert')

    <script>
        document.getElementById('download-pdf-btn').addEventListener('click', function() {
            generatePDF();
        });

        function generatePDF() {
            const container = document.getElementById('ticket-data-container');
            const img = document.getElementById('qrcode-img');
            if (!container || !img) return;

            const code = container.dataset.code;
            const destination = container.dataset.destination;
            const price = container.dataset.price;
            const customer = container.dataset.customer;
            const phone = container.dataset.phone;
            const visitDate = container.dataset.visitDate || '';
            const departureDate = container.dataset.departureDate || '';
            const cottage = container.dataset.cottage || '';

            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            // Colors
            const primaryColor = [249, 115, 22]; // orange
            const darkColor = [31, 41, 55]; // gray-800
            const mutedColor = [107, 114, 128]; // gray-500
            const lightBg = [249, 250, 251]; // gray-50

            // ── TOP ACCENT BAR ──
            doc.setFillColor(...primaryColor);
            doc.rect(0, 0, 210, 6, 'F');

            // ── HEADER ──
            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(26);
            doc.setTextColor(...primaryColor);
            doc.text('AdminWisata', 15, 22);

            doc.setFont('Helvetica', 'normal');
            doc.setFontSize(11);
            doc.setTextColor(...mutedColor);
            doc.text('E-Ticket & Bukti Pemesanan', 15, 28);

            // thin line below header
            doc.setDrawColor(229, 231, 235);
            doc.line(15, 33, 195, 33);

            // ── QR CODE (centered, big) ──
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

            // ── TICKET DETAILS (modern card) ──
            const cardY = 120;
            doc.setFillColor(...lightBg);
            doc.roundedRect(15, cardY, 180, 72, 4, 4, 'F'); // soft background

            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(16);
            doc.setTextColor(...darkColor);
            doc.text('Detail Perjalanan', 20, cardY + 10);

            // Horizontal line inside card
            doc.setDrawColor(209, 213, 219);
            doc.line(20, cardY + 14, 190, cardY + 14);

            // Left column labels
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

            // Visit / Departure dates on same line if both exist
            if (visitDate || departureDate) {
                doc.setFont('Helvetica', 'normal');
                doc.setTextColor(...mutedColor);
                doc.text('Tgl. Kunjungan', labelX, y);
                doc.setTextColor(...darkColor);
                doc.text(visitDate || '-', valueX, y);
                // departure beside
                doc.setTextColor(...mutedColor);
                doc.text('Tgl. Kepulangan', valueX + 45, y);
                doc.setTextColor(...darkColor);
                doc.text(departureDate || '-', valueX + 90, y);
                y += 8;
            }

            // ── VALIDITY NOTICE ──
            const noticeY = cardY + 80;
            doc.setFont('Helvetica', 'italic');
            doc.setFontSize(9);
            doc.setTextColor(...mutedColor);
            doc.text(
                '• Tiket hanya valid ketika pembayaran anda dikonfirmasi oleh Admin.',
                20, noticeY
            );
            doc.text(
                '• Tunjukkan tiket ini ketika sudah berada di lokasi.',
                20, noticeY + 5
            );

            // ── FOOTER ──
            doc.setDrawColor(229, 231, 235);
            doc.line(15, 255, 195, 255);

            doc.setFont('Helvetica', 'oblique');
            doc.setFontSize(9);
            doc.text(
                'Terima kasih telah mempercayakan perjalanan Anda bersama AdminWisata.',
                105, 262, {
                    align: 'center'
                }
            );
            doc.setFont('Helvetica', 'normal');
            doc.text(
                `Waktu Cetak otomatis: ${new Date().toLocaleString('id-ID')}`,
                105, 267, {
                    align: 'center'
                }
            );

            // ── SAVE ──
            doc.save(`Tiket-AdminWisata-${code}.pdf`);
        }
    </script>

</body>

</html>