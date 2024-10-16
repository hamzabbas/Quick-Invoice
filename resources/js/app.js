import '../sass/app.scss';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.css';
// import $ from 'jquery';
import jQuery from 'jquery';
window.$ = jQuery;
// window.$ = window.jQuery = $;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import 'jszip';
import 'pdfmake';
import 'datatables.net';
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
import 'datatables.net-buttons';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.css';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-responsive-bs5';



$('#send-invoice').on('click', function() {
    var selectedInvoices = [];
    console.log("clicked")

    $('.invoice-checkbox:checked').each(function() {
        selectedInvoices.push($(this).data('id')); // Get the invoice ID
    });

    if (selectedInvoices.length > 0) {
        $('#selected_invoices').val(JSON.stringify(selectedInvoices)); // Store as JSON
        $('#invoice-form').attr('action', '{{ route("send.invoices") }}'); // Change action to email route
        $('#invoice-form').submit(); // Submit the form
    } else {
        alert('Please select at least one invoice.');
    }
});



