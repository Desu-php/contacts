<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory,Uuids;

    const PUBLIC = 1;

    protected $fillable = [
        'name',
        'id',
        'public',
        'user_id'
    ];

    public function person()
    {
        return $this->belongsToMany(Person::class,'person_activities');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
