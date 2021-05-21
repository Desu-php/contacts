<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //

    public function get_list_towns()
    {
        return response()->json(City::select(['name', 'lat', 'lon'])->get());
    }

    public function get_list_city()
    {
        return response()->json(City::select(['name','id'])->with('persons')->get());
    }
}
