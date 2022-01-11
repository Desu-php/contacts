<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //

    public function destroy()
    {
        Log::info('Пользователь '.Auth::user()->phone.' удалил себя из системы');
        return response()->json(User::destroy(Auth::id()));
    }
}
