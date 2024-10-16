<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoices;
use App\Models\Payer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use stdClass;


class InvoiceController extends Controller
{
 /**
     * Handle file upload and process invoices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     // Orignal code 
    // public function generateMyCode(Request $request)
    // {
    //     $date = $request->input('date'); // Get the date from the url parameter
    //     $formattedDate = $request->input('formattedDate'); // Get the formatted date from the url parameter

    //     $year = substr($date, 0, 4); // Extract the year
    //     $shortYear = substr($year, 3); // Get the last one digits of the year
    //     $monthDay = substr($date, 5); // Get the month and day
    //     $newCode = $shortYear . '-' . str_replace('-', '', $monthDay); // Format the date

    //     // Get all the job IDs created on this date

    //     $my_codes = DB::table('invoices')->where('booking_date', $formattedDate)->pluck('my_code');
    //     // dd($my_codes);

    //     // Extract the sequence numbers
    //     $sequenceNumbers = $my_codes->map(function ($my_code) {
    //         $parts = explode('-', $my_code);
    //         return end($parts);
    //     });

    //     // Find the maximum sequence number
    //     $maxSequenceNumber = $sequenceNumbers->max();

    //     // Generate the new sequence number
    //     $sequenceNumber = $maxSequenceNumber + 1;

    //     // Check if the sequence number is within the allowed range
    //     if ($sequenceNumber > 9) {
    //         return response()->json(['error' => 'Maximum number of Invoices reached for this day.'], 400);
    //     }

    //     // Generate the job ID
    //     $my_code = $newCode . '-' . $sequenceNumber;

    //     return response()->json(['my_code' => $my_code]);
    // }

    // public function generateMyCode(Request $request)
    // {
    //     $date = $request->input('date'); 
    //     $formattedDate = $request->input('formattedDate'); 
    //     $isMultipleDays = $request->input('multiple_days'); 

    //       $shortYear = substr($date, 0, 4); 
    //       $year = substr($shortYear, 3);  
    //     $monthDay = str_replace('-', '', substr($date, 5)); 
    //     $newCode = $year . '-' . $monthDay; 
    
    //     if ($isMultipleDays) {
    //         $my_codes = DB::table('invoices')->where('booking_date', $formattedDate)->pluck('my_code');
    
    //         $existingMultipleCode = $my_codes->filter(function ($code) {
    //             return substr($code, -1) === 'M';
    //         })->first();
    
    //         if ($existingMultipleCode) {
    //             $my_code = $existingMultipleCode;
    //         } else {
    //             $my_code = $newCode . '-M';
    //         }
    //     } else {
    //         $my_codes = DB::table('invoices')->where('booking_date', $formattedDate)->pluck('my_code');
    
    //         $sequenceNumbers = $my_codes->map(function ($my_code) {
    //             $parts = explode('-', $my_code);
    //             $sequence = end($parts);
    //             return is_numeric($sequence) ? intval($sequence) : null; // Return only numeric sequences
    //         })->filter();
    
    //         $sequenceNumber = ($sequenceNumbers->max() ?? 0) + 1;
    
    //         if ($sequenceNumber > 9) {
    //             return response()->json(['error' => 'Maximum number of Invoices reached for this day.'], 400);
    //         }
    
    //         $my_code = $newCode . '-' . $sequenceNumber;
    //     }
    
    //     return response()->json(['my_code' => $my_code]);
    // }
// updated code  for mutiple invoices
    public function generateMyCode(Request $request)
    {
        $date = $request->input('date'); // Get the date from the request
        $formattedDate = $request->input('formattedDate'); // Get the formatted date from the request
        $isMultipleDays = $request->input('multiple_days'); // Get the multiple days preference
        $payer = $request->input('booking_payer'); // Get the payer from the request
    
        $year = substr($date, 3, 1); // Get the last one digit of the year
        $monthDay = str_replace('-', '', substr($date, 5)); // Get the month and day in 'mmdd' format
        $newCode = $year . '-' . $monthDay; // Format the code as 'y-mmdd'
        $my_code = '';
    
        if ($isMultipleDays) {
            // Base code for multiple days
            $my_code = $newCode . '-M';
    
            // Check if there is any existing multiple day invoice with 'M' for this payer
            $existingMultipleInvoices = DB::table('invoices')
                ->where('my_code', 'like', '%-M')
                ->where('booking_payer', $payer)
                ->exists(); // Check if any record exists
    
            if ($existingMultipleInvoices) {
                // Update all existing multiple day invoices to the new my_code
                DB::table('invoices')
                    ->where('booking_payer', $payer)
                    ->where('my_code', 'like', '%-M') // Update only multiple days invoices with 'M'
                    ->update(['my_code' => $my_code]);
            } 
    
        } else {
            // For single day invoices, get the last sequence number used for the given date
            $my_codes = DB::table('invoices')
                ->where('booking_date', $formattedDate)
                ->pluck('my_code');
    
            // Extract the sequence numbers only
            $sequenceNumbers = $my_codes->map(function ($my_code) {
                $parts = explode('-', $my_code);
                $sequence = end($parts);
                return is_numeric($sequence) ? intval($sequence) : null; // Return only numeric sequences
            })->filter();
    
            // Get the next sequence number
            $sequenceNumber = ($sequenceNumbers->max() ?? 0) + 1;
    
            // Check if the sequence number is within the allowed range
            if ($sequenceNumber > 9) {
                return response()->json(['error' => 'Maximum number of Invoices reached for this day.'], 400);
            }
    
            // Generate the single day job ID
            $my_code = $newCode . '-' . $sequenceNumber;
        }
    
        // Return the updated my_code without inserting into the database
        return response()->json(['my_code' => $my_code]);
    }
    
   
//     public function checkMultipleInvoices($id)
// {
//     $invoice = Invoices::find($id);
//     $payer = $invoice->booking_payer;

//     // Get all invoices for this payer
//     $invoices = Invoices::where('booking_payer', $payer)->get();

//     if ($invoices->count() > 1) {
//         return response()->json([
//             'multiple_invoices' => true,
//             'invoices' => $invoices
//         ]);
//     } else {
//         return response()->json([
//             'multiple_invoices' => false
//         ]);
//     }
// }

public function fetchInvoices(Request $request)
{
    $payer = $request->input('payer');
    $type = $request->input('type');

    // Fetch invoices based on payer and type
    if ($type == 'multiple') {
        $invoices = Invoices::where('booking_payer', $payer)
                            ->where('my_code', 'LIKE', '%M%') // Assuming multiple invoices have a certain pattern in my_code
                            ->get();
                            //dd($invoices);
    } else {
        $invoices = Invoices::where('booking_payer', $payer)
                            ->where('my_code', 'NOT LIKE', '%M%') // Assuming single invoices do not have this pattern
                            ->get();
    }

    return response()->json(['invoicess' => $invoices]);
}

// public function sendInvoices(Request $request)
// {
//     $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true);
//     $payer = $request->input('payer');

//     if (!empty($selectedInvoiceIds)) {
//         // Fetch invoices by ID
//         $invoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();
//     } else {
//         $invoices = collect(); // Empty collection if no invoices selected
//     }

//     // Return a new view with the selected invoices
//     return view('send_invoice', ['invoices' => $invoices, 'payer' => $payer]);
// }

public function sendInvoices(Request $request)
{
    $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true); // Get selected IDs
    $payer = $request->input('payer');

    // Debugging line to check selected invoice IDs
   // dd('Selected IDs:', $selectedInvoiceIds);

    if (!empty($selectedInvoiceIds)) {
        // Fetch invoices by ID
        $invoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();
    } else {
        $invoices = collect(); // Empty collection if no invoices selected
    }

    // Check if invoices were found
    if ($invoices->isEmpty()) {
       // dd('No invoices found for the selected IDs:', $selectedInvoiceIds); // Debugging line
    }

    // Return a new view with the selected invoices
    return view('send_invoice', ['invoices' => $invoices, 'payer' => $payer]);
}






// public function addPayerEmail(Request $request)
// {
//     // Validate the request data
//     $request->validate([
//         'payer' => 'required|string',
//         'email' => 'required|email',
//     ]);

//     $payer = $request->input('payer');
//     $email = $request->input('email');

//     // Update the email for all invoices of the selected payer
//     Invoices::where('booking_payer', $payer)->update(['email' => $email]);

//     return redirect()->back()->with('success', 'Email address updated for ' . $payer);
// }
// public function addPayerEmail(Request $request)
// {
//     // Validate the request data
//     $request->validate([
//         'payer' => 'required|string',
//         'email' => 'required|email',
//     ]);

//     $payerName = $request->input('payer');
//     $email = $request->input('email');

//     // Check if payer already exists
//     $payer = Payer::where('name', $payerName)->first();

//     if ($payer) {
//         // Update the email if payer exists
//         $payer->email = $email;
//         $payer->save();
//     } else {
//         // Create a new payer
//         $payer = Payer::create([
//             'name' => $payerName,
//             'email' => $email,
//         ]);
//     }

//     // Optionally, update the email in invoices if required
//     // This step is optional since now emails are linked via payers
//     // If you still need to store email on invoices, uncomment below
//     /*
//     Invoice::where('payer_id', $payer->id)->update(['email' => $email]);
//     */

//     return redirect()->back()->with('success', 'Email address updated for ' . $payer);
// }

public function addPayerEmail(Request $request)
{
    // Validate the request data
    $request->validate([
        'payer' => 'required|string',
        'email' => 'required|email',
    ]);

    $payerId = $request->input('payer_id');
    $payerName = $request->input('payer');
    $email = $request->input('email');

    if ($payerId) {
        // Update existing payer
        $payer = Payer::find($payerId);
        if ($payer) {
            $payer->name = $payerName;
            $payer->email = $email;
            $payer->save();
        }
    } else {
        // Create a new payer
        Payer::create([
            'name' => $payerName,
            'email' => $email,
        ]);
    }

    return redirect()->back()->with('success', 'Payer information updated successfully.');
}

public function deletePayerEmail($id)
{
    $payer = Payer::find($id);
    if ($payer) {
        $payer->delete();
    }

    return redirect()->back()->with('success', 'Payer deleted successfully.');
}

public function getPayers()
{
    $payers = Payer::all(); // Database se sare payers le lo
    return response()->json($payers); // JSON format me return karo
}


    
    // Retrieve all invoices
    public function getAllInvoices()
    {
       
        $invoices = Invoices::all();
        $payers = Payer::all();

        // Return the index view with the retrieved data
        return view('dashboard', compact('invoices', 'payers'));
    } 
 
    

    public function updateStatus(Request $request, Invoices $invoice)
    {
        $invoice->status = $request->input('status');
        $invoice->save();
    
        return redirect()->back()->with('success', 'Invoice status updated successfully!');
    }
    

    public function updateValue(Request $request, $id)
    {
        $invoice = Invoices::find($id);
    
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }
    
        $field = $request->input('field');
        $value = $request->input('value');
    
        if (!in_array($field, ['booking_payer', 
        'booking_for',
        'booking_prov',
        'booking_locality_division',
        'booking_code',
        'booking_shift',
        'booking_type',
        'booking_from',
        'booking_to',
        'billable_hrs',
        'billable_interp',
        'billable_trip',
        'billable_total',
        'case_english_speaker',
        'case_spanish_speaker',
        'case_uci_number',
        'case_notes',
        'case_ctry',
        'expenses_paid_cost',
        'expenses_paid_hst',
        'reimbursable_meals_cost',
        'reimbursable_meals_hst',
        'reimbursable_others_cost',
        'reimbursable_others_hst',
        'reimbursable_car_kms',
        'reimbursable_car_cents_per_km',
        'reimbursable_car_cost',
        'reimbursable_car_hst',
        'reimbursable_total_cost',
        'reimbursable_total_hst',
        'hourly_fee_per_hour',
        'hourly_fee_cost',
        'hourly_fee_hst',
        'receivables_cost',
        'receivables_hst',
        'receivables_billed',
        'receivables_balance',
        'payer_amount',
        'payer_mag_invoice_number',
        'payer_payment_number',
        'payer_date',
        'age'
        ])) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }
    
        $invoice->$field = $value;
    
        try {
            $invoice->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving value'], 500);
        }
    }
    
    
    // public function upload(Request $request)
    // {
    //     // Validate file type
    //     $request->validate([
    //         'csv_file' => 'required|file|mimes:csv,txt',
    //     ], [
    //         'csv_file.required' => 'Please upload a CSV file to import data.',
    //         'csv_file.mimes' => 'The uploaded file must be a CSV or TXT file.',
    //     ]);
    
    //     // Read the CSV file
    //     $path = $request->file('csv_file')->getRealPath();
    //     $data = array_map('str_getcsv', file($path));
    
    //     // If there are unwanted rows before actual data, remove them (Adjust the slice as needed)
    //     $data = array_slice($data, 6); // Skip unnecessary rows
    
    //     // Arrays to hold data
    //     $insert_data = [];
    //     $update_data = [];
    //     $duplicate_codes = [];
    //     $adjusted_codes = [];
    
    //     // Loop through each row in the data
    //     foreach ($data as $row) {
    //         // Validate the row has enough columns
    //         if (count($row) < 45) {
    //             continue; // Skip rows with insufficient data
    //         }
    
    //         // Clean and validate the 'my_code' field (index 0 in the row)
    //         $original_my_code = $row[0] === '' ? null : $row[0];
    //         $my_code = $original_my_code;
    
    //         if (Invoices::where('my_code', $my_code)->exists()) {
    //             // Handle duplicates by appending 'M' if necessary
    //             if (!str_contains($my_code, 'M') && !in_array($my_code, $adjusted_codes)) {
    //                 $my_code .= 'M';
    //                 $duplicate_codes[] = $original_my_code;
    //                 $adjusted_codes[] = $my_code;
    //             } elseif (in_array($my_code, $adjusted_codes)) {
    //                 continue;
    //             }
    //         }
    
    //         // Manually map fields from specific indices
    //         $data_to_save = [
    //             'my_code' => $my_code,
    //             'booking_date' => $row[1], // Example: booking_date at index 1
    //             'booking_payer' => $row[2] == '' ? null : $row[2], // booking_payer at index 2
    //             'booking_for' => $row[3] == '' ? null : $row[3],
    //             'booking_prov' => $row[4] == '' ? null : $row[4],
    //             'booking_locality_division' => $row[5] == '' ? null : $row[5],
    //             'booking_code' => $row[6] === '' ? null : $row[6],
    //             'booking_shift' => $row[7] == '' ? null : $row[7],
    //             'booking_type' => $row[8] == '' ? null : $row[8],
    //             'booking_from' => $row[9] == '' ? null : $row[9],
    //             'booking_to' => $row[10] == '' ? null : $row[10],
    //             'billable_hrs' => $row[11] == '' ? null : $row[11],
    //             'billable_interp' => $row[12] === '' ? null : $row[12],
    //             'billable_trip' => $row[13] == '' ? null : $row[13],
    //             'billable_total' => $row[14] == '' ? null : $row[14],
    //             'hourly_fee_per_hour' => intval(str_replace(['$', ','], '', $row[15])), // hourly_fee_per_hour at index 15
    //             'case_english_speaker' => $row[16] == '' ? null : $row[16],
    //             'case_spanish_speaker' => $row[17] == '' ? null : $row[17],
    //             'case_uci_number' => $row[18] == '' ? null : $row[18],
    //             'case_notes' => $row[19] == '' ? null : $row[19],
    //             'case_ctry' => $row[20]  == '' ? null : $row[20],
    //             'expenses_paid_cost' => $row[21] == '' ? null : $row[21],
    //             'expenses_paid_hst' => $row[22]  == '' ? null : $row[22],
    //             'reimbursable_meals_cost' => $row[23] == '' ? null : $row[23],
    //             'reimbursable_meals_hst' => $row[24] == '' ? null : $row[24],
    //             'reimbursable_others_cost' => $row[25] == '' ? null : $row[25],
    //             'reimbursable_others_hst' => $row[26]  == '' ? null : $row[26],
    //             'reimbursable_car_kms' => $row[27] === '' ? null : $row[27],
    //             'reimbursable_car_cents_per_km' => $row[28] === '' ? null : floatval(str_replace(['$', ','], '', $row[28])),
    //             'reimbursable_car_cost' => $row[29]  == '' ? null : $row[29],
    //             'reimbursable_car_hst' => $row[30] == '' ? null : $row[30],
    //             'reimbursable_total_cost' => $row[31]  == '' ? null : $row[31],
    //             'reimbursable_total_hst' => $row[32]  == '' ? null : $row[32],
    //             'hourly_fee_cost' => $row[34] === '' ? null : $row[34],
    //             'hourly_fee_hst' => $row[35]  == '' ? null : $row[35],
    //             'receivables_cost' => $row[36] == '' ? null : $row[36],
    //             'receivables_hst' => $row[37] == '' ? null : $row[37],
    //             'receivables_billed' => $row[38] == '' ? null : $row[38],
    //             'receivables_balance' => $row[39] == '' ? null : $row[39],
    //             'payer_amount' => $row[40] === '' ? null : $row[40],
    //             'payer_mag_invoice_number' => $row[41] == '' ? null : $row[41],
    //             'payer_payment_number' => $row[42] == '' ? null : $row[42],
    //             'payer_date' => $row[43] == '' ? null : $row[43],
    //             'age' => $row[44] === '' ? null : $row[44], // age at index 44
    //         ];
    
    //         // Check if it's an update or insert
    //         if (Invoices::where('my_code', $my_code)->exists()) {
    //             $update_data[] = $data_to_save;
    //         } else {
    //             $insert_data[] = $data_to_save;
    //         }
    //     }
    
    //     // Insert new data
    //     if (!empty($insert_data)) {
    //         DB::table('invoices')->insert($insert_data);
    //     }
    
    //     // Update existing data
    //     if (!empty($update_data)) {
    //         foreach ($update_data as $update) {
    //             Invoices::updateOrCreate(
    //                 ['my_code' => $update['my_code']],
    //                 $update
    //             );
    //         }
    //     }
    
    //     // Return success message
    //     $message = 'Data processed successfully.';
    //     if (!empty($duplicate_codes)) {
    //         $message .= ' Some duplicate entries were adjusted: ' . implode(', ', $duplicate_codes);
    //     }
    
    //     return redirect()->route('dashboard')->with('success', $message);
    // }


    public function upload(Request $request)
{
    // Validate file type
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ], [
        'csv_file.required' => 'Please upload a CSV file to import data.',
        'csv_file.mimes' => 'The uploaded file must be a CSV or TXT file.',
    ]);

    // Read the CSV file
    $path = $request->file('csv_file')->getRealPath();
    $data = array_map('str_getcsv', file($path));

    // If there are unwanted rows before actual data, remove them (Adjust the slice as needed)
    $data = array_slice($data, 6); // Skip unnecessary rows

    // Arrays to hold data
    $insert_data = [];
    $update_data = [];
    $skipped_rows = []; // To keep track of skipped rows

    // Loop through each row in the data
    foreach ($data as $index => $row) {
        // Validate the row has enough columns
        if (count($row) < 45) {
            continue; // Skip rows with insufficient data
        }

        // Clean and validate the 'my_code' field (index 0 in the row)
        $original_my_code = $row[0] === '' ? null : $row[0];
        $my_code = $original_my_code;

        // Manually map fields from specific indices
        $data_to_save = [
            'my_code' => $my_code,
            'booking_date' => $row[1],
            'booking_payer' => $row[2] == '' ? null : $row[2],
            'booking_for' => $row[3] == '' ? null : $row[3],
            'booking_prov' => $row[4] == '' ? null : $row[4],
            'booking_locality_division' => $row[5] == '' ? null : $row[5],
            'booking_code' => $row[6] === '' ? null : $row[6],
            'booking_shift' => $row[7] == '' ? null : $row[7],
            'booking_type' => $row[8] == '' ? null : $row[8],
            'booking_from' => $row[9] == '' ? null : $row[9],
            'booking_to' => $row[10] == '' ? null : $row[10],
            'billable_hrs' => $row[11] == '' ? null : $row[11],
            'billable_interp' => $row[12] === '' ? null : $row[12],
            'billable_trip' => $row[13] == '' ? null : $row[13],
            'billable_total' => $row[14] == '' ? null : $row[14],
            'hourly_fee_per_hour' => intval(str_replace(['$', ','], '', $row[15])),
            'case_english_speaker' => $row[16] == '' ? null : $row[16],
            'case_spanish_speaker' => $row[17] == '' ? null : $row[17],
            'case_uci_number' => $row[18] == '' ? null : $row[18],
            'case_notes' => $row[19] == '' ? null : $row[19],
            'case_ctry' => $row[20] == '' ? null : $row[20],
            'expenses_paid_cost' => $row[21] == '' ? null : $row[21],
            'expenses_paid_hst' => $row[22] == '' ? null : $row[22],
            'reimbursable_meals_cost' => $row[23] == '' ? null : $row[23],
            'reimbursable_meals_hst' => $row[24] == '' ? null : $row[24],
            'reimbursable_others_cost' => $row[25] == '' ? null : $row[25],
            'reimbursable_others_hst' => $row[26] == '' ? null : $row[26],
            'reimbursable_car_kms' => $row[27] === '' ? null : $row[27],
            'reimbursable_car_cents_per_km' => $row[28] === '' ? null : floatval(str_replace(['$', ','], '', $row[28])),
            'reimbursable_car_cost' => $row[29] == '' ? null : $row[29],
            'reimbursable_car_hst' => $row[30] == '' ? null : $row[30],
            'reimbursable_total_cost' => $row[31] == '' ? null : $row[31],
            'reimbursable_total_hst' => $row[32] == '' ? null : $row[32],
            'hourly_fee_cost' => $row[34] === '' ? null : $row[34],
            'hourly_fee_hst' => $row[35] == '' ? null : $row[35],
            'receivables_cost' => $row[36] == '' ? null : $row[36],
            'receivables_hst' => $row[37] == '' ? null : $row[37],
            'receivables_billed' => $row[38] == '' ? null : $row[38],
            'receivables_balance' => $row[39] == '' ? null : $row[39],
            'payer_amount' => $row[40] === '' ? null : $row[40],
            'payer_mag_invoice_number' => $row[41] == '' ? null : $row[41],
            'payer_payment_number' => $row[42] == '' ? null : $row[42],
            'payer_date' => $row[43] == '' ? null : $row[43],
            'age' => $row[44] === '' ? null : $row[44],
        ];

        // Check if the record with same 'my_code' and other data exists in the database
        if (Invoices::where('my_code', $my_code)->where('booking_date', $data_to_save['booking_date'])->exists()) {
            $skipped_rows[] = $original_my_code;
            continue; // Skip the row if it already exists
        }

        // If the code doesn't exist, add it to the insert_data array
        $insert_data[] = $data_to_save;
    }

    // Insert new data
    if (!empty($insert_data)) {
        DB::table('invoices')->insert($insert_data);
    }

    // Return success message with skipped rows information
    $message = 'Data processed successfully.';
    if (!empty($skipped_rows)) {
        $message .= ' The following rows were skipped because they already exist: ' . implode(', ', $skipped_rows);
    }

    return redirect()->route('dashboard')->with('success', $message);
}

    
    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => 'required|file|mimes:csv,txt',
    //     ], [
    //         'csv_file.required' => 'Please upload a CSV file to import data.',
    //         'csv_file.mimes' => 'The uploaded file must be a CSV or TXT file.',
    //     ]);
    
    //     $path = $request->file('csv_file')->getRealPath();
    //     $data = array_map('str_getcsv', file($path));
    //     $data = array_slice($data, 6);
    //     //dd( $data);
    
    //     $insert_data = [];
    //     $update_data = [];
    //     $duplicate_codes = [];
    //     $adjusted_codes = []; 
    
    //     foreach ($data as $row) {
    //         $original_my_code = $row[0] === '' ? null : $row[0];
    //         $my_code = $original_my_code;
    
        
    //         if (Invoices::where('my_code', $my_code)->exists()) {
           
    //             if (!str_contains($my_code, 'M') && !in_array($my_code, $adjusted_codes)) {
    //                 $my_code .= 'M'; 
    //                 $duplicate_codes[] = $original_my_code;
    //                 $adjusted_codes[] = $my_code;
    //             } elseif (in_array($my_code, $adjusted_codes)) {
                
    //                 continue;
    //             }
    //         }
    
    //         $data_to_save = [
    //             'my_code' => $my_code,
    //             'booking_date' => $row[1],
    //             'booking_payer' => $row[2],
    //             'booking_for' => $row[3],
    //             'booking_prov' => $row[4],
    //             'booking_locality_division' => $row[5],
    //             'booking_code' => $row[6] === '' ? null : $row[6],
    //             'booking_shift' => $row[7],
    //             'booking_type' => $row[8],
    //             'booking_from' => $row[9],
    //             'booking_to' => $row[10],
    //             'billable_hrs' => $row[11],
    //             'billable_interp' => $row[12] === '' ? null : $row[12],
    //             'billable_trip' => $row[13],
    //             'billable_total' => $row[14],
    //             'case_english_speaker' => $row[15],
    //             'case_spanish_speaker' => $row[16],
    //             'case_uci_number' => $row[17],
    //             'case_notes' => $row[18],
    //             'case_ctry' => $row[19],
    //             'expenses_paid_cost' => $row[20],
    //             'expenses_paid_hst' => $row[21],
    //             'reimbursable_meals_cost' => $row[22],
    //             'reimbursable_meals_hst' => $row[23],
    //             'reimbursable_others_cost' => $row[24],
    //             'reimbursable_others_hst' => $row[25],
    //             'reimbursable_car_kms' => $row[26] === '' ? null : $row[26],
    //             'reimbursable_car_cents_per_km' => $row[27] === '' ? null : $row[27],
    //             'reimbursable_car_cost' => $row[28],
    //             'reimbursable_car_hst' => $row[29],
    //             'reimbursable_total_cost' => $row[30],
    //             'reimbursable_total_hst' => $row[31],
    //             'hourly_fee_per_hour' => intval(str_replace(['$', ','], '', $row[32])),
    //             'hourly_fee_cost' => $row[33] === '' ? null : $row[33],
    //             'hourly_fee_hst' => $row[34],
    //             'receivables_cost' => $row[35],
    //             'receivables_hst' => $row[36],
    //             'receivables_billed' => $row[37],
    //             'receivables_balance' => $row[38],
    //             'payer_amount' => $row[39] === '' ? null : $row[39],
    //             'payer_mag_invoice_number' => $row[40],
    //             'payer_payment_number' => $row[41],
    //             'payer_date' => $row[42],
    //             'age' => $row[43] === '' ? null : $row[43],
    //             'paid_to' => $row[45] === '' ? null : $row[45],
    //         ];
    
    //         if (Invoices::where('my_code', $my_code)->exists()) {
    //             $update_data[] = $data_to_save;
    //         } else {
    //             $insert_data[] = $data_to_save;
    //         }
    //     }
    
    //     if (!empty($insert_data)) {
    //         DB::table('invoices')->insert($insert_data);
    //     }
    
    //     if (!empty($update_data)) {
    //         foreach ($update_data as $update) {
    //             Invoices::updateOrCreate(
    //                 ['my_code' => $update['my_code']],
    //                 $update
    //             );
    //         }
    //     }
    
    //     $message = 'Data processed successfully.';
    
    //     if (!empty($duplicate_codes)) {
    //         $message .= ' Some duplicate entries were adjusted: ' . implode(', ', $duplicate_codes);
    //     }
    
    //     return redirect()->route('dashboard')->with('success', $message);
    // }
    
    





public function createInvoice(Request $request)
{
    $invoice_data = $request->all();

    // Validate the request data
    $validatedData = $request->validate([
        'my_code' => 'required',
        'booking_date' => 'required',
        'booking_payer' => 'required',
        'booking_for' => '',
        'booking_prov' => '',
        'booking_locality_division' => '',
        'booking_code' => '',
        'booking_shift' => '',
        'booking_type' => '',
        'booking_from' => '',
        'booking_to' => '',
        'billable_hrs' => '',
        'billable_interp' => '',
        'billable_trip' => '',
        'billable_total' => '',
        'case_english_speaker' => '',
        'case_spanish_speaker' => '',
        'case_uci_number' => '',
        'case_notes' => '',
        'case_ctry' => '',
        'expenses_paid_cost' => '',
        'expenses_paid_hst' => '',
        'reimbursable_meals_cost' => '',
        'reimbursable_meals_hst' => '',
        'reimbursable_others_cost' => '',
        'reimbursable_others_hst' => '',
        'reimbursable_car_kms' => '',
        'reimbursable_car_cents_per_km' => '',
        'reimbursable_car_cost' => '',
        'reimbursable_car_hst' => '',
        'reimbursable_total_cost' => '',
        'reimbursable_total_hst' => '',
        'hourly_fee_per_hour' => '',
        'hourly_fee_cost' => '',
        'hourly_fee_hst' => '',
        'receivables_cost' => '',
        'receivables_hst' => '',
        'receivables_billed' => '',
        'receivables_balance' => '',
        'payer_amount' => '',
        'payer_mag_invoice_number' => '',
        'payer_payment_number' => '',
        'payer_date' => '',
        'age' => '',
        'paid_to' => '',
     
        // 'total_amount' => 'required|numeric|min:0',
        // 'adjustment_balance' => 'required|numeric|min:0',  
    ]);

    $validatedData['booking_date'] = $invoice_data['formatted_date'];

    try {
      
        $lastMainInvoice = Invoices::where('booking_payer', $validatedData['booking_payer'])
                                    ->where('is_main', true)
                                    ->first();

        if ($lastMainInvoice) {
            $relatedInvoicesCount = Invoices::where('my_code', $lastMainInvoice->my_code)
                                            ->count();

            if ($relatedInvoicesCount < 6) {
                $validatedData['is_main'] = false; // Not a main invoice
            } else {
                $validatedData['is_main'] = true; // This  main invoice
            }
        } else {
            $validatedData['is_main'] = true; // This  main invoice
        }

        $invoice = Invoices::create($validatedData);

        // If the creation is successful, redirect to the main route
        return redirect()->route('index')->with('success', 'Invoice created successfully');
    } catch (\Exception $e) {
        // Handle the error
        $fullMessage = $e->getMessage();
        preg_match("/Column '(.*?)' cannot be null/", $fullMessage, $matches);
        $mainPoint = $matches[0] ?? 'Unknown error';

        return redirect()->back()->with('error', 'Failed to create invoice: ' . $mainPoint)->withInput($invoice_data);
    }
}






    // Storing the invoice
    // public function createInvoice(Request $request)
// {

    //     $invoice_data = $request->all();
    //     // $invoice_data['booking_date'] = $invoice_data['formatted_date'];
    //     // dd($invoice_data);

    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'my_code' => 'required',
    //         'booking_date' => 'required',
    //         'booking_payer' => 'required',
    //         'booking_for' => '',
    //         'booking_prov' => '',
    //         'booking_locality_division' => '',
    //         'booking_code' => '',
    //         'booking_shift' => '',
    //         'booking_type' => '',
    //         'booking_from' => '',
    //         'booking_to' => '',
    //         'billable_hrs' => '',
    //         'billable_interp' => '',
    //         'billable_trip' => '',
    //         'billable_total' => '',
    //         'case_english_speaker' => '',
    //         'case_spanish_speaker' => '',
    //         'case_uci_number' => '',
    //         'case_notes' => '',
    //         'case_ctry' => '',
    //         'expenses_paid_cost' => '',
    //         'expenses_paid_hst' => '',
    //         'reimbursable_meals_cost' => '',
    //         'reimbursable_meals_hst' => '',
    //         'reimbursable_others_cost' => '',
    //         'reimbursable_others_hst' => '',
    //         'reimbursable_car_kms' => '',
    //         'reimbursable_car_cents_per_km' => '',
    //         'reimbursable_car_cost' => '',
    //         'reimbursable_car_hst' => '',
    //         'reimbursable_total_cost' => '',
    //         'reimbursable_total_hst' => '',
    //         'hourly_fee_per_hour' => '',
    //         'hourly_fee_cost' => '',
    //         'hourly_fee_hst' => '',
    //         'receivables_cost' => '',
    //         'receivables_hst' => '',
    //         'receivables_billed' => '',
    //         'receivables_balance' => '',
    //         'payer_amount' => '',
    //         'payer_mag_invoice_number' => '',
    //         'payer_payment_number' => '',
    //         'payer_date' => '',
    //         'age' => '',
    //         'paid_to' => '',
    //         'email' => '',
    //         'total_amount' => 'required|numeric|min:0',
    //         'adjustment_balance' => 'required|numeric|min:0',
    //     ]);


    //     $validatedData['booking_date'] = $invoice_data['formatted_date'];

    //     // dd($validatedData);
    //     // dd("Did not Run.");

    //     // Create a new invoice with the validated data
    //     try {
    //         $invoice = Invoices::create($validatedData);

    //         // If the creation is successful, redirect to the main route
    //         return redirect()->route('index')->with('success', 'Invoice created successfully');
    //     } catch (\Exception $e) {
    //         // If there's an error, redirect back with the error message
    //         // Get the full error message
    //         $fullMessage = $e->getMessage();

    //         // Use a regular expression to extract the main point
    //         preg_match("/Column '(.*?)' cannot be null/", $fullMessage, $matches);

    //         // The main point is in $matches[0] if a match was found
    //         $mainPoint = $matches[0] ?? 'Unknown error';

    //         // Redirect back with the main point of the error message
    //         return redirect()->back()->with('error', 'Failed to create invoice: ' . $mainPoint)->withInput($invoice_data);
    //     }
// }

    // Editing the invoice
    public function editInvoice($id)
    {
        // Retrieve the invoice with the specified ID
        $invoice = Invoices::find($id);

        // Return the edit invoice view with the specified invoice
        return view('edit_invoice', compact('invoice'));
    }

    // Updating the invoice
    public function updateInvoice(Request $request)
    {
        $id = $request->input('id');
        $invoice_data = $request->all();

        // Validate the request data
        $validatedData = $request->validate([
            'my_code' => 'required',
            'booking_date' => 'required',
            'booking_payer' => 'required',
            'booking_for' => '',
            'booking_prov' => '',
            'booking_locality_division' => '',
            'booking_code' => '',
            'booking_shift' => '',
            'booking_type' => '',
            'booking_from' => '',
            'booking_to' => '',
            'billable_hrs' => '',
            'billable_interp' => '',
            'billable_trip' => '',
            'billable_total' => '',
            'case_english_speaker' => '',
            'case_spanish_speaker' => '',
            'case_uci_number' => '',
            'case_notes' => '',
            'case_ctry' => '',
            'expenses_paid_cost' => '',
            'expenses_paid_hst' => '',
            'reimbursable_meals_cost' => '',
            'reimbursable_meals_hst' => '',
            'reimbursable_others_cost' => '',
            'reimbursable_others_hst' => '',
            'reimbursable_car_kms' => '',
            'reimbursable_car_cents_per_km' => '',
            'reimbursable_car_cost' => '',
            'reimbursable_car_hst' => '',
            'reimbursable_total_cost' => '',
            'reimbursable_total_hst' => '',
            'hourly_fee_per_hour' => '',
            'hourly_fee_cost' => '',
            'hourly_fee_hst' => '',
            'receivables_cost' => '',
            'receivables_hst' => '',
            'receivables_billed' => '',
            'receivables_balance' => '',
            'payer_amount' => '',
            'payer_mag_invoice_number' => '',
            'payer_payment_number' => '',
            'payer_date' => '',
            'age' => '',
            'paid_to' => '',
        ]);

        $validatedData['booking_date'] = $invoice_data['formatted_date'];

        // Update the invoice with the validated data
        try {
            $invoice = Invoices::find($id);
            $invoice->update($validatedData);

            // If the update is successful, redirect to the main route
            return redirect()->route('index')->with('success', 'Invoice updated successfully');
        } catch (\Exception $e) {
            // If there's an error, redirect back with the error message
            // Get the full error message
            $fullMessage = $e->getMessage();

            // Use a regular expression to extract the main point
            preg_match("/Column '(.*?)' cannot be null/", $fullMessage, $matches);

            // The main point is in $matches[0] if a match was found
            $mainPoint = $matches[0] ?? 'Unknown error';

            // Redirect back with the main point of the error message
            return redirect()->back()->with('error', 'Failed to update invoice: ' . $mainPoint)->withInput($invoice_data);
        }
    }

    // Deleting the invoice
    public function deleteInvoice($id)
    {
        // Retrieve the invoice with the specified ID
        $invoice = Invoices::find($id);

        // Delete the invoice
        $invoice->delete();

        // Redirect to the main route
        return redirect()->route('index')->with('success', 'Invoice deleted successfully');
    }







    // public function createInvoice(Request $request)
    // {
    //     // Validate the request data
    //     // $validatedData = $request->validate([
    //     //     'my_code' => 'required',
    //     //     'customer_name' => 'required',
    //     //     'customer_email' => 'required|email',
    //     //     'amount' => 'required|numeric',
    //     //     'due_date' => 'required|date',
    //     // ]);

    //     // Create a new invoice with the validated data
    //     $invoice = Invoice::create($validatedData);

    //     // Return the invoice show view with the specified invoice
    //     return view('show_invoice', compact('invoice'));
    // }

    // public function index()
    // {
    //     // Retrieve all invoices
    //     $invoices = Invoice::all();

    //     // Return the invoices view with the retrieved data
    //     return view('invoices.index', compact('invoices'));
    // }

    // public function create()
    // {
    //     // Return the create invoice view
    //     return view('invoices.create');
    // }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            // Define your validation rules here
        ]);

        // Create a new invoice with the validated data
        $invoice = Invoice::create($validatedData);

        // Redirect to the invoice show page
        return redirect()->route('invoices.show', $invoice->id);
    }

    public function show(Invoice $invoice)
    {
        // Return the invoice show view with the specified invoice
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        // Return the edit invoice view with the specified invoice
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Validate the request data
        $validatedData = $request->validate([
            // Define your validation rules here
        ]);

        // Update the invoice with the validated data
        $invoice->update($validatedData);

        // Redirect to the invoice show page
        return redirect()->route('invoices.show', $invoice->id);
    }

    public function destroy(Invoice $invoice)
    {
        // Delete the invoice
        $invoice->delete();

        // Redirect to the invoices index page
        return redirect()->route('invoices.index');
    }
}
