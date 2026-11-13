<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'current_domain',
        'surname',
        'first_name',
        'dob',
        'gender',
        'address_line1',
        'address_line2',
        'postcode',
        'uci_number',
        'contact_number',
        'email',
        'emergency_contact',
        'uln',
        'medical_condition',
        'access_arrangement',
        'access_arrangement_explanation',
        'practical_endorsement',
        'course_work',
        'course_work_uci',
        'exam_board',
        'course_type',
        'subject_name',
        'subject_code',
        'tire',
        'nea',
        'photo_path',
        'id_document_path',
        'supporting_documents_path',
        'additional_docs_paths',
        'is_approved',
        'exam_session',
        'signature',
        'form_filling_date',
        'need_predicted_grades',
        'need_mock_exams',
    ];

    protected $casts = [
        'exam_board' => 'array',
        'course_type' => 'array',
        'subject_name' => 'array',
        'subject_code' => 'array',
        'tire' => 'array',
        'nea' => 'array',
        'additional_docs_paths' => 'array',
        'access_arrangement' => 'boolean',
    ];

    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class, 'student_request_id');
    }
}
