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
        Schema::create('payers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamps();
        });

        // Schema::table('invoices', function (Blueprint $table) {
        //     $table->foreignId('payer_id')->constrained('payers')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('invoices', function (Blueprint $table) {
        //     $table->dropForeign(['payer_id']);
        //     $table->dropColumn('payer_id');
        // });
        Schema::dropIfExists('payers');
    }
};
