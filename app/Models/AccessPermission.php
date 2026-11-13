<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessPermission extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'page_name', 'can_access'];

    protected $casts = [
        'can_access' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
