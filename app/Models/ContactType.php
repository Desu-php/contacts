<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'name',
        'image'
    ];

    public function contacts()
    {
        return $this->hasMany(PersonContact::class);
    }
}
