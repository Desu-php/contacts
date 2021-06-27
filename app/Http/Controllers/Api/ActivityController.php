<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    //
//    public function get_list_activity()
//    {
//        return response()->json(Activity::select('name')->get());
//    }

    public function get_list_activities()
    {
        return response()->json(Activity::select('id','name', 'user_id', 'public')
            ->where('public', Activity::PUBLIC)
            ->orWhereHas('user', function (Builder  $builder){
                return $builder->where('user_id', Auth::id());
            })
            ->get());
    }
}
