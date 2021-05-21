<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonInfo;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    //

    public function get_list_info()
    {
        return response()->json(PersonInfo::select(['name', 'id'])->with('persons')->get());
    }
}
