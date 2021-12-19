<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryPerson extends Model
{
    use HasFactory;

    protected $table = 'country_person';

    protected $fillable = [
        'person_id',
        'country_id'
    ];

    public $timestamps = false;
}
