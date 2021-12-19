<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;


class CountryController extends Controller
{
    //

    public function getCountries()
    {
        return response()->json(Country::all());
    }
}
