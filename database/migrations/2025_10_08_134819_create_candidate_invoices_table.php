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
        Schema::create('candidate_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('family_id')->nullable();
            $table->string('email');
            $table->string('candidate_name');
            $table->text('subjects')->nullable();
            $table->string('total_fee');
            $table->string('payment_deadline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_invoices');
    }
};
