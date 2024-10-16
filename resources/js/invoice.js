$('#send-email').on('click', function() {
    var selectedInvoices = [];
    console.log("clicked");

    $('.invoice-checkbox:checked').each(function() {
        selectedInvoices.push($(this).data('id')); // Get the invoice ID
    });

    if (selectedInvoices.length > 0) {
        $('#selected_invoices').val(JSON.stringify(selectedInvoices)); // Store as JSON
        $('#invoice-form').attr('action', '{{ route("send.email") }}'); // Change action to send email route
        $('#invoice-form').submit(); // Submit the form
    } else {
        alert('No invoices to send.'); // Alert if no invoices are selected
    }
});