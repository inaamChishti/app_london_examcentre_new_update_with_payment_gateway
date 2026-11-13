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
        Schema::create('migrated_student_record', function (Blueprint $table) {
            $table->id();
                        $table->string('current_domain')->nullable();
            $table->string('form_fill_date')->nullable();
            $table->text('sign')->nullable();
            $table->string('student_id')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('postcode')->nullable();
            $table->string('uci_number')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('uln')->nullable();
            $table->text('medical_condition')->nullable();
            $table->text('access_arrangement')->nullable();
            $table->text('access_arrangement_explanation')->nullable();
            $table->string('practical_endorsement')->nullable();
            $table->string('course_work')->nullable();
            $table->string('course_work_uci')->nullable();
            $table->string('day')->nullable();
            $table->string('time')->nullable();
            $table->string('date')->nullable();
            $table->string('duration')->nullable();
            $table->string('exam_board')->nullable();
            $table->string('course_type')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('subject_code')->nullable();
            $table->text('exams')->nullable();
            $table->string('tire')->nullable();
            $table->string('nea')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('supporting_documents_path')->nullable();
            $table->text('additional_docs_paths')->nullable();
            $table->string('student_request_id')->nullable();
            $table->string('unit')->nullable();
            $table->string('exam_session')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('migrated_student_record');
    }
};
