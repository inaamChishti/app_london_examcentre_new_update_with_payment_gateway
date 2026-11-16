<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamBooking extends Model
{
    protected $fillable = [
        'student_request_id', 'booking_reference', 'amount_pence', 'currency',
        'payment_status', 'stripe_checkout_session_id', 'stripe_payment_intent_id',
        'payment_method', 'paid_at', 'metadata',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'metadata' => 'array',
        'amount_pence' => 'integer',
    ];

    public function studentRequest()
    {
        return $this->belongsTo(StudentRequest::class);
    }
}
