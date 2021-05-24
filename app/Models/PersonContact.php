<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonContact extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'contact_type_id',
        'value',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function type()
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id');
    }
}
