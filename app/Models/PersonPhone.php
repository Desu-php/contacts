<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonPhone extends Model
{
    use HasFactory;

    protected $table = 'person_phones';

    protected $fillable = [
        'person_id',
        'phone'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
