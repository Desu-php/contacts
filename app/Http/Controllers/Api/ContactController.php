<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonContact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //

    public function get_list_multiple_id()
    {
        return response()->json(PersonContact::select('id', 'person_id')->with('person')->get());
    }

    public function get_list_contacts()
    {
        return response()->json(PersonContact::select(
            [
                'value',
                'contact_type_id',
                'person_id'
            ])->with([
            'type',
            'person'
        ])->get());
    }
}
