<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Pemesanan Tiket Berhasil - {{ config('app.name') }}</title>

    @stack('styles')
</head>

<body class="antialiased bg-secondary font-poppins">
    <div class="py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- card -->
        <div class="bg-surface rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-10 text-center">

            <!-- header -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-600 mb-6">
                <x-heroicon-o-check-circle class="w-10 h-10" />
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-jakarta mb-2">Pemesanan Berhasil!</h1>
            <p class="text-gray-600 mb-8 text-sm md:text-base">Lakukan pembayaran via whatsapp</p>

            <!-- detail pemesanan -->
            <div class="bg-gray-50 rounded-xl px-1 md:px-4 py-4 text-left mb-6">
                <p class="text-xs text-gray-600 uppercase font-jakarta tracking-wide mb-3">Detail Pemesanan</p>

                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Pemesan :</span>
                        <span class="font-medium text-gray-800 ml-1">
                            {{ $ticket->customer_name }} ({{ $ticket->customer_phone }})
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Destinasi :</span>
                        <span class="font-medium text-gray-800 ml-1">
                            {{ $ticket->destination->name }}
                            @if($ticket->cottage)
                            ({{ $ticket->cottage->name }})
                            @endif
                        </span>
                    </div>
                    @if ($ticket->visit_date || $ticket->departure_date)
                    <div>
                        <span class="text-gray-600">Tanggal :</span>
                        <span class="font-medium text-gray-800 ml-1">
                            {{ $ticket->visit_date?->format('d M Y') ?? '' }}
                            {{ $ticket->departure_date ? ' → ' . $ticket->departure_date->format('d M Y') : '' }}
                        </span>
                    </div>
                    @endif

                    @if ($ticket->customer_destination_detail)
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <span class="text-gray-600 block mb-1 uppercase text-xs font-jakarta tracking-wide">Detail Perjalanan</span>
                        <p id="destination-detail-text" class="text-gray-900 cursor-pointer whitespace-pre-line"
                            data-full-detail="{{ addslashes($ticket->customer_destination_detail) }}">
                            {{ Str::limit($ticket->customer_destination_detail, 300) }}
                            @if (strlen($ticket->customer_destination_detail) > 300)
                            <span class="text-secondary font-semibold text-xs cursor-pointer hover:underline ml-1">
                                (lihat lebih lengkap)
                            </span>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- whatsapp payment cta -->
            @php
            $destName = $ticket->destination->name;
            if ($ticket->cottage) {
            $destName .= ' (' . $ticket->cottage->name . ')';
            }

            $dateDisplay = '';
            $visit = $ticket->visit_date;
            $departure = $ticket->departure_date;
            if ($visit && $departure) {
            $dateDisplay = ($visit->format('Y-m-d') === $departure->format('Y-m-d'))
            ? $visit->format('d M Y')
            : $visit->format('d M Y') . ' → ' . $departure->format('d M Y');
            } elseif ($visit) {
            $dateDisplay = $visit->format('d M Y');
            } elseif ($departure) {
            $dateDisplay = $departure->format('d M Y');
            }

            $orderTime = $ticket->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i');

            $message = "Halo AdminWisata, saya ingin melakukan pembayaran untuk tiket:\n";
            $message .= "• Pemesan: {$ticket->customer_name} ({$ticket->customer_phone})\n";
            $message .= "• Destinasi: {$destName}" . ($dateDisplay ? " - {$dateDisplay}" : '') . "\n";
            $message .= "• Waktu Pemesanan: {$orderTime}";

            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . rawurlencode($message);
            @endphp

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-left mb-6">
                <h4 class="font-jakarta font-semibold text-amber-800 flex items-center gap-2 text-sm md:text-base">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                    Pembayaran via WhatsApp
                </h4>
                <p class="mt-2 text-xs md:text-sm text-amber-700">
                    Lakukan pembayaran via whatsapp untuk mendapatkan tiket anda.
                </p>

                <div class="flex items-center gap-2 text-sm text-green-600 mt-4">
                    <x-heroicon-o-phone class="w-4 h-4" />
                    <span class="font-medium">+{{ $whatsappNumber }}</span>
                </div>

                <a href="{{ $whatsappUrl }}"
                    target="_blank"
                    class="inline-flex items-center mt-1.5 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-lg transition font-poppins text-sm md:text-base">
                    Bayar via WhatsApp
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>

            <!-- warning -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 text-left mb-6">
                <h4 class="font-jakarta font-semibold text-red-800 flex items-center gap-2 mb-2 text-sm md:text-base">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                    Penting! Halaman ini hanya tampil satu kali.
                </h4>
                <ul class="list-disc list-inside text-xs md:text-sm text-red-700 space-y-1 font-nunito">
                    <li>Segera <strong>catat atau screenshot</strong> informasi tiket Anda.</li>
                    <li>Jika Anda menutup halaman ini, Anda <strong>tidak dapat mengaksesnya kembali</strong>.</li>
                </ul>
            </div>

            <!-- action buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
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
        //toggle destination detail
        document.addEventListener('DOMContentLoaded', function() {
            const detailText = document.getElementById('destination-detail-text');
            if (detailText) {
                const fullText = detailText.dataset.fullDetail;
                if (fullText && fullText.length > 300) {
                    detailText.addEventListener('click', function() {
                        if (detailText.textContent.includes('(lihat lebih lengkap)')) {
                            detailText.innerHTML = fullText;
                        } else {
                            detailText.innerHTML = fullText.substring(0, 300) +
                                ' <span class="text-secondary font-semibold text-xs cursor-pointer hover:underline ml-1">(lihat lebih lengkap)</span>';
                        }
                    });
                }
            }
        });

        //confirm return
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