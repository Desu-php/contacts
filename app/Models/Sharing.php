<?php

namespace App\Models;

use App\Traits\PerPage;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sharing extends Model
{
    use HasFactory, Uuids

    const OPEN = 1;
    const CLOSE = 0;

    protected $fillable = [
        'user_id',
        'name',
        'type_access',
        'open',
        'id'
    ];

    protected $perPage = 50;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'sharing_users')->withPivot('access');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'sharing_cities');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'sharing_companies');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'sharing_activities');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'sharing_tags');
    }

    public function cities_ids(){
        return $this->hasMany(SharingCity::class);
    }

    public function activities_ids(){
        return $this->hasMany(SharingActivity::class);
    }

    public function tags_ids(){
        return $this->hasMany(SharingTag::class);
    }

    public function companies_ids(){
        return $this->hasMany(SharingCompany::class);
    }
}
