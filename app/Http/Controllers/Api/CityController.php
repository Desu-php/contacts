<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    //

    public function get_list_towns()
    {
        return response()->json(City::select(['id','name', 'lat', 'lon', 'user_id', 'public'])
            ->where('public', City::PUBLIC)
            ->orWhereHas('user', function (Builder $builder){
                $builder->where('user_id', Auth::id());
            })
            ->get());
    }

    public function get_list_city()
    {
        return response()->json(City::select(['name','id'])->with('persons')->get());
    }
}
