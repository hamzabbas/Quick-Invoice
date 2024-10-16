{{-- @extends('welcome') --}}

{{-- @section('content') --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="{{ asset('js/invoice.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DateTime Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <div class="pb-[40px]">
            <div class="container-fluid ">
            @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
    
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary mt-4 ms-2" data-toggle="modal" data-target="#exampleModal">
  Upload File
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CSV File Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div >
         
          
            <form class="container-fluid" method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Upload only CSV File:</label>
                    <input type="file" class="form-control-file" id="csv_file" name="csv_file">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
     

      </div>
    
    </div>
  </div>
</div> 
<!-- Add Payer Email Button -->
<button type="button" class="btn btn-outline-secondary  mt-4 ms-2" data-toggle="modal" data-target="#addEmailModal">
    Add Payer Email
</button>

<!-- Modal Structure -->
<div class="modal fade mt-4 pt-5" id="addEmailModal" tabindex="-1" role="dialog" aria-labelledby="addEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

    <form action="{{ route('add.payer.email') }}" method="post" id="addEmailForm" class="mb-0">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add/Edit Payer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <div class="modal-body">
            <!-- Hidden field for payer ID (used for editing) -->
            <input type="hidden" id="payer_id" name="payer_id">

            <div class="form-group">
                <label for="payer">Payer Name</label>
                <input type="text" class="form-control" style="text-transform: uppercase;" id="payer" name="payer" required >
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required >
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Payer</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

       
    </div>
</form>

<div class="modal-content p-4">
            <h5>Current Payer Emails</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Payer Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payers as $payer)
                    <tr>
                        <td>{{ $payer->name }}</td>
                        <td>{{ $payer->email }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-success edit-payer" data-id="{{ $payer->id }}" data-name="{{ $payer->name }}" data-email="{{ $payer->email }}" title="Edit this payer"><i class="fa fa-edit"></i></button>
                            <form action="{{ route('delete.payer.email', $payer->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Delete this payer"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
  
  </div>
</div>
<script>
    document.querySelectorAll('.edit-payer').forEach(button => {
        button.addEventListener('click', function() {
            const payerId = this.getAttribute('data-id');
            const payerName = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');

            // Set the form fields with existing payer data
            document.getElementById('payer_id').value = payerId;
            document.getElementById('payer').value = payerName;
            document.getElementById('email').value = email;
        });
    });
</script>

<!-- Add Payer Email Button -->
<button type="button" class="btn btn-outline-success  mt-4 ms-2" data-toggle="modal" data-target="#sendInvoice">
  Send Invoice
</button>

<!-- Modal Structure -->
<div class="modal fade mt-4 pt-5" id="sendInvoice" tabindex="-1" role="dialog" aria-labelledby="addEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{ route('send.invoices') }}" method="POST" id="invoice-form">
      @csrf
      <input type="hidden" name="selected_invoices" id="selected_invoices">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Send Invoices</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <!-- Payer Dropdown -->
          <!-- <div class="form-group">
            <label for="payer-invoice">Select Payer</label>
            <select class="form-control" id="payer-invoice" name="payer">
            <option value="" selected disabled>Select Payer</option>
           
              <option value="MAG">MAG</option>
              <option value="IRCC">IRCC</option>
              <option value="CBSA">CBSA</option>
              <option value="IRB">IRB</option>
              <option value="CIC">CIC</option>
           
            </select>
          </div> -->
          <div class="form-group">
                <label for="payer-invoice">Select Payer</label>
                <select class="form-control" id="payer-invoice" name="payer">
                    <option value="" selected disabled>Select Payer</option>
                </select>
            </div>

          
          <div class="form-group">
            <label for="send-invoice">Select Invoices</label>
            <select class="form-control" id="send-invoice" name="send-invoice">
            <option value="" selected disabled>Select Invoices</option>
           
              <option value="multiple">MULTIPLE</option>
              <option value="single">SINGLE</option>
        
            </select>
          </div>
        <ul class="ul">
         <!-- show selected invoices mutlple/ single  -->
        </ul>

        </div>
        
        <div class="modal-footer">  
          <button type="submit" class="btn btn-outline-primary" id="view-selected-invoices">View Selected Invoices</button>
          <button type="button" id="send-email" class="btn btn-outline-primary">Send Invoices to Email</button>
          <button type="button" id="generate-pdf" class="btn btn-outline-primary">Generate PDF</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
        
      </div>
    </form>

  
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function() {
        // Set up CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#payer-invoice, #send-invoice').on('change', function() {
            var payer = $('#payer-invoice').val(); // Get the selected payer
            var invoiceType = $('#send-invoice').val(); // Get the selected invoice type

            console.log('Sending request with:', { payer: payer, type: invoiceType });

            if (payer && invoiceType) {
                $.ajax({
                    url: '/fetch-invoices', 
                    type: 'GET',
                    data: {
                        payer: payer,
                        type: invoiceType
                    },
                    success: function(response) {
                        console.log('Response:', response);
                        $('.ul').empty();

                        
                        if (response.invoicess && response.invoicess.length) {
                       
                            response.invoicess.forEach(function(invoice) {
                                // $('.ul').append('<li>' + invoice.my_code + ' - ' + invoice.booking_date + '</li>');
                                $('.ul').append(
                                '<li class=" ' + (invoice.last_email_sent_at ? 'email-sent' : 'not-sent') + '">' +
                                '<input type="checkbox"  class="invoice-checkbox form-check-input '+ (invoice.last_email_sent_at ? 'email-sent' : 'not-sent') + ' " data-id="' + invoice.id + '" value="' + invoice.my_code + '">' +
                                invoice.my_code + ' - ' + invoice.booking_date + ' - ' + invoice.id +
                                '</li>'
                            );
                            });
                        } else {
                            $('.ul').append('<li>No invoices found.</li>');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        alert('Failed to fetch invoices. Please try again.');
                    }
                });
            }
        });
              
        $('#view-selected-invoices').on('click', function() {
        var selectedInvoices = [];

        $('.invoice-checkbox:checked').each(function() {
            selectedInvoices.push($(this).data('id')); // Get the invoice ID
        });
        console.log('Selected Invoice IDs:', selectedInvoices); 

        if (selectedInvoices.length > 0) {
            // Set selected IDs in hidden input
            $('#selected_invoices').val(JSON.stringify(selectedInvoices)); // Store as JSON
            $('#invoice-form').submit(); // Submit the form
        } else {
            alert('Please select at least one invoice.');
        }
    });


        $('#generate-pdf').on('click', function() {
            var selectedInvoices = [];

            $('.invoice-checkbox:checked').each(function() {
                selectedInvoices.push($(this).data('id')); // Get the invoice ID
            });

            if (selectedInvoices.length > 0) {
                $('#selected_invoices').val(JSON.stringify(selectedInvoices)); // Store as JSON
                $('#invoice-form').attr('action', '{{ route("generate.invoice.pdf") }}'); // Change action to PDF route
                $('#invoice-form').submit(); // Submit the form
            } else {
                alert('Please select at least one invoice.');
            }
        });

            $('#send-email').on('click', function() {
                var selectedInvoices = [];

                $('.invoice-checkbox:checked').each(function() {
                    selectedInvoices.push($(this).data('id')); // Get the invoice ID
                });

                if (selectedInvoices.length > 0) {
                    $.ajax({
                        url: '/send-selected-invoices-email',
                        type: 'POST',
                        data: {
                            selected_invoices: JSON.stringify(selectedInvoices), // Send selected invoice IDs as JSON
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            alert(response.message); // Show success message from backend
                        },
                        error: function(xhr) {
                            console.error('AJAX Error:', xhr);
                            var errorMessage = 'Failed to send email. Please try again.';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            alert(errorMessage);
                        }
                    });
                } else {
                    alert('Please select at least one invoice to send.');
                }
            });

            $.ajax({
            url: '{{ route('get.payers') }}',
            type: 'GET',
            success: function(response) {
                let payerDropdown = $('#payer-invoice');
                payerDropdown.empty(); // Clear existing options

                payerDropdown.append('<option disabled selected>Select Payer</option>');
                
                // Loop through the response data and add options to the dropdown
                $.each(response, function(key, payer) {
                    payerDropdown.append('<option value="' + payer.name + '">' + payer.name + '</option>');
                });
            },
            error: function() {
                console.error('Error fetching payers');
            }
        });

    //     $('#invoice-form').on('submit', function(e) {
    //     e.preventDefault(); // Prevent form submission and page reload

    //     let selectedInvoices = [];
    //     $('.invoice-checkbox:checked').each(function() {
    //         selectedInvoices.push($(this).val());
    //     });

    //     console.log('Selected invoices:', selectedInvoices);
    //     // Proceed with the selected invoices (e.g., send them via AJAX or perform other actions)
    // });
        //     $('#submit-button').on('click', function(e) {
        //     e.preventDefault(); // Prevent default form submission

        //     var selectedInvoices = [];
        //     $('.invoice-checkbox:checked').each(function() {
        //         selectedInvoices.push($(this).val()); // Collect all selected invoices
        //     });

        //     if (selectedInvoices.length > 0) {
        //         $.ajax({
        //             url: '/send-invoices',
        //             type: 'POST',
        //             data: {
        //                 _token: $('input[name="_token"]').val(), // CSRF Token
        //                 payer: $('#payer-invoice').val(),
        //                 invoices: selectedInvoices // Send selected invoices
        //             },
        //             success: function(response) {
        //                 // Show the response (invoice view)
        //                 console.log(response);
        //                 // $('#invoice-display').html(response); // Display the invoice view in a div or modal
        //             },
        //             error: function(xhr) {
        //                 console.error('AJAX Error:', xhr);
        //                 alert('Failed to send invoices. Please try again.');
        //             }
        //         });
        //     } else {
        //         alert('Please select at least one invoice.');
        //     }
        // });


    });

</script>

<div id="invoice-display"></div>

<table border="0" cellspacing="5" cellpadding="5" id="date-filtered-table">
        <tbody style="display:flex";><tr>
            
            <td><input type="text" id="min" name="min" placeholder="Start Date:"></td>
        </tr>
        <tr>
            
            <td><input type="text" id="max" name="max" placeholder="End Date:"></td>
        </tr>
    </tbody>
    
</table>
         @if (isset($invoices))
                    <div class="container-fluid mt-5">
                        <div class="d-flex w-100 justify-content-between align-items-center ">
                            <h2>Invoices</h2>
                            <div class="d-flex gap-2">
                                <form action="{{ route('create_invoice_form') }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Add New Invoice</button>
                                </form>
                            </div>
                        </div>
                        @if (session('success'))
                            <div id="alert-message" class="alert alert-success fade show" role="alert">
                                <span>{{ session('success') }}</span>
                                <span class="float-end" aria-hidden="true" role="button">&times;</span>
                            </div>
                            <script>
                                setTimeout(function() {
                                    $('#alert-message').fadeOut('fast');
                                }, 3000); // 3 seconds
                            </script>
                        @endif
                        @if (session('error'))
                            <div id="alert-message" class="alert alert-danger fade show" role="alert">
                                <span>{{ session('error') }}</span>
                                <span class="float-end" aria-hidden="true" role="button">&times;</span>
                            </div>
                            <script>
                                setTimeout(function() {
                                    $('#alert-message').fadeOut('fast');
                                }, 3000); // 3 seconds
                            </script>
                        @endif
                        <table class="table table-bordered table-striped" id="invoice_table">
                            <thead>
                                {{-- First Row --}}
                                <tr>
                                    <th class="vertical-align-middle sort-disabled not-export" colspan="1"
                                        rowspan="3">Actions
                                    </th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="3">My Code</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="3">ID</th>
                                    <th colspan="10" rowspan="2"
                                        class="vertical-align-middle sort-disabled sort-disabled">
                                        BOOKING INFORMTION
                                    </th>
                                    <th colspan="4" rowspan="2" class="vertical-align-middle sort-disabled">
                                        BILLABLE
                                        HOURS
                                    </th>
                                    <th colspan="5" rowspan="2" class="vertical-align-middle sort-disabled">
                                        INFORMATION OF THE
                                        CASE
                                        INTERPRETED FOR
                                    </th>
                                    <th colspan="2" rowspan="2" class="vertical-align-middle sort-disabled">
                                        EXPENSES
                                        PAID</th>
                                    <th colspan="10" rowspan="1" class="vertical-align-middle sort-disabled">
                                        REIMBURSABLE
                                        EXPENSES</th>
                                    <th colspan="3" rowspan="2" class="vertical-align-middle sort-disabled">
                                        HOURLY FEE
                                    </th>
                                    <th colspan="4" rowspan="2" class="vertical-align-middle sort-disabled">
                                        RECEIVABLES</th>
                                    <th colspan="4" rowspan="2" class="vertical-align-middle sort-disabled">
                                        PAYER'S
                                        DATA</th>
                                    <th colspan="1" rowspan="1" class="vertical-align-middle sort-disabled">
                                        TODAY</th>
                                    <th colspan="1" rowspan="3" class="vertical-align-middle">MY Ref. CODE
                                    </th>
                                    <th colspan="1" rowspan="3" class="vertical-align-middle">Paid to</th>
                                    <th colspan="1" rowspan="3" class="vertical-align-middle">status</th>

                                </tr>
                                {{-- Second Row --}}
                                <tr>
                                    <th colspan="2" rowspan="1" class="vertical-align-middle sort-disabled">
                                        Meals</th>
                                    <th colspan="2" rowspan="1" class="vertical-align-middle sort-disabled">
                                        Others
                                    </th>
                                    <th colspan="4" rowspan="1" class="vertical-align-middle sort-disabled">
                                        Personal
                                        Car</th>
                                    <th colspan="2" rowspan="1" class="vertical-align-middle sort-disabled">
                                        Total</th>
                                    <th colspan="1" rowspan="1" class="vertical-align-middle sort-disabled px-2">
                                        {{ now()->format('d/M/y') }}
                                    </th>
                                </tr>
                                {{-- Third Row --}}
                                <tr>
                                    <th class="vertical-align-middle sort-enabled">Date</th>
                                    <th class="vertical-align-middle sort-enabled">Payer</th>
                                    <th class="vertical-align-middle sort-enabled">For</th>
                                    <th class="vertical-align-middle sort-enabled">Prov</th>
                                    <th class="vertical-align-middle sort-enabled">Locality-Division</th>
                                    <th class="vertical-align-middle sort-enabled">Code</th>
                                    <th class="vertical-align-middle sort-enabled">Shift</th>
                                    <th class="vertical-align-middle sort-enabled">Type</th>
                                    <th class="vertical-align-middle sort-enabled">From</th>
                                    <th class="vertical-align-middle sort-enabled">To</th>
                                    <th class="vertical-align-middle sort-enabled">Hrs</th>
                                    <th class="vertical-align-middle sort-enabled">Interp</th>
                                    <th class="vertical-align-middle sort-enabled">Trip</th>
                                    <th class="vertical-align-middle sort-enabled">Total</th>
                                    <th class="vertical-align-middle sort-enabled">English Speaker</th>
                                    <th class="vertical-align-middle sort-enabled">Spanish Speaker</th>
                                    <th class="vertical-align-middle sort-enabled">Case/UCI No.</th>
                                    <th class="vertical-align-middle sort-enabled">Notes</th>
                                    <th class="vertical-align-middle sort-enabled">Ctry</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">kms</th>
                                    <th class="vertical-align-middle sort-enabled">¢/km</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">$/Hr</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">Cost</th>
                                    <th class="vertical-align-middle sort-enabled">HST</th>
                                    <th class="vertical-align-middle sort-enabled">Billed</th>
                                    <th class="vertical-align-middle sort-enabled">Balance</th>
                                    <th class="vertical-align-middle sort-enabled">Amount</th>
                                    <th class="vertical-align-middle sort-enabled">MAG Inv. No</th>
                                    <th class="vertical-align-middle sort-enabled">Payment No.</th>
                                    <th class="vertical-align-middle sort-enabled">Date</th>
                                    <th class="vertical-align-middle sort-enabled">Age</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoices as $invoice )
                                    <tr class="{{ $invoice->last_email_sent_at ? 'email-sent' : 'not-sent' }}">
                                        <td class="vertical-align-middle not-export">
                                            <!-- Dot Indicator -->
                                             <!-- <div class="payer-email-dot">

                                                                    
                                            @if ($invoice->email)
                                            <i class="fa fa-envelope" aria-hidden="true" title="{{ $invoice->email }}"></i>
                                            
                                            @else
                                              <span class="dot dot-inactive" title="Add Email For This Payer"></span> 
                                            <i class="fa fa-envelope not-email-val" aria-hidden="true" title="Add Email For This Payer"></i>

                                            @endif
                                            </div>   -->
                                   
                                            <!-- <a href="{{ route('send-invoice',  $invoice->id) }}" class="btn btn-info" id="sendInvoiceBtn">Send Invoice  
                                           
                                              </a> -->


                                              <!-- <<script>
                                                $(document).ready(function() {
    $('#sendInvoiceBtn').on('click', function(e) {
        e.preventDefault(); // Default link behavior ko prevent karein

        // Get the invoice ID from the button
        let invoiceId = $(this).attr('href').split('/').pop(); // Extract the ID from the URL
        let relatedInvoicesCount = {{ $invoices->count() }}; // Related invoices ka count

        if (relatedInvoicesCount > 1) {
            // Agar multiple invoices hain, toh popup dikhayein
            $('#invoiceSelectionModal').show(); // Yahan apna modal kholne ka logic add karein
        } else {
            // Agar sirf ek invoice hai, toh directly bhejein
            window.location.href = $(this).attr('href'); // Invoice ko send karein
        }
    });
});

                                              </script> -->

                                              <!-- <a href="#" class="btn btn-info" id="sendInvoiceBtn" data-invoice-id="{{ $invoice->id }}">
                                                    Send Invoice
                                                </a> -->
<!-- 
                                                <script>
document.getElementById('sendInvoiceBtn').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent the default action
    var invoiceId = this.getAttribute('data-invoice-id');

    // Make an AJAX request to check if the payer has multiple invoices
    fetch('/check-multiple-invoices/' + invoiceId)
        .then(response => response.json())
        .then(data => {
            if (data.multiple_invoices) {
                // Open modal and display invoices for selection
                openInvoiceSelectionModal(data.invoices);
            } else {
                // Redirect to invoice view
                window.location.href = '/send_invoice/' + invoiceId;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

function openInvoiceSelectionModal(invoices) {
    // Generate modal HTML
    var modalHtml = `
        <div class="modal" id="invoiceModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Invoices to View in Single Invoice  (Max 6)</h5>
                        <button type="button" class="close" onclick="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="invoiceSelectionForm">
                            ${invoices.map(invoice => `
                                <div>
                                    <input type="checkbox" name="invoice_ids" value="${invoice.id}" id="invoice_${invoice.id}">
                                    <label for="invoice_${invoice.id}">Invoice #${invoice.id} - ${invoice.booking_date}</label>
                                </div>
                            `).join('')}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="submitSelectedInvoices()" class="btn btn-primary">Submit</button>
                        <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Append modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Limit selection to 6 invoices
    var checkboxes = document.querySelectorAll('#invoiceSelectionForm input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var checkedCount = document.querySelectorAll('#invoiceSelectionForm input[type="checkbox"]:checked').length;
            if (checkedCount > 6) {
                this.checked = false;
                alert('You can select a maximum of 6 invoices.');
            }
        });
    });

    // Display the modal
    document.getElementById('invoiceModal').style.display = 'block';
}

function closeModal() {
    var modal = document.getElementById('invoiceModal');
    if (modal) {
        modal.remove();
    }
}

function submitSelectedInvoices() {
    var selectedInvoices = Array.from(document.querySelectorAll('#invoiceSelectionForm input[type="checkbox"]:checked'))
        .map(checkbox => checkbox.value);

    if (selectedInvoices.length === 0) {
        alert('Please select at least one invoice.');
        return;
    }

    // Redirect to the route to display selected invoices
    window.location.href = `/send_invoice_multiple?invoice_ids=${selectedInvoices.join(',')}`;
}
</script> -->



                                 
                                            
 
                                            <div class="d-flex gap-1">
                                            <form action="{{ route('edit_invoice_form', $invoice->id) }}" class="mt-2" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Edit</button>
                                            </form>
                                            <button type="button" class="btn btn-danger h-100 deleteButton mt-2"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="{{ $invoice->id }}">Delete</button></div>
                                                
                                        </td>
                                        <td class="vertical-align-middle">{{ $invoice->my_code }}</td>
                                        <td class="vertical-align-middle">{{ $invoice->id }}</td>
                                        <td class="vertical-align-middle">{{ $invoice->booking_date }}</td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_payer">{{ $invoice->booking_payer }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_for">{{ $invoice->booking_for }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_prov">{{ $invoice->booking_prov }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_locality_division"> {{ explode('/', $invoice->booking_locality_division)[0] }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_code"> {{ $invoice->booking_code }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_shift"> {{ $invoice->booking_shift }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_type"> {{ $invoice->booking_type }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_from"> {{ $invoice->booking_from }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="booking_to"> {{ $invoice->booking_to }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                      
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="billable_hrs"> {{ $invoice->billable_hrs }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="billable_interp"> {{ $invoice->billable_interp }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="billable_trip"> {{ $invoice->billable_trip }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="billable_total"> {{ $invoice->billable_total }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="case_english_speaker"> {{ $invoice->case_english_speaker}}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="case_spanish_speaker"> {{ $invoice->case_spanish_speaker}}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="case_uci_number"> {{ $invoice->case_uci_number }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="case_notes"> {{ $invoice->case_notes}}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="case_ctry"> {{ $invoice->case_ctry }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="expenses_paid_cost"> {{ $invoice->expenses_paid_cost }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="expenses_paid_hst"> {{ $invoice->expenses_paid_hst }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_meals_cost"> {{ $invoice->reimbursable_meals_cost }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_meals_hst"> {{ $invoice->reimbursable_meals_hst }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_others_cost"> {{ $invoice->reimbursable_others_cost }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_others_hst"> {{ $invoice->reimbursable_others_hst }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_car_kms"> {{ $invoice->reimbursable_car_kms }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_car_cents_per_km"> {{ $invoice->reimbursable_car_cents_per_km }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_car_cost"> {{ $invoice->reimbursable_car_cost }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_car_hst"> {{ $invoice->reimbursable_car_hst }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                      
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_total_cost"> {{ $invoice->reimbursable_total_cost }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="reimbursable_total_hst"> {{ $invoice->reimbursable_total_hst }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="hourly_fee_per_hour"> {{ $invoice->hourly_fee_per_hour  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="hourly_fee_cost"> {{ $invoice->hourly_fee_cost  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="hourly_fee_hst"> {{ $invoice->hourly_fee_hst  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="receivables_cost"> {{ $invoice->receivables_cost  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="receivables_hst"> {{ $invoice->receivables_hst  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="receivables_billed"> {{ $invoice->receivables_billed  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="receivables_balance"> {{ $invoice->receivables_balance  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                      
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="payer_amount"> {{ $invoice->payer_amount  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="payer_mag_invoice_number"> {{ $invoice->payer_mag_invoice_number }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="payer_payment_number"> {{ $invoice->payer_payment_number }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="payer_date"> {{ $invoice->payer_date }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="age"> {{ $invoice->age }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        
                                        <td class="vertical-align-middle">{{ $invoice->my_code }}</td>
                                        <td class="vertical-align-middle">
                                            <span class="editable" data-id="{{ $invoice->id }}" data-field="paid_to "> {{ $invoice->paid_to  }}</span>
                                            <button class="edit-btn" title="Edit"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td class="vertical-align-middle">
                                        <form action="{{ route('invoices.updateStatus', $invoice->id) }}" method="POST" id="status-form-{{ $invoice->id }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="status-dropdown"></label>
                                                <select name="status"  id="status-dropdown-{{ $invoice->id }}" onchange="updateColor({{ $invoice->id }})" class="form-control">
                                                    <option value="unpaid" class="status-unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                                    <option value="paid" class="status-paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                    <option value="less_pay" class="status-less-pay" {{ $invoice->status == 'less_pay' ? 'selected' : '' }}>Less Pay</option>

                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success">Update Status</button>
                                        </form>

                                        </td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header ">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete Invoice</h5>
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this invoice?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <form id="deleteForm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- CSV FILE IMPORT -->

                @if (isset($object))
                    <h2>CSV Data</h2>
                    <div class="container mt-5">
                        <table class="table table-striped table-bordered" id="invoice_table">
                            <thead>
                                {{-- First Row --}}
                                <tr>
                                    <th class="vertical-align-middle" colspan="1" rowspan="3">My Code</th>
                                    <th colspan="10" rowspan="2" class="vertical-align-middle">BOOKING
                                        INFORMTION
                                    </th>
                                    <th colspan="4" rowspan="2" class="vertical-align-middle">BILLABLE
                                        HOURS</th>
                                    <th colspan="5" rowspan="2" class="vertical-align-middle">INFORMATION
                                        OF THE
                                        CASE
                                        INTERPRETED FOR
                                    </th>
                                    <th colspan="2" rowspan="2" class="vertical-align-middle">EXPENSES PAID
                                    </th>
                                    <th colspan="10" rowspan="1" class="vertical-align-middler">REIMBURSABLE
                                        EXPENSES</th>
                                    <th colspan="3" rowspan="2" class="vertical-align-middle">HOURLY FEE
                                    </th>
                                    <th colspan="4" rowspan="2" class="vertical-align-middle">RECEIVABLES
                                    </th>
                                    <th colspan="4" rowspan="2" class="vertical-align-middle">PAYER'S DATA
                                    </th>
                                    <th colspan="1" rowspan="1" class="vertical-align-middle">TODAY</th>
                                    <th colspan="1" rowspan="3" class="vertical-align-middle">MY Ref. CODE
                                    </th>
                                    <th colspan="1" rowspan="3" class="vertical-align-middle">Paid to</th>

                                </tr>
                                {{-- Second Row --}}
                                <tr>
                                    <th colspan="2" rowspan="1" class="vertical-align-middle">Meals</th>
                                    <th colspan="2" rowspan="1" class="vertical-align-middle">Others</th>
                                    <th colspan="4" rowspan="1" class="vertical-align-middle">Personal Car
                                    </th>
                                    <th colspan="2" rowspan="1" class="vertical-align-middle">Total</th>
                                    <th colspan="1" rowspan="1" class="vertical-align-middle">
                                        {{ now()->format('d/M/y') }}
                                    </th>
                                </tr>
                                {{-- Third Row --}}
                                <tr>
                                    <th class="vertical-align-middle">Date</th>
                                    <th class="vertical-align-middle">Payer</th>
                                    <th class="vertical-align-middle">For</th>
                                    <th class="vertical-align-middle">Prov</th>
                                    <th class="vertical-align-middle">Locality-Division</th>
                                    <th class="vertical-align-middle">Code</th>
                                    <th class="vertical-align-middle">Shift</th>
                                    <th class="vertical-align-middle">Type</th>
                                    <th class="vertical-align-middle">From</th>
                                    <th class="vertical-align-middle">To</th>
                                    <th class="vertical-align-middle">Hrs</th>
                                    <th class="vertical-align-middle">Interp</th>
                                    <th class="vertical-align-middle">Trip</th>
                                    <th class="vertical-align-middle">Total</th>
                                    <th class="vertical-align-middle">English Speaker</th>
                                    <th class="vertical-align-middle">Spanish Speaker</th>
                                    <th class="vertical-align-middle">Case/UCI No.</th>
                                    <th class="vertical-align-middle">Notes</th>
                                    <th class="vertical-align-middle">Ctry</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">kms</th>
                                    <th class="vertical-align-middle">¢/km</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">$/Hr</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">Cost</th>
                                    <th class="vertical-align-middle">HST</th>
                                    <th class="vertical-align-middle">Billed</th>
                                    <th class="vertical-align-middle">Balance</th>
                                    <th class="vertical-align-middle">Amount</th>
                                    <th class="vertical-align-middle">MAG Inv. No</th>
                                    <th class="vertical-align-middle">Payment No.</th>
                                    <th class="vertical-align-middle">Date</th>
                                    <th class="vertical-align-middle">Age</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($object->rows as $row)
                            <tr>
                                @foreach ($row as $key => $value)
                                    <td class="vertical-align-middle">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                

            </div>
        </div>
<style>



    .not-sent  td,  .not-sent {
    color: var(--bs-dark) !important;

    }
    .email-sent td, .email-sent{
    color: var(--bs-success) !important;
}
.status-paid-bg td, .status-paid-bg{
    color: var(--bs-primary) !important;

}

.status-less-pay-bg td, .status-less-pay-bg{
    color: var(--bs-danger) !important;

}
    .status-unpaid-bg {
    background-color: lightcoral; 
}

.status-paid-bg {
    background-color: lightgreen; 
}
#invoice_table td{
    position: relative;
}
.payer-email-dot{
    position: absolute;
    top: 2px;
    left: 5px;
    cursor: pointer;
}
.payer-email-dot i{
font-size:12px;
}
.edit-btn{
    position:absolute;
    top:5px;
    right:5px;
    opacity: 0;
    transition: opacity 0.2s ease;
}
#invoice_table td:hover .edit-btn{
opacity: 1;

}
.not-email-val{
    animation: notmail 0.8s ease infinite;
}
@keyframes notmail {
    0%{
        transform: scale(0);
    }
    100%{
        transform: scale(1);

    }
}
    </style>

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
           <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- DataTables JavaScript -->
    <script  src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <!-- DataTables DateTime Plugin -->
   
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
  
    <script type="module">
    $(document).ready(function() {
        $('#alert-message span').click(function() {
            $('#alert-message').hide();
        });

        $('.deleteButton').on('click', function() {
            var invoiceId = $(this).data('id');
            $('#deleteForm').attr('action', '/delete_invoice/' + invoiceId);
        });

        $('.sort-disabled').on('click', function(e) {
            e.stopPropagation();
        });

        let minDate, maxDate;

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            let min = minDate.val() ? moment(minDate.val(), 'MMMM Do YYYY').startOf('day').utc() : null;
            let max = maxDate.val() ? moment(maxDate.val(), 'MMMM Do YYYY').endOf('day').utc() : null;
            let date = moment(data[3], 'MMM/DD/ddd/YYYY').startOf('day').utc(); // set the index of date field

            console.log('Min Date:', min);
            console.log('Max Date:', max);
            console.log('Current Date:', date);
            if (
        (min === null && max === null) ||
        (min === null && date <= max) ||
        (min <= date && max === null) ||
        (min <= date && date <= max)
    ) {
                return true;
            }
            return false;
        });

        
        minDate = new DateTime('#min', {
            format: 'MMMM Do YYYY'
        });
        maxDate = new DateTime('#max', {
            format: 'MMMM Do YYYY'
        });

        let table = $('#invoice_table').DataTable({
            dom: 'Bfrltip',
            buttons: [{
                extend: 'excelHtml5',
                text: 'Export as Excel',
                className: 'btn btn-success',
                filename: 'Invoices',
                exportOptions: {
                    columns: ':visible:not(.not-export)',
                },
            }],
            responsive: false,
            "scrollX": true,
            autoWidth: false,
            bFilter: true,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                ['10', '25', '50', 'Show all']
            ],
            columnDefs: [{
                    targets: 'sort-enabled',
                    orderable: true
                },
                {
                    targets: 'sort-disabled',
                    orderable: false
                }
            ],
        });

        table.columns.adjust().draw();

        // Event listener onchange function 
        document.querySelectorAll('#min, #max').forEach((el) => {
            el.addEventListener('change', () => table.draw());
        });
    });



  


</script>
<script>
 $(document).ready(function() {
    $('.edit-btn').on('click', function() {
        var span = $(this).siblings('.editable');
        var currentValue = span.text();
        var input = $('<input>', {
            type: 'text',
            value: currentValue,
            class: 'edit-input',
            'data-id': span.data('id'),
            'data-field': span.data('field')
        });
        span.replaceWith(input);
        input.focus();

        input.on('blur', function() {
            var newValue = $(this).val();
            var span = $('<span>', {
                text: newValue,
                class: 'editable',
                'data-id': $(this).data('id'),
                'data-field': $(this).data('field')
            });
            $(this).replaceWith(span);

        
            $.ajax({
                url: '/update-value/' + $(this).data('id'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    field: $(this).data('field'),
                    value: newValue
                },
                success: function(response) {
                    console.log('Value updated successfully');
                },
                error: function(xhr) {
                    console.log('Error updating value');
                }
            });
        });

       
        input.on('keydown', function(e) {
            if (e.which === 13) { 
                $(this).blur();
            }
        });
    });
});

function updateColor(invoiceId) {
        var dropdown = document.getElementById('status-dropdown-' + invoiceId);
        var selectedValue = dropdown.value;
        var form = document.getElementById('status-form-' + invoiceId).closest('tr');

        if (selectedValue === 'paid') {
            form.classList.remove('status-unpaid-bg');
            form.classList.add('status-paid-bg');
        } else if (selectedValue === 'unpaid') {
            form.classList.remove('status-paid-bg');
            form.classList.add('status-unpaid-bg');
        }
        else if (selectedValue === 'less_pay') {
            form.classList.add('status-less-pay-bg'); 
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        var forms = document.querySelectorAll('form[id^="status-form-"]');
        forms.forEach(function(form) {
            var invoiceId = form.getAttribute('id').split('-').pop();
            updateColor(invoiceId);
        });
    });
    </script>



    </x-app-layout>

{{-- @endsection --}}
