<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name'
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_companies')->withPivot('position');
    }
}
