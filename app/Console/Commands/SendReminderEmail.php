<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\sendMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoices;
use Carbon\Carbon;
use App\Http\Controllers\PdfController;

class SendReminderEmail extends Command
{
    protected $signature = 'email:send-reminder';
    protected $description = 'Send reminder email for invoices';

    public function handle()
    {
        $invoices = Invoices::where('status', 'unpaid')
        ->where(function ($query) {
            $query->where('last_email_sent_at', '<', Carbon::now()->subMinutes(10))
                  ->orWhereNull('last_email_sent_at');
        })
        ->get();

            foreach ($invoices as $invoice) {
                if (!$invoice->email) {
                    continue;
                }
            // Generate PDF data
            $pdfController = new PdfController();
            $pdfData = $pdfController->generatePdf($invoice->id);

            // Logic to send reminder email
            $toEmailAddress = $invoice->email; // Replace with actual recipient
            $welcomeMessage = "Reminder: Your invoice is pending. Please review and take action.";

            // Send the email
            Mail::to($toEmailAddress)->send(new sendMail($welcomeMessage, $pdfData, $invoice));

            // Update the last_email_sent_at field
            $invoice->last_email_sent_at = Carbon::now();
            $invoice->save();

            $this->info('Reminder email sent successfully.');
            }

            if ($invoices->isEmpty()) {
            $this->info('No reminder needed at this time.');
            }
    }
}
