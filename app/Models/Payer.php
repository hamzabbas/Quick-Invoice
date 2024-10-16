<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payer extends Model
{
    use HasFactory;

 protected $fillable = [
        'name',
        'email',
        // Add other fillable fields
    ];

    /**
     * Get the invoices for the payer.
     */
    public function invoices()
    {
        return $this->hasMany(Invoices::class);
    }
} 