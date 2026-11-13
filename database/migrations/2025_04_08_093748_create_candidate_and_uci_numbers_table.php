<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateAndUciNumbersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidate_and_uci_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_id');
            $table->string('uci_number')->nullable();
            $table->string('candidate_name')->nullable();
            $table->string('email')->nullable();
            $table->string('is_sent')->nullable();;
            $table->timestamps();

            // Optional: Add foreign key if 'candidate_id' references another table
            // $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_and_uci_numbers');
    }
}
