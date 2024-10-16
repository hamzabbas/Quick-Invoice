<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\sendMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PdfController;
use App\Models\Invoices;
use App\Jobs\SendReminderEmailJob;
use App\Models\Payer;

class MailController extends Controller
{
    // public function sendemail($id){
       
    //     $invoice = Invoices::findOrFail($id);

    //     $pdfController = new PdfController();
    //     $pdfData = $pdfController->generatePdf($id);
    //     $toEmailAddress = $invoice->email;
    //     if (!$toEmailAddress) {
    //         return response()->json(['message' => 'No email address specified for invoice ID: ' . $invoice->id], 400);
    //     }

    //     // $toEmailAddress = 'meethamzaabbas945@gmail.com';
    //     $welcome = "Hey this is testing mail";

    //     Mail::to($toEmailAddress)->send(new sendMail($welcome, $pdfData, $invoice));
    //     $invoice->last_email_sent_at = now();
    //     $invoice->save();
    //     SendReminderEmailJob::dispatch($invoice->id)->delay(now()->addMinutes(10));

    //     return redirect()->back()->with('success', 'Email Send Successfully');
    // }

//     public function sendemail($id)
// {
//     $invoice = Invoices::findOrFail($id);

//     // Determine related invoices
//     if (strpos($invoice->my_code, '-M') !== false) {
//         // Fetch all invoices with the same my_code and booking_payer
//         $relatedInvoices = Invoices::where('my_code', $invoice->my_code)
//             ->where('booking_payer', $invoice->booking_payer)
//             ->get();
//     } else {
//         // If it's a single invoice, just collect the current one
//         $relatedInvoices = collect([$invoice]);
//     }

//     $pdfController = new PdfController();
//     $pdfData = $pdfController->generatePdf($id);
//     $toEmailAddress = $invoice->email;

//     if (!$toEmailAddress) {
//         return response()->json(['message' => 'No email address specified for invoice ID: ' . $invoice->id], 400);
//     }

//     $welcome = "Hey this is testing mail";

//     // Pass $relatedInvoices when creating sendMail
//     Mail::to($toEmailAddress)->send(new sendMail($welcome, $pdfData, $invoice, $relatedInvoices));

//     $invoice->last_email_sent_at = now();
//     $invoice->save();

//     foreach ($relatedInvoices as $relatedInvoice) {
//         $relatedInvoice->last_email_sent_at = now();
//         $relatedInvoice->save(); // Save each related invoice
//     }

//     SendReminderEmailJob::dispatch($invoice->id)->delay(now()->addMinutes(10));

//     return redirect()->back()->with('success', 'Email Sent Successfully');
// }


// public function sendEmaill(Request $request)
// {
//     // Get selected invoice IDs from the request
//     $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true);
//     dd($selectedInvoiceIds );

//     // Fetch all selected invoices
//     $relatedInvoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();

//     // Check if there are any related invoices
//     if ($relatedInvoices->isEmpty()) {
//         return response()->json(['message' => 'No invoices found for the selected IDs.'], 400);
//     }

//     // Generate PDF for selected invoices
//     $pdfController = new PdfController();
//     $pdfData = $pdfController->generatePdf($relatedInvoices);

//     // Loop through all related invoices to send email
//     foreach ($relatedInvoices as $invoice) {
//         $toEmailAddress = $invoice->email;

//         if ($toEmailAddress) {
//             // Prepare email content
//             $welcome = "Hey, please find the attached invoice.";
            
//             // Send the email with the generated PDF
//             Mail::to($toEmailAddress)->send(new sendMail($welcome, $pdfData, $invoice, $relatedInvoices));
            
//             // Update the last email sent timestamp
//             $invoice->last_email_sent_at = now();
//             $invoice->save();
//         }
//     }

//     return response()->json(['message' => 'Emails Sent Successfully']);
// }


// public function sendEmaill(Request $request)
// {
//     try {
//         // Get selected invoice IDs from the request
//         $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true);
//         \Log::info('Selected Invoice IDs for Email:', $selectedInvoiceIds);

//         // Validate selectedInvoiceIds
//         if (empty($selectedInvoiceIds)) {
//             return response()->json(['message' => 'No invoices selected.'], 400);
//         } 

//         // Fetch all selected invoices
//         $relatedInvoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();
//         \Log::info('Related Invoices:', $relatedInvoices->toArray());

//         // Check if there are any related invoices
//         if ($relatedInvoices->isEmpty()) {
//             return response()->json(['message' => 'No invoices found for the selected IDs.'], 400);
//         }

//         // Determine payer's email based on the payer field (assuming all invoices have the same payer)
//         $payer = $relatedInvoices->first()->booking_payer;
//         // Fetch payer's email. Adjust according to your database structure.
//         // For example, if payer's email is stored in a separate table:
//         // $payerEmail = Payer::where('name', $payer)->first()->email;

//         // For demonstration, assuming each invoice has an email field
//         $payerEmail = $relatedInvoices->first()->email;

//         if (!$payerEmail) {
//             return response()->json(['message' => 'No email address specified for the payer.'], 400);
//         }

//         // Generate PDF for selected invoices
//         $pdfController = new PdfController();
//         $pdfData = $pdfController->generatePdfForMultipleInvoices($selectedInvoiceIds);
//         // Ensure generatePdfForMultipleInvoices returns the PDF binary data

//         // Prepare email content
//         $welcomeMessage = "Hello, please find attached the invoices you requested.";
//         $invoice = $relatedInvoices->first();
//         // Send the email with the generated PDF
//         Mail::to($payerEmail)->send(new SendMail($welcomeMessage, $pdfData, $invoice, $relatedInvoices));

//         // Update the last email sent timestamp for each invoice
//         foreach ($relatedInvoices as $invoice) {
//             $invoice->last_email_sent_at = now();
//             $invoice->save();
//         }

//         // Optionally, dispatch a job for sending reminders
//         SendReminderEmailJob::dispatch($invoice->id)->delay(now()->addDays(15));

//         return response()->json(['message' => 'Emails Sent Successfully']);
//     } catch (\Exception $e) {
//         \Log::error('Error sending emails: ' . $e->getMessage());
//         return response()->json(['message' => 'Failed to send email. Please try again.', 'error' => $e->getMessage()], 500);
//     }
// }
public function sendEmaill(Request $request)
{
    try {
        // Get selected invoice IDs from the request
        $selectedInvoiceIds = json_decode($request->input('selected_invoices'), true);
        \Log::info('Selected Invoice IDs for Email:', $selectedInvoiceIds);

        // Validate selectedInvoiceIds
        if (empty($selectedInvoiceIds)) {
            return response()->json(['message' => 'No invoices selected.'], 400);
        } 

        // Fetch all selected invoices
        $relatedInvoices = Invoices::whereIn('id', $selectedInvoiceIds)->get();
        \Log::info('Related Invoices:', $relatedInvoices->toArray());

        // Check if there are any related invoices
        if ($relatedInvoices->isEmpty()) {
            return response()->json(['message' => 'No invoices found for the selected IDs.'], 400);
        }

        // Determine payer's name based on the first invoice
        $payerName = $relatedInvoices->first()->booking_payer;

        // Fetch payer's email from the Payer table
        $payer = Payer::where('name', $payerName)->first();

        // Check if payer exists and has an email
        if (!$payer || !$payer->email) {
            return response()->json(['message' => 'No email address found for the payer.'], 400);
        }

        // Payer's email
        $payerEmail = $payer->email;

        // Generate PDF for selected invoices
        $pdfController = new PdfController();
        $pdfData = $pdfController->generatePdfForMultipleInvoices($selectedInvoiceIds);
        // Ensure generatePdfForMultipleInvoices returns the PDF binary data

        // Prepare email content
        $welcomeMessage = "Hello, please find attached the invoices you requested.";
        $invoice = $relatedInvoices->first();

        // Send the email with the generated PDF
        Mail::to($payerEmail)->send(new SendMail($welcomeMessage, $pdfData, $invoice, $relatedInvoices));

        // Update the last email sent timestamp for each invoice
        foreach ($relatedInvoices as $invoice) {
            $invoice->last_email_sent_at = now();
            $invoice->save();
        }

        // Optionally, dispatch a job for sending reminders
        SendReminderEmailJob::dispatch($invoice->id)->delay(now()->addDays(15));

        return response()->json(['message' => 'Emails Sent Successfully']);
    } catch (\Exception $e) {
        \Log::error('Error sending emails: ' . $e->getMessage());
        return response()->json(['message' => 'Failed to send email. Please try again.', 'error' => $e->getMessage()], 500);
    }
}


}
