<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonConnection extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'id',
        'with_whom',
        'who'
    ];

    public $timestamps = false;
}
