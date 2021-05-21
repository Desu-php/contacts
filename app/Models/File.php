<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'type',
        'size',
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_files');
    }
}
