<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Socialite;
use Auth;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        return Socialite::driver('eveonline')->scopes(['esi-mail.send_mail.v1','esi-mail.read_mail.v1'])->redirect();
    }
    public function redirectToProvider(Request $request)
    {
        return Socialite::driver('eveonline')->redirect();

    }

    public function handleProviderCallback(\App\Eve\UserService $userService)
    {
        $user = Socialite::driver('eveonline')->user();
        // Auth::loginUsingId(1);
        $laravelUser = User::find($user->id);
        if ($laravelUser == null) {
            $laravelUser = new User();
        }
        $time = $user->expiresIn + time();

        $laravelUser->id = $user->id;
        $laravelUser->name = $user->name;
        $laravelUser->token_expiry = date('Y-m-d H:i:s',$time);
        $laravelUser->token = $user->token;
        $laravelUser->refresh_token = $user->refreshToken;
        $laravelUser->save();

        Auth::loginUsingId($user->id);
        $userService->update($laravelUser);

        return redirect()->route('home');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
