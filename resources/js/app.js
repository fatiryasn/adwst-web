import Alpine from "alpinejs";
import Swal from "sweetalert2";
import { generateQRDataURL, downloadTicketPDF } from "./ticket";

window.Alpine = Alpine;
window.Swal = Swal;

window.generateQRDataURL = generateQRDataURL;
window.downloadTicketPDF = downloadTicketPDF;

Alpine.start();
