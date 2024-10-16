<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

use App\Models\Invoices;
 

class PdfController extends Controller
{
     // public function generatePdf($id)
     // {
     //  set_time_limit(300);
     //  $invoice = Invoices::findOrFail($id);
     //    $data = ['invoice' => $invoice];
     //    $html = view('send_invoice', $data)->render();
     //    $pdf = PDF::loadHTML($html)->setPaper('A4', 'portrait');
        
     //    return $pdf->output();
     // }

     public function generatePdf($id)
     {
         set_time_limit(300);
     
         // Find the specific invoice using the ID
         $invoice = Invoices::findOrFail($id);
     
         // Check if this is a multiple invoice
         if (strpos($invoice->my_code, '-M') !== false) {
             // Fetch all invoices with the same my_code and payer
             $relatedInvoices = Invoices::where('my_code', $invoice->my_code)
                 ->where('booking_payer', $invoice->booking_payer)
                 ->get();
         } else {
             // If it's a single invoice, just fetch the current one
             $relatedInvoices = collect([$invoice]);
         }
     
         // Pass single invoice and all related invoices to the view
         $data = [
             'invoice' => $invoice, // Single invoice details
             'invoices' => $relatedInvoices // Collection of all related invoices
         ];
         $html = view('send_invoice', $data)->render();
     
         // Create a new PDF instance with the specified paper size and orientation
         $pdf = PDF::loadHTML($html)->setPaper('A3', 'portrait');
     
         // Return the generated PDF content (for email attachment or storage)
         return $pdf->output();
     }
     

     // public function test($id){
     //  set_time_limit(300);
     //  $invoice = Invoices::findOrFail($id);
     //  $data = ['invoice' => $invoice];
     //  $html = view('send_invoice', $data)->render();
  
     //  $options = new Options();
     //  $options->set('isHtml5ParserEnabled', true);
     //  $options->set('isRemoteEnabled', true);
  
     //  $dompdf = new Dompdf($options);
     //  $dompdf->loadHtml($html);
     //  $dompdf->setPaper('A3', 'portrait');
     //  $dompdf->render();
      
     //  return $dompdf->stream('invoice.pdf', array("Attachment" => false));
     // }


    //  public function test($id)
    //  {
    //      set_time_limit(300);
     
    //      // Find the specific invoice using the ID
    //      $invoice = Invoices::findOrFail($id);
     
    //      // Check if this is a multiple invoice
    //      if (strpos($invoice->my_code, '-M') !== false) {
    //          // Fetch all invoices with the same my_code and payer
    //          $relatedInvoices = Invoices::where('my_code', $invoice->my_code)
    //              ->where('booking_payer', $invoice->booking_payer)
    //              ->get();
    //      } else {
    //          // If it's a single invoice, just fetch the current one
    //          $relatedInvoices = collect([$invoice]);
    //      }
     
    //      // Pass single invoice and all related invoices to the view
    //      $data = [
    //          'invoice' => $invoice, // Single invoice details
    //          'invoices' => $relatedInvoices // Collection of all related invoices
    //      ];
    //      $html = view('send_invoice', $data)->render();
     
    //      $options = new Options();
    //      $options->set('isHtml5ParserEnabled', true);
    //      $options->set('isRemoteEnabled', true);
    //       $options->set('defaultFont' , 'sans-serif');
     
    //      $dompdf = new Dompdf($options);
    //      $dompdf->loadHtml($html);
    //      $dompdf->setPaper('A3', 'portrait');
        
    //      $dompdf->render();
     
    //      return $dompdf->stream('invoice.pdf', array("Attachment" => false));
    //  }

    public function generatePDFF(Request $request)
    {
        \Log::info('Generating PDF with request:', $request->all());
        set_time_limit(300);
        // Get selected invoice IDs from the request
        $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true);
    
        // Fetch all selected invoices
        $relatedInvoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();
    
        // Check if there are any related invoices
        if ($relatedInvoices->isEmpty()) {
            return redirect()->back()->with('error', 'No invoices found for the selected IDs.');
        }
    
        // Pass related invoices to the view
        $data = [
            'invoices' => $relatedInvoices // Collection of all related invoices
        ];
    
        // Load the view for the PDF
        $html = view('send_invoice', $data)->render(); // Ensure this view exists
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'sans-serif');
    
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait'); // Adjust paper size if necessary
    
        $dompdf->render();
    
        // Stream the generated PDF file
        return $dompdf->stream('invoices.pdf', array("Attachment" => false));
    }


   // Controller Method
// public function generatePdfff(Request $request)
// {
//     \Log::info('Generating PDF with request androplpe:', $request->all());
//         set_time_limit(300);
//         // Get selected invoice IDs from the request
//         $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true);
//         \Log::info('Generating PDF with request one:', $selectedInvoiceIds);
//         // Fetch all selected invoices
//         // $relatedInvoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();
//         // \Log::info('Generating PDF with request twowwwwwwwww:', $relatedInvoices);
//         // Check if there are any related invoices
//         if ($selectedInvoiceIds->isEmpty()) {
//             return redirect()->back()->with('error', 'No invoices found for the selected IDs.');
//         }
    
//         // Pass related invoices to the view
//         $data = [
//             'invoices' => $selectedInvoiceIds // Co         llection of all related invoices
//         ];
    
//         // Load the view for the PDF
//         $html = view('send_invoice', $data)->render(); // Ensure this view exists
//         dd($html);
//         $options = new Options();
//         $options->set('isHtml5ParserEnabled', true);
//         $options->set('isRemoteEnabled', true);
//         $options->set('defaultFont', 'sans-serif');
    
//         $dompdf = new Dompdf($options);
//         $dompdf->loadHtml($html);
//         $dompdf->setPaper('A3', 'portrait'); // Adjust paper size if necessary
    
//         $dompdf->render();
    
//         // Stream the generated PDF file
//         return $dompdf->stream('invoices.pdf', array("Attachment" => false));
// }

public function generatePdfForMultipleInvoices(array $invoiceIds)
{
    // Fetch all invoices
    set_time_limit(300);
    $invoices = Invoices::whereIn('id', $invoiceIds)->get();

    

    // Prepare data for the view
    $data = [
        'invoices' => $invoices
    ];

    // Load the view and render HTML
    $html = view('send_invoice', $data)->render();

    // Initialize Dompdf with options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'sans-serif');
    $options->set('chroot', public_path());

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Adjust paper size if necessary

    $dompdf->render();

    // Return the generated PDF binary data
    return $dompdf->output();
}

    


     public function testview($id){
            $invoice = Invoices::find($id);
            return view('send_invoice', ['invoice' => $invoice]);
     }
}
