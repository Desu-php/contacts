<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonCompany extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'person_id',
        'company_id',
    ];

    public $timestamps = false;
}
