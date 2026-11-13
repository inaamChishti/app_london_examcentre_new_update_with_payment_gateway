<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('email_logs', 'candidate_id')) {
                $table->string('candidate_id')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('email_logs', 'uci_number')) {
                $table->string('uci_number')->nullable()->after('candidate_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('email_logs', 'candidate_id')) {
                $cols[] = 'candidate_id';
            }
            if (Schema::hasColumn('email_logs', 'uci_number')) {
                $cols[] = 'uci_number';
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
