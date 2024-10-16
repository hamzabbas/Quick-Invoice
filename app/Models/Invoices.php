<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'my_code',
        'booking_date',
        'booking_payer',
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
        'age',
        'paid_to',
   
        // 'total_amount',
        // 'adjustment_balance',
       
    ];

  public function payer()
    {
        return $this->belongsTo(Payer::class);
    }
}
