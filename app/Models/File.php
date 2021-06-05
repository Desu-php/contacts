<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory,Uuids;

    protected $fillable = [
        'path',
        'type',
        'size',
        'id'
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_files');
    }
}
