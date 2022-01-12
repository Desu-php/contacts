<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function destroy()
    {
        Log::info('Пользователь ' . Auth::user()->phone . ' удалил себя из системы');
        return response()->json(User::destroy(Auth::id()));
    }

    public function change_user_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'given_name' => 'nullable|string|max:255',
            'family_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'my_phone' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        }

        $data = $validator->validated();


        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'avatars');
        }

        Auth::user()->update($data);

        return response()->json([
            'message' => 'Успешно обновлен'
        ]);
    }

    private function uploadFile($file, $dir = '')
    {
        return 'storage/' . $file->store('/uploads/' . $dir, 'public');
    }

    public function me()
    {
        return Auth::user();
    }

}
