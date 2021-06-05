<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonCity extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'city_id',
        'person_id',
        'id'
    ];
    public $timestamps = false;
}
