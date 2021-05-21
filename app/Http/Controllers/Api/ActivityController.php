<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    //
//    public function get_list_activity()
//    {
//        return response()->json(Activity::select('name')->get());
//    }

    public function get_list_activity()
    {
        return response()->json(Activity::select('id','name')->with('person')->get());
    }
}
