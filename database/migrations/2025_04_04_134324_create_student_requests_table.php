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
        Schema::create('student_requests', function (Blueprint $table) {
            $table->id();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->string('postcode')->nullable();
            $table->string('uci_number')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('uln')->nullable();
            $table->text('medical_condition')->nullable();
            $table->boolean('access_arrangement')->default(false);
            $table->text('access_arrangement_explanation')->nullable();
            $table->string('practical_endorsement')->nullable();
            $table->string('course_work')->nullable();
            $table->string('course_work_uci')->nullable();
            $table->json('exam_board')->nullable();
            $table->json('course_type')->nullable();
            $table->json('subject_name')->nullable();
            $table->json('subject_code')->nullable();
            $table->json('tire')->nullable();
            $table->json('nea')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('supporting_documents_path')->nullable();
            $table->json('additional_docs_paths')->nullable();
            $table->json('is_approved')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_requests');
    }
};
