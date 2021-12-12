<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, Uuids;

    const delivered = 'поставлена';
    const completed = 'выполнена';

    protected $perPage = 50;

    protected $fillable = [
        'name',
        'description',
        'status_id',
        'reminder',
        'user_id'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
