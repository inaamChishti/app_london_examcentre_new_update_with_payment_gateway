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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('session');
            $table->string('student_id');
            $table->string('name')->nullable();
            $table->string('payment_received');
            $table->string('amount_received');
            $table->enum('confirmed_by', ['Receipt', 'Manual']);
            $table->string('payment_method');
            $table->text('note')->nullable();
            $table->string('receipt_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
