<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInfoValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'person_info_id',
        'value'
    ];
}
