<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phones()
    {
        return $this->hasMany(PersonPhone::class);
    }

    public function links()
    {
        return $this->hasMany(PersonLink::class);
    }

    public function notes()
    {
        return $this->hasMany(PersonNote::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'person_files')->withPivot('type');
    }

    public function tags()
    {
        return $this->hasMany(PersonTag::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'person_cities');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'person_activities');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'person_companies')->withPivot('position');
    }

    public function connections()
    {
        return $this->belongsToMany(Company::class, 'person_connection', 'who_whorm', 'who');
    }

    public function contacts()
    {
        return $this->hasMany(PersonContact::class);
    }

    public function infos()
    {
        return $this->belongsToMany(PersonInfo::class, 'person_info_values')->withPivot('value');
    }
}
