import jsPDF from "jspdf";
import QRCode from "qrcode";

//generate qr code
export function generateQRDataURL(text, size = 180) {
    return new Promise((resolve, reject) => {
        QRCode.toDataURL(
            text,
            {
                width: size,
                margin: 2,
                color: {
                    dark: "#000000",
                    light: "#ffffff",
                },
            },
            (err, url) => {
                if (err) reject(err);
                else resolve(url);
            },
        );
    });
}

//generate ticket pdf
export async function downloadTicketPDF(data) {
    const doc = new jsPDF("p", "mm", "a4");

    //colors
    const primaryColor = [249, 115, 22]; 
    const darkColor = [31, 41, 55]; 
    const mutedColor = [107, 114, 128];
    const lightBg = [249, 250, 251];

    //top accent bar
    doc.setFillColor(...primaryColor);
    doc.rect(0, 0, 210, 6, "F");

    //header
    doc.setFont("Helvetica", "bold");
    doc.setFontSize(26);
    doc.setTextColor(...primaryColor);
    doc.text("AdminWisata", 15, 22);

    doc.setFont("Helvetica", "normal");
    doc.setFontSize(11);
    doc.setTextColor(...mutedColor);
    doc.text("E-Ticket & Bukti Pemesanan", 15, 28);

    doc.setDrawColor(229, 231, 235);
    doc.line(15, 33, 195, 33);

    //QR code
    try {
        const qrDataUrl = await generateQRDataURL(data.code);
        const qrSize = 70;
        const qrX = (210 - qrSize) / 2;
        doc.addImage(qrDataUrl, "PNG", qrX, 42, qrSize, qrSize);
    } catch (e) {
        console.error("QR generation failed:", e);
    }

    //ticket detail
    const cardY = 120;
    doc.setFillColor(...lightBg);
    doc.roundedRect(15, cardY, 180, 72, 4, 4, "F");

    doc.setFont("Helvetica", "bold");
    doc.setFontSize(16);
    doc.setTextColor(...darkColor);
    doc.text("Detail Perjalanan", 20, cardY + 10);

    doc.setDrawColor(209, 213, 219);
    doc.line(20, cardY + 14, 190, cardY + 14);

    const labelX = 20;
    const valueX = 70;
    let y = cardY + 24;

    doc.setFontSize(11);
    const addRow = (label, value, isBold = false) => {
        doc.setFont("Helvetica", "normal");
        doc.setTextColor(...mutedColor);
        doc.text(label, labelX, y);
        doc.setFont("Helvetica", isBold ? "bold" : "normal");
        doc.setTextColor(...darkColor);
        doc.text(value, valueX, y);
        y += 8;
    };

    addRow("Kode Tiket", data.code, true);
    addRow("Nama", data.customer);
    addRow("Telepon", data.phone);
    addRow(
        "Destinasi",
        data.destination + (data.cottage ? " (" + data.cottage + ")" : ""),
        true,
    );
    if (data.priceRaw > 0) {
        addRow("Harga", data.price, true);
    }
    if (data.visitDate || data.departureDate) {
        doc.setFont("Helvetica", "normal");
        doc.setTextColor(...mutedColor);
        doc.text("Tgl. Kunjungan", labelX, y);
        doc.setTextColor(...darkColor);
        doc.text(data.visitDate || "-", valueX, y);
        doc.setTextColor(...mutedColor);
        doc.text("Tgl. Kepulangan", valueX + 45, y);
        doc.setTextColor(...darkColor);
        doc.text(data.departureDate || "-", valueX + 90, y);
        y += 8;
    }

    //validity notice
    const noticeY = cardY + 80;
    doc.setFont("Helvetica", "italic");
    doc.setFontSize(9);
    doc.setTextColor(...mutedColor);
    doc.text(
        "• Tiket hanya valid ketika pembayaran anda dikonfirmasi oleh Admin.",
        20,
        noticeY,
    );
    doc.text(
        "• Tunjukkan tiket ini ketika sudah berada di lokasi.",
        20,
        noticeY + 5,
    );

    //footer
    doc.setDrawColor(229, 231, 235);
    doc.line(15, 255, 195, 255);

    doc.setFont("Helvetica", "oblique");
    doc.setFontSize(9);
    doc.text(
        "Terima kasih telah mempercayakan perjalanan Anda bersama AdminWisata.",
        105,
        262,
        { align: "center" },
    );
    doc.setFont("Helvetica", "normal");
    doc.text(
        `Waktu Cetak otomatis: ${new Date().toLocaleString("id-ID")}`,
        105,
        267,
        { align: "center" },
    );

    //save
    doc.save(`Tiket-AdminWisata-${data.code}.pdf`);
}
