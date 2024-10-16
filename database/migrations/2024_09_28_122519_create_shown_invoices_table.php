<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shown_invoices', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('invoice_id'); // ID of the invoice
            $table->string('my_code'); // The code for the invoice (like 4-1007-M)
            $table->unsignedBigInteger('viewed_id'); // The ID of the view which displayed these invoices
            $table->timestamps(); // For created_at and updated_at columns

            // Add foreign key constraint (optional, if you have 'invoices' table)
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shown_invoices');
    }
};
