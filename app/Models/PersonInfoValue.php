<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInfoValue extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'person_id',
        'person_info_id',
        'value',
        'id'
    ];
}
