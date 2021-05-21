<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonTag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    public function get_list_tags()
    {
        return response()->json(PersonTag::select(['person_id', 'tag'])->with('person')->get());
    }
}
