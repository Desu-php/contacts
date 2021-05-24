<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name',
        'lat',
        'lon'
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_cities');
    }
}
