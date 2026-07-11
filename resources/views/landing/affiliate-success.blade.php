<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Afiliasi Berhasil - {{ config('app.name') }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    @stack('styles')
</head>

<body class="antialiased bg-secondary">

    <section class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-surface rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-10 text-center">

                <!-- header -->
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-600 mb-6">
                    <x-heroicon-o-check-circle class="w-10 h-10" />
                </div>

                <h1 class="text-3xl font-bold text-gray-900 font-jakarta mb-2">Pendaftaran Berhasil!</h1>
                <p class="text-gray-600 mb-8">Selamat, Anda resmi menjadi bagian dari Program Afiliasi.</p>

               <!-- affiliate link -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                    <h3 class="font-semibold text-gray-800 mb-2">Link Afiliasi Anda</h3>
                    <p class="text-xs text-gray-500 mb-3">Bagikan link ini untuk mengundang pelanggan baru</p>
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-3">
                        <code id="affiliate-link" class="flex-1 text-secondary font-jakarta text-sm break-all">
                            {{ url('/?ref=' . $affiliate->code) }}
                        </code>
                        <button onclick="copyLink()" class="flex-shrink-0 bg-secondary hover:bg-secondary/80 text-white p-2 rounded-lg transition" title="Salin link">
                            <x-heroicon-o-clipboard-document class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <!-- data summary -->
                <div id="affiliate-data-container" class="mb-8 text-left bg-gray-50 rounded-xl p-6"
                    data-code="{{ $affiliate->code }}"
                    data-full-name="{{ $affiliate->full_name }}"
                    data-email="{{ $affiliate->email }}"
                    data-phone="{{ $affiliate->phone_number }}"
                    data-channels="{{ $affiliate->promotion_channels ?? '—' }}"
                    data-reason="{{ $affiliate->join_reason ?? '' }}"
                    data-created-at="{{ $affiliate->created_at->translatedFormat('d F Y, H:i') }}">
                    <h3 class="font-semibold text-gray-800 mb-4">Data Pendaftaran Anda</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Nama</span>
                            <p class="font-medium text-gray-800">{{ $affiliate->full_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Email</span>
                            <p class="font-medium text-gray-800">{{ $affiliate->email }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Telepon</span>
                            <p class="font-medium text-gray-800">{{ $affiliate->phone_number }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Media Promosi</span>
                            <p class="font-medium text-gray-800">{{ $affiliate->promotion_channels ?? '—' }}</p>
                        </div>
                    </div>
                    @if ($affiliate->join_reason)
                    <div class="mt-4">
                        <span class="text-gray-500 text-sm">Alasan Bergabung</span>
                        <p class="font-medium text-gray-800 text-sm mt-1">{{ $affiliate->join_reason }}</p>
                    </div>
                    @endif
                </div>

                <!-- warnings -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-left mb-6">
                    <h4 class="font-semibold text-amber-800 flex items-center gap-2">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                        Penting!
                    </h4>
                    <ul class="mt-2 text-sm text-amber-700 space-y-1 list-disc list-inside">
                        <li>Halaman ini hanya <strong>ditampilkan satu kali</strong>. Jika Anda menutupnya, Anda tidak dapat mengaksesnya kembali.</li>
                        <li><strong>Salin</strong> atau <strong>screenshot</strong> link afiliasi di atas sekarang juga.</li>
                        <li>Gunakan link tersebut untuk mulai mendapatkan poin setiap kali pelanggan membeli tiket.</li>
                    </ul>
                </div>

                <!-- manual download -->
                <button id="download-pdf-btn"
                    class="inline-flex items-center bg-secondary hover:bg-secondary/80 text-white font-semibold px-6 py-3 rounded-lg transition mb-6">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                    Download bukti
                </button>

                <!-- back to home -->
                <a href="{{ url('/') }}" class="inline-flex items-center bg-white border border-secondary text-secondary hover:bg-secondary hover:text-white font-semibold px-6 py-3 rounded-lg transition">
                    Kembali ke Beranda
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>
        </div>
    </section>

    <script>
        function copyLink() {
            const link = document.getElementById('affiliate-link').innerText;
            navigator.clipboard.writeText(link).then(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Link disalin!',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    alert('Link berhasil disalin!');
                }
            });
        }

        // PDF generation
        window.addEventListener('load', function() {
            setTimeout(generatePDF, 600);
        });

        document.getElementById('download-pdf-btn').addEventListener('click', function() {
            generatePDF();
        });

        function generatePDF() {
            const container = document.getElementById('affiliate-data-container');
            if (!container) return;

            const code = container.dataset.code;
            const fullName = container.dataset.fullName;
            const email = container.dataset.email;
            const phone = container.dataset.phone;
            const channels = container.dataset.channels;
            const reason = container.dataset.reason;
            const createdAt = container.dataset.createdAt;

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
            doc.text('Bukti Pendaftaran Afiliasi', 15, 28);

            doc.setDrawColor(229, 231, 235);
            doc.line(15, 33, 195, 33);

            // Affiliate badge (instead of QR)
            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(18);
            doc.setTextColor(...primaryColor);
            doc.text('AFILIASI', 105, 50, {
                align: 'center'
            });

            // Data card
            const cardY = 60;
            doc.setFillColor(...lightBg);
            doc.roundedRect(15, cardY, 180, 90, 4, 4, 'F');

            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(16);
            doc.setTextColor(...darkColor);
            doc.text('Data Pendaftaran', 20, cardY + 10);

            doc.setDrawColor(209, 213, 219);
            doc.line(20, cardY + 14, 190, cardY + 14);

            const labelX = 20;
            const valueX = 70;
            let y = cardY + 24;

            doc.setFontSize(11);
            const addRow = (label, value, bold = false) => {
                doc.setFont('Helvetica', 'normal');
                doc.setTextColor(...mutedColor);
                doc.text(label, labelX, y);
                doc.setFont('Helvetica', bold ? 'bold' : 'normal');
                doc.setTextColor(...darkColor);
                doc.text(value, valueX, y);
                y += 8;
            };

            addRow('Kode Afiliasi', code, true);
            addRow('Nama', fullName);
            addRow('Email', email);
            addRow('Telepon', phone);
            addRow('Media Promosi', channels);
            if (reason) {
                addRow('Alasan', reason);
            }

            // Footer
            doc.setDrawColor(229, 231, 235);
            doc.line(15, 200, 195, 200);

            doc.setFont('Helvetica', 'oblique');
            doc.setFontSize(9);
            doc.text('Terima kasih telah bergabung dengan Program Afiliasi AdminWisata.', 105, 208, {
                align: 'center'
            });
            doc.setFont('Helvetica', 'normal');
            doc.text(`Didaftarkan pada: ${createdAt}`, 105, 213, {
                align: 'center'
            });

            doc.save(`Afiliasi-AdminWisata-${code}.pdf`);
        }
    </script>

</body>

</html>