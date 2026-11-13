<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionToCandidateAndUciNumbersTable extends Migration
{
    public function up()
    {
        Schema::table('candidate_and_uci_numbers', function (Blueprint $table) {
            $table->string('session')->nullable()->after('email'); // or place after any other column
        });
    }

    public function down()
    {
        Schema::table('candidate_and_uci_numbers', function (Blueprint $table) {
            $table->dropColumn('session');
        });
    }
}
