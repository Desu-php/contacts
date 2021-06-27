<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, Uuids;

    const PUBLIC = 1;

    protected $fillable = [
        'name',
        'lat',
        'lon',
        'id',
        'public',
        'user_id'
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_cities');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
