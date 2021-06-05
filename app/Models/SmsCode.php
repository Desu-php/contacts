<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SmsCode extends Model
{

    protected $table = 'sms_codes';
    protected $fillable = ['code', 'phone', 'created_at','try', 'id'];

}
