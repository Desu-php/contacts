<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonLink extends Model
{
    use HasFactory,Uuids;

    protected $table = 'person_links';

    protected $fillable = [
        'person_id',
        'link',
        'name'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
