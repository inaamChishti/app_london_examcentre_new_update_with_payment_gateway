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
            $table->text('current_domain')->nullable()->after('id');
        });

        Schema::table('student_requests', function (Blueprint $table) {
            $table->text('current_domain')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            $table->dropColumn('current_domain');
        });

        Schema::table('student_requests', function (Blueprint $table) {
            $table->dropColumn('current_domain');
        });
    }
};
