<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name',
        'full_name',
        'english_name',
        'alpha2',
        'alpha3',
        'iso',
        'part_world',
        'location',
        'lat',
        'lon',
    ];
}
