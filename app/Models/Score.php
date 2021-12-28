<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'score',
        'percent',
        'user_id',
        'request_score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
