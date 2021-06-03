<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharingUser extends Model
{
    use HasFactory, Uuids;

    const ACCESS_ALLOWED = 1;
    const ACCESS_DENIED = 0;

    protected $fillable = [
        'sharing_id',
        'user_id',
        'access'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharing()
    {
        return $this->belongsTo(Sharing::class);
    }
}
