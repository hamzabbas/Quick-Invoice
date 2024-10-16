<style>
    .underline-b{
        border-bottom:1px solid #000;
    }
    .list-type{
        list-style-type:disc;
    }
    .border-bb{
        border-bottom: 1px solid black;
    display: inline-block;
    width: 60%;
    }
    .border-tt{
        border-top: 1px solid black;
    display: inline-block;
    width: 60%;
    }
    .dashed{
        display:inline-block;
      float:right;
        width:150px;
        border-bottom:1px solid;
       
    }
    .vertical-align-end{
        text-align:right;
        vertical-align:middle;
    }
    .border-bottom-dashed {
        border-bottom:1px dashed #000;
    }
    .send_invoice_table th, td{
        font-family: sans-serif;
        font-size:12px;
        font-weight:400;
        text-align:center;
    }
    .send_invoice_table th{
        padding:0 !important;
    }
    .fs-16{
        font-size:16px;
    }
    .fs-14{
        font-size:14px;
    }
    
    table {
    border-left: 0.01em solid #ccc;
    border-right: 0;
    border-top: 0.01em solid #ccc;
    border-bottom: 0;
    border-collapse: collapse;
}
table td,
table th {
    border-left: 0;
    border-right: 0.01em solid #ccc;
    border-top: 0;
    border-bottom: 0.01em solid #ccc;
    font-size:10px;
    font-weight: bold;
}
.invoice-top{
    display:flex;
}
.left-side, .right-side{
    width:50%;
}
.h-38>td{
    height:25px;
}

@media print {
            .no-print {
                display: none;
            }
        }
        .d-inline{
            display:inline-block;
        }
        .table-img{
            width:100px;

        }
        .invoice-top-table {
            width: 100%;
            border-collapse: collapse;
            border:0;
      
        }
        .invoice-top-table td {
            padding: 8px;
            border: 0;
            font-size:16px;
            text-align:left;

        }
        .end{
            display:flex;
            justify-content:end;
        }
        .w-30{
           position: relative;
        }
        .w-30:after {
    width: 150px;
    border-bottom: 1px solid #000 !important;
    position: absolute;
    content: "";
    bottom: 0;
 
    height: 1px;
    background: #000;
    left: 0;
    right: 0;
}
        .end{
         
    width: 50%;
   
    margin-left: auto;
    padding-right: 100px;
        }
       .border1{
        border:1px solid #000 !important;
       }
       .border-grey{
        border-top: 1px solid #e3e3e3;
        padding-top:10px;
       }
       .border{
        border:1px solid #000 !important;
       }
       .border-g{
        border:1px solid #e3e3e3 !important;
       }
       .border2{
        border:1px solid #000 !important;
       }
       .text-left{
        text-align: left;
       }
       .lh-20{
        line-height: 20px;
       }
       .d-inline-table{
        display: inline-flex;
       }
</style>

@extends('welcome') 
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

               
                        @if ($invoices->isNotEmpty())
        @php $invoice = $invoices->first(); @endphp <!-- Get the first invoice -->
        <form id="invoice-form">
        @csrf
    <!-- <input type="hidden" name="selected_invoices" id="selected_invoices" value="{{ json_encode($invoices->pluck('id')) }}"> -->
    <input type="hidden" name="selected_invoices" id="selected_invoices" value="{{ json_encode($invoices->pluck('id')->toArray()) }}">

        <table class="invoice-top-table">
            <tbody>
                <tr>
                    <td class="d-flex ">Ontario 
                        <img src="{{ asset('images/logo.png') }}" style="width: 50px; height: auto;" > &ensp;  Ministry of the Attorney General Court Services Division</td>
                    <td></td>
                    <td><b>Interpreter Invoice</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Invoice No.</b><span class="w-30">VIB {{ $invoice->booking_code ?? '' }} {{ $invoice->my_code ?? '' }}</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Invoice Date:</b><span class="w-30">{{ $invoice->booking_date ?? '' }}</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Court Location:</b><span class="w-30">{{ is_object($invoice) ? explode('/', $invoice->booking_locality_division)[0] ?? '' : '' }}</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Language:</b><span class="w-30">
                        @if (is_object($invoice) && isset($invoice->case_english_speaker) && $invoice->case_english_speaker)
                            English
                        @endif
                        @if (is_object($invoice) && isset($invoice->case_spanish_speaker) && $invoice->case_spanish_speaker)
                            Spanish
                        @endif
                    </span></td>
                </tr>
                <tr class="border-grey">
                    <td>Supplier’s Name (surname, first name, initials as registered with Ontario Shared Services)
                        <div>
                            <input type="checkbox" name="" id="">
                            Check this box <b>only</b> if this is a <b>new</b> address
                        </div>
                    </td>
                    <td class="text-center">Victor Barreto - ASETS Ltd <br>
                        471 Vanguard Cres., Oakville, Ontario L6L 5G6
                    </td>
                    <td class="border1">
                        <b>NEW: HST Registration No.</b> <br>
                        #####-####-RT0001
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <p>No invoices found.</p>
    @endif
        <div class="  mt-5 px-3">
        
       
           
            <div class="container-fluidd " id="example">
                
                
                
           
                       
                        <table class="table send_invoice_table  table-bordered "> 
                            <thead>
                           
                                <tr>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Date of Services</th>
                                    <th class="vertical-align-middle" colspan="10" rowspan="2">Case Name / Court File Number</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">
                                        <div>P</div>
                                        <div>V</div>
                                        <div>T</div>
                                    </th>
                                    <th class="vertical-align-middle" colspan="2" rowspan="1">Booked</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Total <br> Court <br> Hours</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Extra <br> Hours <br> Authorized</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Minus <br> Lunch</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Court <br> Clerk <br> Initials</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Round <br> Trip <br> Hours</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Total <br> Hours</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Hourly <br> Rates</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="2">Total For <br> Billable <br> Hours</th>
                                    <th class="vertical-align-middle" colspan="2" rowspan="2">Additional Authorized <br> Expenditures <br> (If any, attach receipts.)</th>
                                    <th class="vertical-align-middle" colspan="3" rowspan="2">Kilometre Allowance or <br> Transit Fare</th>
                                

                                </tr>
                                <tr>
                                    
                                    <th class="vertical-align-middle" colspan="1" rowspan="1">From</th>
                                    <th class="vertical-align-middle" colspan="1" rowspan="1">to</th>

                                   
                                </tr>
                              
                               
                                
                            </thead>
                           <tbody>
                                @php
                                    $grandTotal = 0; 
                                    $grandTotalKms = 0;
                                    $grandTotalOthersCost = 0;
                                    $grandCarCost = 0;
                                    $billableHst = 0;
                                    $expenseHst = 0;
                                    

                                @endphp
                                @if (isset($invoices) && count($invoices) > 0)                 
                                @foreach ($invoices as $invoice)
                                @php
                                    $grandTotalKms += $invoice->reimbursable_car_kms;
                                    $othersCost = 0;
                                    if (is_numeric($invoice->reimbursable_meals_cost)) {
                                        $othersCost = $invoice->reimbursable_meals_cost;
                                    }
                                    $grandTotalOthersCost += $othersCost;
                                    $carCost = 0;
                                    if(is_numeric($invoice->reimbursable_car_cost)){
                                        $carCost = $invoice->reimbursable_car_cost;
                                        $grandCarCost += $carCost;
                                    }
                                    $billableHst +=  $invoice->hourly_fee_hst;
                                    $expenseHst +=  $invoice->expenses_paid_hst;
                                    
                                  
                                @endphp 

                           <tr>
                                    <td class="vertical-align-middle">{{ $invoice->booking_date }}</td>
                                    <td class="vertical-align-middle" colspan="10">

                                        {{ $invoice->case_spanish_speaker }}
                                        @if ($invoice->case_uci_number )
                                         / @endif{{ $invoice->case_uci_number }}
                                    </td>
                                    <td class="vertical-align-middle" >{{ $invoice->booking_type }}</td>
                                    <td class="vertical-align-middle" colspan="1" >{{ rtrim($invoice->booking_from, ':00') }}</td>
                                    <td class="vertical-align-middle" colspan="1">{{ rtrim($invoice->booking_to, ':00') }}</td>
                                    <td class="vertical-align-middle" >{{ rtrim($invoice->billable_hrs, ':00') }}</td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" >1</td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" >{{ $invoice->billable_trip }}</td>
                                    <td class="vertical-align-middle" >{{ $invoice->billable_total - 1 }}</td>
                                    <td class="vertical-align-middle" >${{ $invoice->hourly_fee_per_hour }}</td>
                                    <td class="vertical-align-middle" >

                                        @php
                                        $rowTotal = ($invoice->billable_total - 1) * $invoice->hourly_fee_per_hour;
                                        $grandTotal += $rowTotal; 
                                    @endphp
                                    ${{ $rowTotal }}
                                    </td>
                                    <td class="vertical-align-middle" colspan="1" >Lunch</td>
                                    <td class="vertical-align-middle" colspan="1">{{ $invoice->reimbursable_meals_cost }}</td>
                                    <td class="vertical-align-middle" colspan="1">{{ $invoice->reimbursable_car_cost }}</td>
                                    <td class="vertical-align-middle" colspan="1">
                                        @if ($invoice->reimbursable_car_kms)
                                    {{ $invoice->reimbursable_car_kms }}/KM/s
                                @endif</td>
                                    <td class="vertical-align-middle" colspan="1">
                                        @if ($invoice->reimbursable_car_cents_per_km)
                                        {{ $invoice->reimbursable_car_cents_per_km }}/¢/km
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                         <tr>
                        <td colspan="5">No invoices found.</td>
                    </tr>
                @endif
                                <tr class="h-38">
                                <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle"  colspan="10"></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" colspan="1" ></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" colspan="1" ></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>           
                                </tr>
                                <tr class="h-38">
                                <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle"  colspan="10"></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" colspan="1" ></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" colspan="1" ></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>           
                                </tr>
                                <tr class="h-38">
                                <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle"  colspan="10"></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" colspan="1" ></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" ></td>
                                    <td class="vertical-align-middle" colspan="1" ></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>
                                    <td class="vertical-align-middle" colspan="1"></td>           
                                </tr>
                                <tr class="border-0">
                                    <td colspan="14" rowspan="6" class="border-0 ">
                                        <div class="text-left">
                                            <p class="lh-20">
                                                1, <span class="border-bb"></span>
                                                , Crown Attorney*, <br>
                                                also authorize additional expenditures to be paid in the amount of $ <br>
                                                for (reason)
                                                <span class="border-dashed dashed"></span>
                                            </p>
                                            <div class="border-bottom border-dark ms-auto w-75 pt-3"></div>
                                            <div class="gap-3 d-inline-table w-100">
                                            
                                                <div class=" border-dark w-75 mt-5 text-center lh-20 border-tt">Crown Attorney/Prosecuter</div>
                                                <div class="border-tt border-dark w-25 mt-5 text-center lh-20">Date</div>
                                            </div>
                                            <div class="pt-3">
                                                strike out inapplicable
                                            </div>

                                                </div>
                                    </td>
                                    <td class="vertical-align-end  border-0 " colspan="7" rowspan="1"  >
                                                <div>Value of <strong>Total</strong> Billable Hours</div>
                                    </td>
                                    <td class="vertical-align-middle border-g" colspan="1" rowspan="1"> ${{ $grandTotal }}
                                    </td>
                                    <td class="vertical-align-middle border-g" colspan="2" rowspan="1"  >{{ $grandTotalOthersCost }}
                                    </td>
                                    <td class="vertical-align-middle border-g" colspan="1" rowspan="1" class="border-left" >
                                    {{ number_format( $grandCarCost, 2) }}
                                    </td>
                                    <td class="vertical-align-middle border-g" colspan="2" rowspan="1"  >
                                    <strong>TOTAL</strong> KM
                                    </td>
                                   
                                </tr>
                                <tr class="border-0">
                                    <td class="vertical-align-end border-0" colspan="7" rowspan="1">
                                        value Of <strong>Total</strong> km
                                    </td>
                                    <td class="vertical-align-middle border-g">
                                    {{ $grandTotalKms }}
                                    </td>
                                    <td colspan="2" rowspan="2"class="border-g" >
                                    <div class="text-center pb-3">TOTAL <br> Additional</div>
                                    <div>Expenditures</div>
                                    </td>
                                    <td colspan="1" class="border-g"></td>
                                    <td colspan="2" class="vertical-align-middle border-g"> previous Balance</td>
                                </tr>
                                <tr class="border-0">
                                    <td colspan="7" rowspan="1" class="vertical-align-end border-0"><strong>TOTAL</strong> Other Expenses (Excluding KM)</td>
                                    <td colspan="1" rowspan="1" class="vertical-align-middle border-g">{{ $grandTotalOthersCost }}</td>
                                    <td class="vertical-align-middle border-g" colspan="1" rowspan="1" class="border-left" >  {{ number_format( $grandCarCost, 2) }}
                                    </td>
                                    <td class="vertical-align-middle border-g" colspan="2" rowspan="1" class="border-left" >
                                   <strong>TOTAL</strong> KM to date
                                    </td>
                                </tr>
                                @php
                                $totalBeforeTaxes =  $grandTotal  +   $grandTotalKms +  $grandTotalOthersCost ;
                                @endphp
                                <tr class="border-0">
                                    <td class="vertical-align-end border-0" colspan="7"><strong>SUBTOTAL Before Taxes</strong></td>
                                    <td class="vertical-align-middle border-g">{{ $totalBeforeTaxes}} </td>
                                   
                                    

                                </tr>
                                <tr class="border-0">
                                <td class="vertical-align-end border-0" colspan="7"><strong>HST for Billable hours</strong></td>
                                    <td class="vertical-align-middle border-g">{{$billableHst}}</td>
                                </tr>
                                <tr class="border-0">
                                <td class="vertical-align-end border-0" colspan="7"><strong>HST for Expense</strong></td>
                                    <td class="vertical-align-middle border-g">{{$expenseHst}}</td>
                                    <td colspan="2" class="border-0"></td>
                                    @php 
                                    $grandAllTotal = $totalBeforeTaxes + $billableHst + $expenseHst;
                                    @endphp
                                    <td class="vertical-align-middle border2 " colspan="4" rowspan="2">
                                        <strong class="fs-16">GRAND TOTAL WITH TAXES</strong> <br>

                                        <b class="fs-14">
                                           ${{$grandAllTotal}}
                                            </b>                        
                                    </td>
                                </tr>
                                <tr class="border-0">
                                    <td class="border-0"></td>
                                </tr>
                                <tr class="border-0">
                                    <td class="border-0"></td>
                                </tr>
                                <tr class="border-0">
                                    <td class="border-0"></td>
                                </tr>
                                <tr class="border-0 ">
                                    <td class=" border-0 text-left" colspan="14" >I certify that I was in attendance in court as described above.
                                

                                    </td>
                                    <td class="vertical-align-middle border-0" colspan="5"></td>
                                    <td class="vertical-align-end border-0" colspan="1">
                                        Approved:
                                    </td>
                                    <td colspan="4" class="vertical-align-middle border-0">
                                    <div class=" border-tt border-dark w-75 mt-3 text-center lh-20">Supervisor/Authorized Official


                                    </div>
                                    </td>
                                    <td class="vertical-align-end border-0" colspan="1">
                                        Date:
                                    </td>
                                    <td colspan="4" class="vertical-align-middle border-0">
                                    <div class=" border-tt border-dark w-75 mt-3 text-center lh-20">
                                    </div>
                                    </td>
                                </tr>
                                <tr class="border-0 ">
                                    
                                   
                                   
                                    <td class="vertical-align-middle border-0" colspan="7">
                                    <div class=" border-tt border-dark w-100 mt-3 text-center lh-20">Signature of Interpreter
                                    </div>
                                    </td>
                                  
                                   
                                    <td  class="vertical-align-middle border-0" colspan="7">
                                   
                                    <div class=" border-tt border-dark w-75 mt-3 text-center lh-20">Date
                                    </div>
                                    </td>
                                    <td colspan="5" class="border-0"></td>
                                   
                                    <td class="vertical-align-end border-0" colspan="1">
                                    Name & title:
                                    </td>
                                    <td colspan="5" class="vertical-align-middle border-0">
                                    <div class=" border-tt border-dark w-75 mt-3 text-center lh-20">
                                    </div>
                                    </td>
                                    
                                </tr>
                               
                           </tbody>
                            
                        </table>
{{--
         
                        <a href="{{ route('send.email', ['id' => $invoice->id]) }}" class="btn btn-primary no-print">Send Invoice to email</a>
                     --}}
                        <!-- <button type="button" id="send-invoice" class="btn btn-primary">Send Invoice</button> -->
                        
                    </div>
                    
                </div>
            </form>
            <!-- <button type="button" id="generate-pdf" class="btn btn-secondary">Generate PDF</button> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
        <script>
            $(document).ready(function() {
    // console.log({{ json_encode($invoices->pluck('id')->toArray()) }});
   

    $('#generate-pdf').on('click', function() {
        var selectedInvoices = {!! json_encode($invoices->pluck('id')->toArray()) !!}; // Use double curly braces for Blade
        console.log(selectedInvoices); // Check the output

        if (selectedInvoices.length > 0) { // Ensure it's an array
            $.ajax({
                url: '/generate-invoice-pdff',
                type: 'POST',
                data: {
                    selected_invoices: JSON.stringify(selectedInvoices), // Send as JSON string
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(response) {
                    // This will be handled by the stream in the controller
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr);
                    alert('Failed to generate PDF. Please try again.');
                }
            });
        } else {
            alert('No invoices to generate PDF.');
        }
    });
});

</script>
      

