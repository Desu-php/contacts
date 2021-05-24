<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonNote extends Model
{
    use HasFactory,Uuids;

    protected $table = 'person_notes';

    protected $fillable = [
        'person_id',
        'address',
        'text',
        'note',
        'type',
        'protected',
        'lat',
        'lon',
        'file_id'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
