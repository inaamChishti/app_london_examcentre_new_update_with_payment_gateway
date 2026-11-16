<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_request_id')->constrained('student_requests')->cascadeOnDelete();
            $table->string('booking_reference', 40)->unique();
            $table->unsignedInteger('amount_pence');
            $table->char('currency', 3)->default('gbp');
            $table->string('payment_status', 40)->default('pending')->index();
            $table->string('stripe_checkout_session_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_bookings');
    }
};
