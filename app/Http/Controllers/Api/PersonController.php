<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
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
            ])
            ->where('me', 0)
            ->paginate();

        $log = LogActivity::where('user_id', Auth::id())
            ->orderBy('id', 'DESC')->first();

        return response()->json(['data' => $persons, 'last_id' => !is_null($log)?$log->id:null]);
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $filePath = $this->uploadFile($request->file('file'), 'files');

        return response()->json($filePath);
    }

    public function get_user_info()
    {
        $persons = Person::where('user_id', Auth::id())
            ->where('me', 1)
            ->with([
                'tags',
                'cities',
                'companies',
                'activities',
                'contacts',
                'infos',
                'profileInfo'
            ])->first();

        return response()->json($persons);
    }
}
