<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';
    use HasFactory,Uuids;

    protected $fillable = [
        'givenName',
        'familyName',
        'middleName',
        'moreNo',
        'reminderCall',
        'removed',
        'thumbnailImage',
        'user_id',
        'me',
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsToMany(Tag::class, 'person_tags');
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
        return $this->belongsToMany(Company::class, 'person_companies');
    }

    public function connections()
    {
        return $this->belongsToMany(Person::class, 'person_connections', 'with_whom', 'who', 'id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(PersonContact::class);
    }

    public function infos()
    {
        return $this->belongsToMany(PersonInfo::class, 'person_info_values')->withPivot(['value', 'id']);
    }

    public function multiplelds()
    {
        return $this->hasMany(Multipleld::class);
    }

    public function profileInfo()
    {
        return $this->hasOne(ProfileInfo::class);
    }
}
