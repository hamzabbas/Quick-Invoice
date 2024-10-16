<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $welcome;
    public $pdfData;
    public $invoice;
    public $invoices;
    

    /**
     * Create a new message instance.
     *
     * @param string $welcome
     * @param mixed $pdfData
     * @param mixed $invoice
     *   * @param mixed $invoices
     * @return void
     */
    public function __construct($welcome, $pdfData, $invoice, $invoices)
    {
        $this->welcome = $welcome;
        $this->pdfData = $pdfData;
        $this->invoice = $invoice;
        $this->invoices = $invoices;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('send_invoice') 
                    ->subject('Invoice Mail')
                    ->attachData($this->pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'invoice' => $this->invoice,
                        'invoices' => $this->invoices,
                    ]); 
    }

    
}
