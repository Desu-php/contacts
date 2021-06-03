<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multipleld extends Model
{
    use HasFactory,Uuids;

    protected $table = 'multipleld';

    protected $fillable = ['person_id', 'identifier'];
}
