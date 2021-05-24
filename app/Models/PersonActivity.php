<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonActivity extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'person_id',
        'activity_id'
    ];

    public $timestamps = false;
}
