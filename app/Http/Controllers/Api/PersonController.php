<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    //
    public function get_list_persons(Request $request)
    {
        $persons = Person::where('user_id', Auth::id())
            ->with([
                'phones',
                'notes.file',
                'tags',
                'cities',
                'companies',
                'links',
                'files',
                'activities',
                'contacts',
                'infos'
            ])->paginate();

        return response()->json($persons);
    }
}
