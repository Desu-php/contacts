<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Phone;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function checkCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone_number',
            'password' => 'required|string',
            'code' => 'required|code:' . Phone::formatCorrected($request->phone) . '|code_try:' . Phone::formatCorrected($request->phone),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        if (!$this->authCheck($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return  response()->json([
            'url' => Session::get('sharing_url')
        ]);
    }

    private function authCheck($request)
    {
        $attempt = $request->only('phone', 'password');
        $attempt['phone'] = Phone::formatCorrected($attempt['phone']);

        if (!Auth::attempt($attempt)) {
            return false;
        }
        $request->session()->regenerate();
        return true;
    }
}
