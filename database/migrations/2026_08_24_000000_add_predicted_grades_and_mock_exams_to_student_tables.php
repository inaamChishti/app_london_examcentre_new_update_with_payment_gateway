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
        Schema::table('student_details', function (Blueprint $table) {
            $table->string('need_predicted_grades')->nullable()->after('referral');
            $table->string('need_mock_exams')->nullable()->after('need_predicted_grades');
        });

        Schema::table('student_requests', function (Blueprint $table) {
            $table->string('need_predicted_grades')->nullable()->after('form_filling_date');
            $table->string('need_mock_exams')->nullable()->after('need_predicted_grades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            $table->dropColumn(['need_predicted_grades', 'need_mock_exams']);
        });

        Schema::table('student_requests', function (Blueprint $table) {
            $table->dropColumn(['need_predicted_grades', 'need_mock_exams']);
        });
    }
};
