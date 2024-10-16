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
        Schema::create('invoice_selection', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id'); // Foreign key from invoices table
            $table->string('my_code'); // Multiple invoice code
            $table->unsignedBigInteger('selected_by')->nullable(); // User ID (optional)
            $table->unsignedBigInteger('viewed_id'); // ID of the viewed invoice or related entity
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }
        
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_selection');
    }
};
