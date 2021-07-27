<?php


namespace App\Traits;


use App\Models\SharingUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Sharing AS ModelSharing;

trait Sharing
{
    public function show(Request $request, $id)
    {
        $sharing = \App\Models\Sharing::where('id', $id)
            ->where('open', ModelSharing::OPEN)
            ->first();

        if (is_null($sharing)) {
            if ($request->expectsJson()){
                return response()->json([
                    'error' => 'sharing not found'
                ], 404);
            }else{
                abort(404);
            }

        }

        if ($sharing->user_id != Auth::id()) {

            $sharingUser = SharingUser::firstOrCreate([
                'user_id' => Auth::id(),
                'sharing_id' => $sharing->id
            ],
                [
                    'access'  => 1
                ]
            );

            if ($sharingUser->access === SharingUser::ACCESS_DENIED) {
                if ($request->expectsJson()){
                    return response()->json([
                        'message' => 'Доступ к списку запрещен'
                    ], 403);
                }else{
                    abort(403);
                }
            }
        }

        if ($request->expectsJson()){
            return response()->json([
                'message' => 'Доступ к списку получен'
            ]);
        }else{
            return view('sharing');
        }

    }
}
