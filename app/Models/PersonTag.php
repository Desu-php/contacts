<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonTag extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'person_id',
        'tag_id'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
