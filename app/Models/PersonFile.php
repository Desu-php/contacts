<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonFile extends Model
{
    use HasFactory,Uuids;
    protected $fillable = [
        'person_id',
        'file_id',
        'type',
        'id'
    ];


}
