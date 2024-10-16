<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoices;
use App\Mail\sendMail;
use App\Http\Controllers\PdfController;
use Carbon\Carbon;

class SendReminderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoiceId;

    /**
     * Create a new job instance.
     */
    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $invoice = Invoices::find($this->invoiceId);

        if ($invoice) {
            $pdfController = new PdfController();
            $pdfData = $pdfController->generatePdf($invoice->id);
            
            $toEmailAddress = 'meethamzaabbas945@gmail.com'; // Replace with actual recipient
            $welcomeMessage = "Reminder: Your invoice is pending. Please review and take action.";
            
            Mail::to($toEmailAddress)->send(new sendMail($welcomeMessage, $pdfData, $invoice));

            $invoice->last_email_sent_at = Carbon::now();
            $invoice->save();

            \Log::info('Reminder email sent successfully for Invoice ID: ' . $this->invoiceId);
        } else {
            \Log::info('Invoice not found for Invoice ID: ' . $this->invoiceId);
        }
    }
}
