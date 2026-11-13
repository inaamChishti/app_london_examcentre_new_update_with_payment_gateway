<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'session',
        'student_id',
        'name',
        'payment_received',
        'amount_received',
        'confirmed_by',
        'payment_method',
        'note',
        'receipt_path',
    ];

    protected $casts = [
        'amount_received' => 'decimal:2',
    ];
}
