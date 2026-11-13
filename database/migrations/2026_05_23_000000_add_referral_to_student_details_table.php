<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            $table->string('referral')->nullable()->after('receipt_path');
        });
    }

    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            $table->dropColumn('referral');
        });
    }
};
