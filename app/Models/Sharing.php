<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sharing extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'user_id',
        'name',
        'type_access',
        'open',
        'id'
    ];

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
}
