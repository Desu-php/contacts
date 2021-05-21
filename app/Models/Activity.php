<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function person()
    {
        return $this->belongsToMany(Person::class,'person_activities');
    }
}
