<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()){
            return $next($request);
        }else{
            Session::put('sharing_url', $request->url());
            $uri_path = $request->path();
            $uri_parts = explode('/', $uri_path);
            $uri_tail = end($uri_parts);
            $user = User::whereHas('sharings', function ($query) use ($uri_tail){
                $query->where('id', $uri_tail);
            })->with('sharings')->first();

            $sharingText = '';
            if (!is_null($user)){
                $sharingText = $user->sharings->first()->name.' от '.$user->phone;
            }

            Session::put('sharingText',$sharingText);
            Session::save();
            return redirect()->route('login');
        }

    }
}
