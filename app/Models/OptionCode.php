<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionCode extends Model
{
    protected $table = 'option_codes';

    protected $fillable = [
        'specification_code',
        'entry_code',
        'gcse_subject',
        'paper_component',
        'component_code',
        'component_name',
        'component_type',
        'selection_group',
        'board',
        'qualification',
    ];
}
