<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('email_logs', 'email_address')) {
                $table->string('email_address')->nullable()->after('candidate_name');
            }
            if (!Schema::hasColumn('email_logs', 'sent_status')) {
                // 'sent', 'failed'
                $table->string('sent_status')->default('sent')->after('message_date');
            }
            if (!Schema::hasColumn('email_logs', 'tracking_token')) {
                $table->string('tracking_token')->nullable()->unique()->after('sent_status');
            }
            if (!Schema::hasColumn('email_logs', 'opened_at')) {
                $table->dateTime('opened_at')->nullable()->after('tracking_token');
            }
            if (!Schema::hasColumn('email_logs', 'open_count')) {
                $table->unsignedInteger('open_count')->default(0)->after('opened_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $cols = ['email_address', 'sent_status', 'tracking_token', 'opened_at', 'open_count'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('email_logs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
