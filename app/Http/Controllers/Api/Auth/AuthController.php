<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Phone;
use App\Http\Controllers\Controller;
use App\Models\SmsCode;
use App\Models\User;
use App\Services\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    //
    const Password = 'Passw0rd';

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone_number',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$this->authCheck($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
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

        return response()->json([
            'message' => 'Смс отправлен',
        ]);
    }

    public function checkCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone_number',
            'code' => 'required|code:' . Phone::formatCorrected($request->phone) . '|code_try:' . Phone::formatCorrected($request->phone),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        if (!$this->authCheck($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->getTokenAndRefreshToken($request->input('phone'), self::Password);
    }

    private function getTokenAndRefreshToken($phone, $password)
    {
        $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('passport.OAUTH_PWD_GRANT_CLIENT_ID'),
            'client_secret' => config('passport.OAUTH_PWD_GRANT_CLIENT_SECRET'),
            'username' => $phone,
            'password' => $password,
            'scope' => ''
        ]);
        return $response->json();
    }

    private function authCheck($request)
    {
        $attempt = $request->only('phone');
        $attempt['phone'] = Phone::formatCorrected($attempt['phone']);
        $attempt['password'] = self::Password;

        $user = User::where('phone', $attempt['phone'])->exists();

        if (!$user){
            User::create([
                'phone' => $attempt['phone'],
                'password' => Hash::make(self::Password)
            ]);
            return true;
        }


        if (!Auth::attempt($attempt)) {
            return false;
        }
        return true;
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone_number',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        return $this->refreshToken($request->refresh_token);
    }

    private function refreshToken($refresh_token)
    {
        $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => config('passport.OAUTH_PWD_GRANT_CLIENT_ID'),
            'client_secret' => config('passport.OAUTH_PWD_GRANT_CLIENT_SECRET'),
            'scope' => '',
        ]);

        return $response->json();
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($access_token, $refresh_token)
    {
        return response()->json([
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        ]);
    }

}
