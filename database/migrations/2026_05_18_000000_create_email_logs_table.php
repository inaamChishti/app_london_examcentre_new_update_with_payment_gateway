<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_name')->nullable();
            $table->string('student_id')->nullable();
            $table->string('candidate_id')->nullable();
            $table->string('uci_number')->nullable();
            $table->string('subject_line');
            $table->longText('message');
            $table->dateTime('message_date');
            $table->string('session')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
