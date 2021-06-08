<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Phone;
use App\Http\Controllers\Controller;
use App\Models\SmsCode;
use App\Services\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function checkCode(Request $request)
    {
        $this->validate($request,[
            'phone' => 'required|phone_number',
            'password' => 'required|string',
            'code' => 'required|code:' . Phone::formatCorrected($request->phone) . '|code_try:' . Phone::formatCorrected($request->phone),
        ]);

        if (!$this->authCheck($request)) {
            return back()->withErrors('error', 'Unauthorized');
        }

        return  redirect()->to(Session::get('sharing_url'));
    }

    private function authCheck($request)
    {
        $attempt = $request->only('phone', 'password');
        $attempt['phone'] = Phone::formatCorrected($attempt['phone']);

        if (!Auth::attempt($attempt)) {
            return false;
        }
        return true;
    }

}
