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
        Schema::create('seating_arrangements', function (Blueprint $table) {
            $table->id();
            $table->string('roomNumber');
            $table->integer('numRows');
            $table->json('rowsData'); // storing rows as JSON structure
            $table->integer('totalSeatingCapacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seating_arrangements');
    }
};
