<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Uuids, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
        'phone',
        'id',
        'city',
        'company',
        'email',
        'family_name',
        'given_name',
        'note',
        'my_phone',
        'position',
        'site',
        'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime'
    ];


    public function getAvatarAttribute()
    {
        $image = $this->image;
        return $image ? asset($image) : null;
    }

    public function findForPassport($username) {

        return $this->where('phone', $username)->first();
    }

    public function profile()
    {
        return $this->hasOne(Person::class)->where('me', 1);
    }

    public function persons()
    {
        return $this->hasMany(Person::class)->where('me', 0);
    }

    public function sharings()
    {
        return $this->hasMany(Sharing::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
