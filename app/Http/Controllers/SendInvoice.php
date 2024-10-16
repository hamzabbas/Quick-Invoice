<?php

namespace App\Http\Controllers;
use App\Models\Invoices;
use App\Models\InvoiceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SendInvoice extends Controller
{
    // public function sendInvoice($id)
    // {
        
    //     $invoice = Invoices::find($id);

    //     return view('send_invoice', ['invoice' => $invoice]);
    // }

    public function sendInvoice($id)
    {
      
        $invoice = Invoices::find($id);
      
     
        if (strpos($invoice->my_code, '-M') !== false) {
      
            $relatedInvoices = Invoices::where('my_code', $invoice->my_code)
                    ->where('booking_payer', $invoice->booking_payer)
                     // ->limit(6)
                    ->get();
        } else {
            $relatedInvoices = collect([$invoice]);
        }

        return view('send_invoice', [
            'invoice' => $invoice,
            'invoices' => $relatedInvoices
        ]);
    }




    // public function sendMultipleInvoices(Request $request)
    // {
        
    //     $invoiceIds = explode(',', $request->query('invoice_ids'));

    //     // Fetch the selected invoices, limiting to 6
    //     $invoices = Invoices::whereIn('id', $invoiceIds)->take(6)->get();

    //     if ($invoices->isEmpty()) {
    //         return redirect()->back()->with('error', 'No invoices selected.');
    //     }

    //     // Pass the invoices to the view
    //     return view('send_invoice', [
    //         'invoices' => $invoices
    //     ]);
    // }

    // public function sendInvoice($id)
    // {
    //     $invoice = Invoices::findOrFail($id);
        
    //     if (strpos($invoice->my_code, '-M') !== false) {
    //         $totalInvoices = Invoices::where('my_code', $invoice->my_code)
    //             ->where('booking_payer', $invoice->booking_payer)
    //             ->count();
    
    //         if ($totalInvoices > 6) {
    //             // Send response indicating that modal should be shown
    //             return response()->json(['show_modal' => true]);
    //         } else {
    //             // Send response indicating that no modal is needed
    //             return response()->json(['show_modal' => false]);
    //         }
    //     } else {
    //         // For single invoices, direct response
    //         return response()->json(['show_modal' => false]);
    //     }
    // }
    


   
    

    
    
}
