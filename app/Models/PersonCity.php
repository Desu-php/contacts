<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'person_id'
    ];
    public $timestamps = false;
}
