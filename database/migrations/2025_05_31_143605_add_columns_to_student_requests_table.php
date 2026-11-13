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
        Schema::table('student_requests', function (Blueprint $table) {
          $table->text('signature')->nullable()->after('exam_session');
            $table->text('form_filling_date')->nullable()->after('signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_requests', function (Blueprint $table) {
          $table->dropColumn('signature');
            $table->dropColumn('form_filling_date');
        });
    }
};
