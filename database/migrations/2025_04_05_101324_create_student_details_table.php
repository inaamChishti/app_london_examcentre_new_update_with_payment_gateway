<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('surname');
            $table->string('first_name');
            $table->date('dob');
            $table->string('gender');
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
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



            $table->text('day')->nullable();
            $table->text('time')->nullable();
            $table->text('date')->nullable();
            $table->text('duration')->nullable();

            $table->text('exam_board')->nullable();
            $table->text('course_type')->nullable();
            $table->text('subject_name')->nullable();
            $table->text('subject_code')->nullable();
            $table->text('exams')->nullable();
            $table->text('tire')->nullable();
            $table->text('nea')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('supporting_documents_path')->nullable();
            $table->json('additional_docs_paths')->nullable();
            $table->unsignedBigInteger('student_request_id')->nullable();
            $table->timestamps();

            $table->foreign('student_request_id')->references('id')->on('student_requests')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_details');
    }
}
