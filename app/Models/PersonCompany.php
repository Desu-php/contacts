<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'company_id',
        'position'
    ];

    public $timestamps = false;
}
