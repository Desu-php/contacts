<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    //

    public function get_list_company()
    {
        return response()->json(Company::select('name', 'id')->with('persons')->get());
    }
}
