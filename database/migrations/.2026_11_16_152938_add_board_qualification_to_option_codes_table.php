<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('option_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('option_codes', 'board')) {
                $table->string('board', 100)->nullable()->after('selection_group');
            }
            if (!Schema::hasColumn('option_codes', 'qualification')) {
                $table->string('qualification', 100)->nullable()->after('board');
            }
        });
    }

    public function down(): void
    {
        Schema::table('option_codes', function (Blueprint $table) {
            $table->dropColumn(['board', 'qualification']);
        });
    }
};
