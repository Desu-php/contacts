<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonNote;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    //

    public function get_list_note()
    {
        return response()->json(PersonNote::select(
            [
                'type',
                'address',
                'lat',
                'lon',
                'created_at',
                'pin',
                'file_id',
                'person_id'
            ])
            ->with([
                'file',
                'person'
            ])->get());
    }
}
