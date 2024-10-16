@extends('welcome')

@section('content')
    <div class="container">
        @if (isset($invoices))
            <div class="container mt-5">
                <div class="d-flex w-100 justify-content-between align-items-center ">
                    <h2>Invoices</h2>
                    <div class="d-flex gap-2">
                        <form action="{{ route('create_invoice_form') }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary">Add New</button>
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
                        <span>{{ session('error') }}Hello World</span>
                        <span class="float-end" aria-hidden="true" role="button">&times;</span>
                    </div>
                    <script>
                        setTimeout(function() {
                            $('#alert-message').fadeOut('fast');
                        }, 3000); // 3 seconds
                    </script>
                @endif
                <table class="table table-striped table-bordered" id="invoice_table">
                    <thead>
                        {{-- First Row --}}
                        <tr>
                            <th class="vertical-align-middle sort-disabled not-export" colspan="1" rowspan="3">Actions
                            </th>
                            <th class="vertical-align-middle" colspan="1" rowspan="3">My Code</th>
                            <th class="vertical-align-middle" colspan="1" rowspan="3">ID</th>
                            <th colspan="10" rowspan="2" class="vertical-align-middle sort-disabled sort-disabled">
                                BOOKING INFORMTION
                            </th>
                            <th colspan="4" rowspan="2" class="vertical-align-middle sort-disabled">BILLABLE HOURS
                            </th>
                            <th colspan="5" rowspan="2" class="vertical-align-middle sort-disabled">INFORMATION OF THE
                                CASE
                                INTERPRETED FOR
                            </th>
                            <th colspan="2" rowspan="2" class="vertical-align-middle sort-disabled">EXPENSES PAID</th>
                            <th colspan="10" rowspan="1" class="vertical-align-middle sort-disabled">REIMBURSABLE
                                EXPENSES</th>
                            <th colspan="3" rowspan="2" class="vertical-align-middle sort-disabled">HOURLY FEE</th>
                            <th colspan="4" rowspan="2" class="vertical-align-middle sort-disabled">RECEIVABLES</th>
                            <th colspan="4" rowspan="2" class="vertical-align-middle sort-disabled">PAYER'S DATA</th>
                            <th colspan="1" rowspan="1" class="vertical-align-middle sort-disabled">TODAY</th>
                            <th colspan="1" rowspan="3" class="vertical-align-middle">MY Ref. CODE</th>
                            <th colspan="1" rowspan="3" class="vertical-align-middle">Paid to</th>

                        </tr>
                        {{-- Second Row --}}
                        <tr>
                            <th colspan="2" rowspan="1" class="vertical-align-middle sort-disabled">Meals</th>
                            <th colspan="2" rowspan="1" class="vertical-align-middle sort-disabled">Others</th>
                            <th colspan="4" rowspan="1" class="vertical-align-middle sort-disabled">Personal Car</th>
                            <th colspan="2" rowspan="1" class="vertical-align-middle sort-disabled">Total</th>
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
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td class="vertical-align-middle not-export">
                                    <form action="{{ route('edit_invoice_form', $invoice->id) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </form>
                                    <button type="button" class="btn btn-danger deleteButton mt-2"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="{{ $invoice->id }}">Delete</button>
                                </td>
                                <td class="vertical-align-middle">{{ $invoice->my_code }}</td>
                                <td class="vertical-align-middle">{{ $invoice->id }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_date }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_payer }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_for }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_prov }}</td>
                                <td class="vertical-align-middle">
                                    {{ explode('/', $invoice->booking_locality_division)[0] }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_code }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_shift }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_type }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_from }}</td>
                                <td class="vertical-align-middle">{{ $invoice->booking_to }}</td>
                                <td class="vertical-align-middle">{{ $invoice->billable_hrs }}</td>
                                <td class="vertical-align-middle">{{ $invoice->billable_interp }}</td>
                                <td class="vertical-align-middle">{{ $invoice->billable_trip }}</td>
                                <td class="vertical-align-middle">{{ $invoice->billable_total }}</td>
                                <td class="vertical-align-middle">{{ $invoice->case_english_speaker }}</td>
                                <td class="vertical-align-middle">{{ $invoice->case_spanish_speaker }}</td>
                                <td class="vertical-align-middle">{{ $invoice->case_uci_number }}</td>
                                <td class="vertical-align-middle">{{ $invoice->case_notes }}</td>
                                <td class="vertical-align-middle">{{ $invoice->case_ctry }}</td>
                                <td class="vertical-align-middle">{{ $invoice->expenses_paid_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->expenses_paid_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_meals_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_meals_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_others_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_others_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_car_kms }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_car_cents_per_km }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_car_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_car_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_total_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->reimbursable_total_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->hourly_fee_per_hour }}</td>
                                <td class="vertical-align-middle">{{ $invoice->hourly_fee_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->hourly_fee_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->receivables_cost }}</td>
                                <td class="vertical-align-middle">{{ $invoice->receivables_hst }}</td>
                                <td class="vertical-align-middle">{{ $invoice->receivables_billed }}</td>
                                <td class="vertical-align-middle">{{ $invoice->receivables_balance }}</td>
                                <td class="vertical-align-middle">{{ $invoice->payer_amount }}</td>
                                <td class="vertical-align-middle">{{ $invoice->payer_mag_invoice_number }}</td>
                                <td class="vertical-align-middle">{{ $invoice->payer_payment_number }}</td>
                                <td class="vertical-align-middle">{{ $invoice->payer_date }}</td>
                                <td class="vertical-align-middle">{{ $invoice->age }}</td>
                                <td class="vertical-align-middle">{{ $invoice->my_code }}</td>
                                <td class="vertical-align-middle">{{ $invoice->paid_to }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Invoice</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this invoice?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        @if (isset($object2))
            <h2>CSV Data</h2>
            <div class="container mt-5">
                <table class="table table-striped table-bordered" id="invoice_table">
                    <thead>
                        {{-- First Row --}}
                        <tr>
                            <th class="vertical-align-middle" colspan="1" rowspan="3">My Code</th>
                            <th colspan="10" rowspan="2" class="vertical-align-middle">BOOKING INFORMTION</th>
                            <th colspan="4" rowspan="2" class="vertical-align-middle">BILLABLE HOURS</th>
                            <th colspan="5" rowspan="2" class="vertical-align-middle">INFORMATION OF THE CASE
                                INTERPRETED FOR
                            </th>
                            <th colspan="2" rowspan="2" class="vertical-align-middle">EXPENSES PAID</th>
                            <th colspan="10" rowspan="1" class="vertical-align-middler">REIMBURSABLE EXPENSES</th>
                            <th colspan="3" rowspan="2" class="vertical-align-middle">HOURLY FEE</th>
                            <th colspan="4" rowspan="2" class="vertical-align-middle">RECEIVABLES</th>
                            <th colspan="4" rowspan="2" class="vertical-align-middle">PAYER'S DATA</th>
                            <th colspan="1" rowspan="1" class="vertical-align-middle">TODAY</th>
                            <th colspan="1" rowspan="3" class="vertical-align-middle">MY Ref. CODE</th>
                            <th colspan="1" rowspan="3" class="vertical-align-middle">Paid to</th>

                        </tr>
                        {{-- Second Row --}}
                        <tr>
                            <th colspan="2" rowspan="1" class="vertical-align-middle">Meals</th>
                            <th colspan="2" rowspan="1" class="vertical-align-middle">Others</th>
                            <th colspan="4" rowspan="1" class="vertical-align-middle">Personal Car</th>
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

        @if (isset($testing))
            <table id="myTable" class="mt-5 table table-bordered">
                <thead>
                    {{-- First Row --}}
                    <tr>
                        <th colspan="1" rowspan="3">My Code</th>
                        <th colspan="10" rowspan="2" class="text-center">BOOKING INFORMTION</th>
                        <th colspan="4" rowspan="2" class="text-center">BILLABLE HOURS</th>
                        <th colspan="5" rowspan="2" class="text-center">INFORMATION OF THE CASE INTERPRETED FOR
                        </th>
                        <th colspan="2" rowspan="2" class="text-center">EXPENSES PAID</th>
                        <th colspan="10" rowspan="1" class="text-center">REIMBURSABLE EXPENSES</th>
                        <th colspan="3" rowspan="2" class="text-center">HOURLY FEE</th>
                        <th colspan="4" rowspan="2" class="text-center">RECEIVABLES</th>
                        <th colspan="4" rowspan="2" class="text-center">PAYER'S DATA</th>
                        <th colspan="1" rowspan="1" class="text-center">TODAY</th>
                        <th colspan="1" rowspan="3" class="text-center">MY Ref. CODE</th>
                        <th colspan="1" rowspan="3" class="text-center">Paid to</th>

                    </tr>
                    {{-- Second Row --}}
                    <tr>
                        <th colspan="2" rowspan="1" class="text-center">Meals</th>
                        <th colspan="2" rowspan="1" class="text-center">Others</th>
                        <th colspan="4" rowspan="1" class="text-center">Personal Car</th>
                        <th colspan="2" rowspan="1" class="text-center">Total</th>
                        <th colspan="1" rowspan="1" class="text-center">{{ now()->format('d/M/y') }}</th>
                    </tr>
                    {{-- Third Row --}}
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Payer</th>
                        <th class="text-center">For</th>
                        <th class="text-center">Prov</th>
                        <th class="text-center">Locality-Division</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Shift</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">From</th>
                        <th class="text-center">To</th>
                        <th class="text-center">Hrs</th>
                        <th class="text-center">Interp</th>
                        <th class="text-center">Trip</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">English Speaker</th>
                        <th class="text-center">Spanish Speaker</th>
                        <th class="text-center">Case/UCI No.</th>
                        <th class="text-center">Notes</th>
                        <th class="text-center">Ctry</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">kms</th>
                        <th class="text-center">¢/km</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">$/Hr</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">HST</th>
                        <th class="text-center">Billed</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">MAG Inv. No</th>
                        <th class="text-center">Payment No.</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Age</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        @endif

    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
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


            // $('#myTable').DataTable({
            //    "scrollX": true,
            // });
        });
    </script>
@endsection
