<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('option_codes', function (Blueprint $table) {
            $table->id();
            $table->string('specification_code', 20)->nullable();
            $table->string('entry_code', 20)->nullable();
            $table->string('gcse_subject', 255)->nullable();
            $table->string('paper_component', 100)->nullable();
            $table->string('component_code', 50)->nullable();
            $table->text('component_name')->nullable();
            $table->string('component_type', 100)->nullable();
            $table->string('selection_group', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_codes');
    }
};
