<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('option_codes', function (Blueprint $table) {
            $table->string('board', 100)->nullable()->after('selection_group');
            $table->string('qualification', 100)->nullable()->after('board');
        });
    }

    public function down(): void
    {
        Schema::table('option_codes', function (Blueprint $table) {
            $table->dropColumn(['board', 'qualification']);
        });
    }
};
