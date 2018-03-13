<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use \App\DiscordUser;

class DiscordController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('discord')->scopes(['guilds.join'])->redirect();
    }

    public function handleProviderCallback(\App\Eve\Discord $discordService,\App\Eve\Groups $group)
    {
        $user = Socialite::driver('discord')->user();
        $laravelUser = Auth::User();
        $discord = DiscordUser::where('user_id',$laravelUser->id)->first();
        if ($discord == null) {
            $discord = new DiscordUser();
        }

        if (DiscordUser::where('id','=',$user->id)->count() > 0) {
            return redirect()->route('home')->with('message','That discord user is already registered with an eve account');
        }

        $discord->user_id = $laravelUser->id;
        $discord->username = $user->nickname;
        $discord->id = $user->id;
        $discord->token = $user->token;

        $discord->refresh_token = $user->refreshToken;
        $time = $user->expiresIn + time();
        $discord->token_expiry = date('Y-m-d H:i:s',$time);
        $discord->save();

        $group->runRules($laravelUser);

        $discordService->addMember($laravelUser);
        return redirect()->route('home');
    }

    public function revokeDiscord(Request $request)
    {
        DiscordUser::where('user_id','=',Auth::user()->id)->delete();
        return redirect()->route('home')->with('message','Successfully cleared your discord accounts');
    }
}
