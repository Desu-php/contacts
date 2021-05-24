<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    //
    public function get_list_persons(Request $request)
    {
        $persons = Person::where('user_id', Auth::id())
            ->with([
                'notes.file',
                'tags',
                'cities',
                'companies',
                'links',
                'files',
                'activities',
                'contacts',
                'infos',
                'connections',
                'multiplelds'
            ])->paginate();

        return response()->json($persons);
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|mimes:png,jpeg,jpg,webp,gif',
            'person_id' => 'required|exists:persons,id'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $filePath = 'storage/'.$request->file('image')->store('/uploads/thumbnailImages','public');

        Person::where('id', $request->person_id)
            ->update([
                'thumbnailImage' => $filePath
            ]);

        return response()->json($filePath);
    }
}
