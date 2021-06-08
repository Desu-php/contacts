<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Phone;
use App\Http\Controllers\Controller;
use App\Models\SmsCode;
use App\Providers\RouteServiceProvider;
use App\Services\Sms;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function logout()
    {
        Auth::logout();
        return back();
    }

    private function authCheck($request)
    {
        $attempt = $request->only('phone', 'password');
        $attempt['phone'] = Phone::formatCorrected($attempt['phone']);

        if (!Auth::attempt($attempt)) {
            return false;
        }
        Auth::logout();
        return true;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|phone_number',
            'password' => 'required|string',
        ]);


        if (!$this->authCheck($request)) {
            return back()->withErrors('phone', 'Логин или пароль неверный');
        }

        if (env('APP_ENV') == 'production') {
            $code = rand(1000, 9999);
            $sms = new Sms([$request->phone], $code);
            $response = $sms->send();

            if (!$response['success']) {
                return response()->json($response);
            }
        } else {
            $code = 1234;
        }

        SmsCode::create([
            'code' => $code,
            'phone' => Phone::formatCorrected($request->phone),
            'try' => 0
        ]);

        Session::flash('phone', $request->phone);
        Session::flash('password', $request->password);
        Session::flash('status', true);
        return back();
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
}
